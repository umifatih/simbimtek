<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\Pendaftaran;
use App\Models\SiteSetting;
use App\Support\DocxPreviewer;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\TemplateProcessor;

class AdminCetakController extends Controller
{
    public function index()
    {
        $daftarKegiatan = Kegiatan::orderByDesc('tanggal_mulai')->get();

        return view('admin.cetak.index', compact('daftarKegiatan'));
    }

    /** ===== Preview (HTML asli hasil convert docx) ===== */

    public function previewDaftarPeserta(Request $request)
    {
        [$kegiatan, $peserta] = $this->ambilData($request);
        $setting = SiteSetting::current();

        $path = $this->buatDaftarPeserta($kegiatan, $peserta, $setting);
        $html = DocxPreviewer::toHtml($path);
        @unlink($path);

        return view('cetak.preview-docx', [
            'html'        => $html,
            'title'       => 'Daftar Peserta',
            'unduh_url'   => route('admin.cetak.daftar-peserta', ['kegiatan_id' => $kegiatan->id]),
            'kembali_url' => route('admin.cetak.index'),
        ]);
    }

    public function previewDaftarHadir(Request $request)
    {
        [$kegiatan, $peserta] = $this->ambilData($request);
        $jadwalHarian = $this->jadwalHarian($kegiatan);
        $setting = SiteSetting::current();

        $path = $this->buatDaftarHadir($kegiatan, $peserta, $jadwalHarian, $setting);
        $html = DocxPreviewer::toHtml($path);
        @unlink($path);

        return view('cetak.preview-docx', [
            'html'        => $html,
            'title'       => 'Daftar Hadir',
            'unduh_url'   => route('admin.cetak.daftar-hadir', ['kegiatan_id' => $kegiatan->id]),
            'kembali_url' => route('admin.cetak.index'),
        ]);
    }

    public function previewKonsumsiAtk(Request $request)
    {
        [$kegiatan, $peserta] = $this->ambilData($request);
        $jadwalHarian = $this->jadwalHarian($kegiatan);
        $setting = SiteSetting::current();

        $path = $this->buatKonsumsiAtk($kegiatan, $peserta, $jadwalHarian, $setting);
        $html = DocxPreviewer::toHtml($path);
        @unlink($path);

        return view('cetak.preview-docx', [
            'html'        => $html,
            'title'       => 'Daftar Penerimaan Konsumsi & ATK',
            'unduh_url'   => route('admin.cetak.konsumsi-atk', ['kegiatan_id' => $kegiatan->id]),
            'kembali_url' => route('admin.cetak.index'),
        ]);
    }

    /** ===== Daftar Peserta (Word) ===== */
    public function unduhDaftarPeserta(Request $request)
    {
        [$kegiatan, $peserta] = $this->ambilData($request);
        $setting = SiteSetting::current();

        $path = $this->buatDaftarPeserta($kegiatan, $peserta, $setting);

        return response()
            ->download($path, 'Daftar-Peserta-' . Str::slug($kegiatan->nama) . '.docx')
            ->deleteFileAfterSend(true);
    }

    /** ===== Daftar Hadir (Word, per hari) ===== */
    public function unduhDaftarHadir(Request $request)
    {
        [$kegiatan, $peserta] = $this->ambilData($request);
        $jadwalHarian = $this->jadwalHarian($kegiatan);
        $setting = SiteSetting::current();

        $path = $this->buatDaftarHadir($kegiatan, $peserta, $jadwalHarian, $setting);

        return response()
            ->download($path, 'Daftar-Hadir-' . Str::slug($kegiatan->nama) . '.docx')
            ->deleteFileAfterSend(true);
    }

    /** ===== Konsumsi & ATK (Word, per hari) ===== */
    public function unduhKonsumsiAtk(Request $request)
    {
        [$kegiatan, $peserta] = $this->ambilData($request);
        $jadwalHarian = $this->jadwalHarian($kegiatan);
        $setting = SiteSetting::current();

        $path = $this->buatKonsumsiAtk($kegiatan, $peserta, $jadwalHarian, $setting);

        return response()
            ->download($path, 'Konsumsi-ATK-' . Str::slug($kegiatan->nama) . '.docx')
            ->deleteFileAfterSend(true);
    }

    /**
     * Isi template daftar-peserta.docx dan simpan ke file sementara.
     * Dipakai baik oleh unduh (langsung didownload) maupun preview
     * (di-convert dulu ke HTML lalu filenya dihapus).
     */
    private function buatDaftarPeserta(Kegiatan $kegiatan, $peserta, SiteSetting $setting): string
    {
        $template = new TemplateProcessor(storage_path('app/templates/daftar-peserta.docx'));

        $this->isiKop($template, $setting);
        $template->setValue('nama_kegiatan', $kegiatan->nama);
        $template->setValue('hari_tanggal', $kegiatan->hari_tanggal);
        $template->setValue('lokasi', $kegiatan->lokasi);
        $template->setValue('tanggal_cetak', now('Asia/Jakarta')->translatedFormat('d F Y'));
        $template->setValue('nama_panitia', $kegiatan->nama_panitia ?? '-');
        $template->setValue('nip_panitia', $kegiatan->nip_panitia ?? '-');

        if ($peserta->isNotEmpty()) {
            $template->cloneRow('no', $peserta->count());

            foreach ($peserta as $i => $p) {
                $ke = $i + 1;
                $template->setValue("no#{$ke}", $ke);
                $template->setValue("nama_gelar#{$ke}", $p->nama_gelar);
                $template->setValue("nip#{$ke}", $p->nip);
                $template->setValue("unit_kerja#{$ke}", strtoupper($p->unit_kerja));
                $template->setValue("jabatan#{$ke}", $p->jabatan);
            }
        } else {
            $template->setValue('no#1', '-');
            $template->setValue('nama_gelar#1', 'Belum ada peserta terdaftar untuk kegiatan ini.');
            $template->setValue('nip#1', '');
            $template->setValue('unit_kerja#1', '');
            $template->setValue('jabatan#1', '');
        }

        return $this->simpanSementara($template, 'daftar-peserta-' . Str::slug($kegiatan->nama));
    }

    /**
     * Isi template daftar-hadir.docx (per hari, blok HARI di-clone) dan simpan sementara.
     */
    private function buatDaftarHadir(Kegiatan $kegiatan, $peserta, array $jadwalHarian, SiteSetting $setting): string
    {
        $template = new TemplateProcessor(storage_path('app/templates/daftar-hadir.docx'));
        $template->cloneBlock('HARI', count($jadwalHarian), true, true);

        $i = 0;
        foreach ($jadwalHarian as $hari) {
            $i++;

            $template->setValue("nama_aplikasi#{$i}", $setting->nama_aplikasi);
            $template->setValue("nama_kegiatan#{$i}", $kegiatan->nama);
            $template->setValue("hari_label#{$i}", $hari['label']);
            $template->setValue("tanggal_format#{$i}", $hari['tanggal_format']);
            $template->setValue("lokasi#{$i}", $kegiatan->lokasi);
            $template->setValue("nama_panitia#{$i}", $kegiatan->nama_panitia ?? '-');
            $template->setValue("nip_panitia#{$i}", $kegiatan->nip_panitia ?? '-');

            $this->isiLogo($template, $setting, "logo#{$i}");

            if ($peserta->isNotEmpty()) {
                $template->cloneRow("nama_gelar#{$i}", $peserta->count());

                foreach ($peserta as $j => $p) {
                    $ke = $j + 1;
                    $template->setValue("no#{$i}#{$ke}", $ke);
                    $template->setValue("nama_gelar#{$i}#{$ke}", $p->nama_gelar);
                    $template->setValue("unit_kerja#{$i}#{$ke}", strtoupper($p->unit_kerja));
                }
            } else {
                $template->setValue("no#{$i}#1", '-');
                $template->setValue("nama_gelar#{$i}#1", 'Belum ada peserta terdaftar.');
                $template->setValue("unit_kerja#{$i}#1", '');
            }
        }

        return $this->simpanSementara($template, 'daftar-hadir-' . Str::slug($kegiatan->nama));
    }

    /**
     * Isi template konsumsi-atk.docx (per hari, blok HARI di-clone) dan simpan sementara.
     */
    private function buatKonsumsiAtk(Kegiatan $kegiatan, $peserta, array $jadwalHarian, SiteSetting $setting): string
    {
        $template = new TemplateProcessor(storage_path('app/templates/konsumsi-atk.docx'));
        $template->cloneBlock('HARI', count($jadwalHarian), true, true);

        $i = 0;
        foreach ($jadwalHarian as $hari) {
            $i++;

            $template->setValue("nama_aplikasi#{$i}", $setting->nama_aplikasi);
            $template->setValue("nama_kegiatan#{$i}", $kegiatan->nama);
            $template->setValue("hari_label#{$i}", $hari['label']);
            $template->setValue("tanggal_format#{$i}", $hari['tanggal_format']);
            $template->setValue("lokasi#{$i}", $kegiatan->lokasi);
            $template->setValue("nama_panitia#{$i}", $kegiatan->nama_panitia ?? '-');
            $template->setValue("nip_panitia#{$i}", $kegiatan->nip_panitia ?? '-');

            $this->isiLogo($template, $setting, "logo#{$i}");

            if ($peserta->isNotEmpty()) {
                $template->cloneRow("nama_gelar#{$i}", $peserta->count());

                foreach ($peserta as $j => $p) {
                    $ke = $j + 1;
                    $template->setValue("no#{$i}#{$ke}", $ke);
                    $template->setValue("nama_gelar#{$i}#{$ke}", $p->nama_gelar);
                    $template->setValue("unit_kerja#{$i}#{$ke}", strtoupper($p->unit_kerja));
                }
            } else {
                $template->setValue("no#{$i}#1", '-');
                $template->setValue("nama_gelar#{$i}#1", 'Belum ada peserta terdaftar.');
                $template->setValue("unit_kerja#{$i}#1", '');
            }
        }

        return $this->simpanSementara($template, 'konsumsi-atk-' . Str::slug($kegiatan->nama));
    }

    /**
     * Ambil kegiatan terpilih beserta seluruh pendaftar di kegiatan itu,
     * diurutkan berdasarkan nama (nama_gelar bukan kolom asli, jadi sort
     * dilakukan di collection, bukan lewat query builder).
     */
    private function ambilData(Request $request): array
    {
        $request->validate([
            'kegiatan_id' => 'required|exists:kegiatan,id',
        ]);

        $kegiatan = Kegiatan::findOrFail($request->query('kegiatan_id'));

        $peserta = Pendaftaran::with('peserta')
            ->where('kegiatan_id', $kegiatan->id)
            ->get()
            ->sortBy('nama_gelar')
            ->values();

        return [$kegiatan, $peserta];
    }

    /**
     * Bikin daftar "Hari 1", "Hari 2", dst sesuai rentang tanggal kegiatan,
     * dipakai untuk blok ${HARI} yang di-cloneBlock per hari.
     */
    private function jadwalHarian(Kegiatan $kegiatan): array
    {
        $jadwal = [];
        $periode = CarbonPeriod::create($kegiatan->tanggal_mulai, $kegiatan->tanggal_selesai);
        $hariKe = 1;

        foreach ($periode as $tanggal) {
            $jadwal[$hariKe] = [
                'tanggal'        => $tanggal->toDateString(),
                'tanggal_format' => $tanggal->translatedFormat('d F Y'),
                'label'          => "Hari {$hariKe}",
            ];
            $hariKe++;
        }

        return $jadwal;
    }

    private function isiKop(TemplateProcessor $template, SiteSetting $setting, string $tagNama = 'nama_aplikasi', string $tagLogo = 'logo'): void
    {
        $template->setValue($tagNama, $setting->nama_aplikasi);
        $this->isiLogo($template, $setting, $tagLogo);
    }

    private function isiLogo(TemplateProcessor $template, SiteSetting $setting, string $tagLogo): void
    {
        if ($setting->logo_path && Storage::disk('public')->exists($setting->logo_path)) {
            $template->setImageValue($tagLogo, [
                'path'   => Storage::disk('public')->path($setting->logo_path),
                'width'  => 60,
                'height' => 60,
                'ratio'  => false,
            ]);
        }
        // Kalau logo belum diset di Pengaturan, tag ${logo} akan tampil
        // apa adanya (teks kosong) di dokumen — tidak error.
    }

    /**
     * Simpan TemplateProcessor yang sudah diisi ke file sementara unik,
     * lalu kembalikan path-nya. Pemanggil yang menentukan file ini
     * didownload (lalu dihapus oleh deleteFileAfterSend) atau
     * di-convert ke HTML untuk preview (lalu dihapus manual).
     */
    private function simpanSementara(TemplateProcessor $template, string $prefix): string
    {
        $folderTmp = storage_path('app/tmp');
        if (! is_dir($folderTmp)) {
            mkdir($folderTmp, 0755, true);
        }

        $path = $folderTmp . '/' . $prefix . '-' . uniqid() . '.docx';
        $template->saveAs($path);

        return $path;
    }
}