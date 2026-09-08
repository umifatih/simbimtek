<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Peserta;
use App\Models\Pendaftaran;
use App\Models\DataMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\TemplateProcessor;

class PendaftaranController extends Controller
{
    public function create()
    {
        $kegiatanList = Kegiatan::withCount(['pendaftaran as kuota_terisi'])
            ->where('status', 'dibuka')
            ->orderBy('tanggal_mulai')
            ->get();

        return view('peserta.daftar', compact('kegiatanList'));
    }

    /**
     * Dipakai saat suggestion "Unit Kerja" diklik di form pendaftaran.
     * Cari sekolah di data master (nama sekolah / unit kerja) untuk ditampilkan
     * sebagai daftar saran ketika peserta mengetik.
     */
    public function cariSekolah(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        if (mb_strlen($q) < 2) {
            return response()->json(['data' => []]);
        }

        $hasil = DataMaster::where('unit_kerja', 'like', "%{$q}%")
            ->orWhere('data_sekolah', 'like', "%{$q}%")
            ->orWhere('desa', 'like', "%{$q}%")
            ->orderBy('unit_kerja')
            ->limit(8)
            ->get(['unit_kerja', 'nama_kepsek', 'nip_kepsek'])
            ->map(fn ($s) => [
                'unit_kerja'  => $s->unit_kerja,
                'nama_kepsek' => $s->nama_kepsek,
                'nip_kepsek'  => $s->nip_kepsek,
            ]);

        return response()->json(['data' => $hasil]);
    }

    /**
     * Pencarian saran peserta yang PERNAH mendaftar — bisa lewat NIP maupun Nama.
     * Dipakai di kolom "NIP" dan "Nama dan Gelar": peserta ketik sebagian NIP atau
     * sebagian nama, muncul daftar saran, begitu diklik seluruh data diisi otomatis
     * lewat cariNip() di bawah (karena NIP dari saran ini sudah pasti unik).
     */
    public function cariPeserta(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        if (mb_strlen($q) < 3) {
            return response()->json(['data' => []]);
        }

        $hasil = Peserta::where('nip', 'like', "%{$q}%")
            ->orWhere('nama_gelar', 'like', "%{$q}%")
            ->orderBy('nama_gelar')
            ->limit(8)
            ->get(['nip', 'nama_gelar', 'unit_kerja'])
            ->map(fn ($p) => [
                'nip'        => $p->nip,
                'nama_gelar' => $p->nama_gelar,
                'unit_kerja' => $p->unit_kerja,
            ]);

        return response()->json(['data' => $hasil]);
    }

    /**
     * Ambil data lengkap satu peserta berdasarkan NIP persis (exact match).
     * Dipanggil otomatis begitu peserta mengetik NIP penuh, ATAU begitu peserta
     * memilih salah satu saran dari cariPeserta() di atas (baik saran itu muncul
     * dari pencarian NIP maupun pencarian Nama).
     */
    public function cariNip(string $nip)
    {
        $peserta = Peserta::where('nip', $nip)->first();

        if (! $peserta) {
            return response()->json(['ditemukan' => false]);
        }

        return response()->json([
            'ditemukan' => true,
            'data' => [
                'unit_kerja'        => $peserta->unit_kerja,
                'nama_gelar'        => $peserta->nama_gelar,
                'pangkat_golongan'  => $peserta->pangkat_golongan,
                'tempat_lahir'      => $peserta->tempat_lahir,
                'tanggal_lahir'     => optional($peserta->tanggal_lahir)->toDateString(),
                'jabatan'           => $peserta->jabatan,
                'email'             => $peserta->email,
                'nama_gelar_kepsek' => $peserta->nama_gelar_kepsek,
                'nip_kepsek'        => $peserta->nip_kepsek,
                'nip'               => $peserta->nip,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kegiatan_id'          => 'required|exists:kegiatan,id',
            'unit_kerja'           => 'required|string|max:255',
            'nama_gelar'           => 'required|string|max:255',
            'nip'                  => 'required|string|max:30',
            'pangkat_golongan'     => 'required|string|max:100',
            'tempat_lahir'         => 'required|string|max:100',
            'tanggal_lahir'        => 'required|date',
            'jabatan'              => 'required|in:Bendahara BOSP,Operator BOSP,lainnya',
            'jabatan_lainnya'      => 'nullable|string|max:100|required_if:jabatan,lainnya',
            'email'                => 'required|email|max:255',
            'nama_gelar_kepsek'    => 'required|string|max:255',
            'nip_kepsek'           => 'nullable|string|max:30',
            'setuju'               => 'required',
        ]);

        // Kalau peserta pilih "Lainnya", ganti nilai jabatan dengan teks yang mereka tulis
        if ($validated['jabatan'] === 'lainnya') {
            $validated['jabatan'] = $validated['jabatan_lainnya'];
        }
        unset($validated['jabatan_lainnya']);

        $peserta = Peserta::updateOrCreate(
            ['nip' => $validated['nip']],
            [
                'nama_gelar'           => $validated['nama_gelar'],
                'unit_kerja'           => $validated['unit_kerja'],
                'pangkat_golongan'     => $validated['pangkat_golongan'],
                'tempat_lahir'         => $validated['tempat_lahir'],
                'tanggal_lahir'        => $validated['tanggal_lahir'],
                'jabatan'              => $validated['jabatan'],
                'email'                => $validated['email'],
                'nama_gelar_kepsek'    => $validated['nama_gelar_kepsek'],
                'nip_kepsek'           => $validated['nip_kepsek'] ?? null,
            ]
        );

        $sudahDaftar = Pendaftaran::where('peserta_id', $peserta->id)
            ->where('kegiatan_id', $validated['kegiatan_id'])
            ->first();

        if ($sudahDaftar) {
            return back()->withErrors(['kegiatan_id' => 'Kamu sudah terdaftar di kegiatan ini sebelumnya.']);
        }

        $tahun = now()->format('Y');
        $urutan = Pendaftaran::whereYear('created_at', $tahun)->count() + 1;

        $pendaftaran = Pendaftaran::create([
            'peserta_id' => $peserta->id,
            'kegiatan_id' => $validated['kegiatan_id'],
            'nomor_pendaftaran' => "BT-{$tahun}-" . str_pad($urutan, 4, '0', STR_PAD_LEFT),
            'token_kehadiran' => Str::random(32),
            'status' => 'daftar',
        ]);

        return redirect()->route('cek-status', ['nomor_pendaftaran' => $pendaftaran->nomor_pendaftaran])
                         ->with('success', true);
    }

    public function unduh(Pendaftaran $pendaftaran, string $jenis)
    {
        $pendaftaran->load(['peserta', 'kegiatan']);
        $kegiatan = $pendaftaran->kegiatan;

        $templateMap = [
            'bukti'       => storage_path('app/templates/pendaftar.docx'),
            'surat-tugas' => storage_path('app/templates/surat-tugas.docx'),
            'sppd'        => storage_path('app/templates/sppd.docx'),
            'sertifikat'  => storage_path('app/templates/sertifikat.docx'),
        ];

        abort_unless(isset($templateMap[$jenis]), 404);

        if ($jenis === 'sertifikat') {
            abort_unless(
                $pendaftaran->status === 'sertifikat',
                403,
                'Sertifikat belum bisa diunduh — kehadiranmu belum tercatat lengkap.'
            );
        }

        $template = new TemplateProcessor($templateMap[$jenis]);

        $unitKerjaKop = new TextRun();
        $unitKerjaKop->addText(strtoupper($pendaftaran->unit_kerja), ['bold' => true, 'size' => 13]);
        $template->setComplexValue('unit_kerja_kop', $unitKerjaKop);

        $template->setValue('unit_kerja', strtoupper($pendaftaran->unit_kerja));

        $template->setValue('nomor_pendaftaran', $pendaftaran->nomor_pendaftaran);
        $template->setValue('nama', $pendaftaran->nama_gelar);
        $template->setValue('nip', $pendaftaran->nip);
        $template->setValue('jabatan', $pendaftaran->jabatan);
        $template->setValue('nama_ks', $pendaftaran->nama_gelar_kepsek);
        $template->setValue('nip_ks', $pendaftaran->nip_kepsek ?? '-');

        $template->setValue('nama_kegiatan', $kegiatan->nama);
        $template->setValue('hari_tanggal', $kegiatan->hari_tanggal);
        $template->setValue('waktu', substr($kegiatan->waktu, 0, 5));
        $template->setValue('lokasi', $kegiatan->lokasi);
        $template->setValue('nomor_surat_dasar', $kegiatan->nomor_surat_dasar ?? '-');
        $template->setValue(
            'tanggal_surat_dasar',
            optional($kegiatan->tanggal_surat_dasar)->translatedFormat('d F Y') ?? '-'
        );
        $template->setValue('nama_panitia', $kegiatan->nama_panitia ?? '-');
        $template->setValue('nip_panitia', $kegiatan->nip_panitia ?? '-');
        $template->setValue('tanggal_mulai', $kegiatan->tanggal_mulai->translatedFormat('d F Y'));
        $template->setValue('tanggal_ttd', $kegiatan->tanggal_mulai->translatedFormat('d F Y'));

        if ($jenis === 'sppd') {
            $this->isiRincianPerjalanan($template, $kegiatan, $pendaftaran);
        }

        if ($jenis === 'sertifikat') {
            $template->setValue('tanggal_selesai', $kegiatan->tanggal_selesai->translatedFormat('d F Y'));
        }

        $namaFile = $jenis . '-' . Str::slug($pendaftaran->nama_gelar) . '.docx';
        $folderTmp = storage_path('app/tmp');

        if (!is_dir($folderTmp)) {
            mkdir($folderTmp, 0755, true);
        }

        $pathSementara = $folderTmp . '/' . $namaFile;
        $template->saveAs($pathSementara);

        if (in_array($jenis, ['surat-tugas', 'sppd']) && $pendaftaran->status === 'daftar') {
            $pendaftaran->update(['status' => 'sppd']);
        }

        return response()->download($pathSementara, $namaFile)->deleteFileAfterSend(true);
    }

    private function isiRincianPerjalanan(TemplateProcessor $template, Kegiatan $kegiatan, Pendaftaran $pendaftaran): void
    {
        $mulai = $kegiatan->tanggal_mulai;
        $selesai = $kegiatan->tanggal_selesai;
        $periode = \Carbon\CarbonPeriod::create($mulai, $selesai);
        $jumlahHari = $mulai->diffInDays($selesai) + 1;

        $template->cloneRow('lokasi_hari', $jumlahHari);

        foreach ($periode as $index => $tanggal) {
            $ke = $index + 1;

            $template->setValue("romawi_hari#{$ke}", $this->angkaRomawi($index + 2));
            $template->setValue("lokasi_hari#{$ke}", $kegiatan->lokasi);
            $template->setValue("tanggal_hari#{$ke}", $tanggal->translatedFormat('d F Y'));
            $template->setValue("panitia_nama_hari#{$ke}", $kegiatan->nama_panitia ?? '-');
            $template->setValue("panitia_nip_hari#{$ke}", $kegiatan->nip_panitia ?? '-');
        }

        $template->setValue('romawi_akhir', $this->angkaRomawi($jumlahHari + 2));
        $template->setValue('tanggal_akhir', $selesai->translatedFormat('d F Y'));
    }

    private function angkaRomawi(int $angka): string
    {
        $map = ['M'=>1000,'CM'=>900,'D'=>500,'CD'=>400,'C'=>100,'XC'=>90,'L'=>50,'XL'=>40,'X'=>10,'IX'=>9,'V'=>5,'IV'=>4,'I'=>1];
        $hasil = '';
        foreach ($map as $roman => $nilai) {
            while ($angka >= $nilai) {
                $hasil .= $roman;
                $angka -= $nilai;
            }
        }
        return $hasil;
    }
}