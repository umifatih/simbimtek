<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Peserta;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\TemplateProcessor;

class PendaftaranController extends Controller
{
    /**
     * Tampilkan form pendaftaran, kirim daftar kegiatan yang statusnya 'dibuka'.
     * kuota_terisi dihitung otomatis dari jumlah pendaftaran yang sudah masuk.
     */
    public function create()
    {
        $kegiatanList = Kegiatan::withCount(['pendaftaran as kuota_terisi'])
            ->where('status', 'dibuka')
            ->orderBy('tanggal_mulai')
            ->get();

        return view('daftar.index', compact('kegiatanList'));
    }

    /**
     * AJAX: dipanggil dari form pendaftaran begitu peserta selesai mengetik NIP.
     * Kalau NIP sudah pernah dipakai daftar sebelumnya, kembalikan data dirinya
     * supaya form bisa diisi otomatis di sisi klien.
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
                'unit_kerja'           => $peserta->unit_kerja,
                'nama_gelar'           => $peserta->nama_gelar,
                'pangkat_golongan'     => $peserta->pangkat_golongan,
                'tempat_tanggal_lahir' => $peserta->tempat_tanggal_lahir,
                'jabatan'              => $peserta->jabatan,
                'email'                => $peserta->email,
                'nama_gelar_kepsek'    => $peserta->nama_gelar_kepsek,
                'nip_kepsek'           => $peserta->nip_kepsek,
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
            'tempat_tanggal_lahir' => 'required|string|max:100',
            'jabatan'              => 'required|in:Bendahara BOSP,Operator BOSP',
            'email'                => 'required|email|max:255',
            'nama_gelar_kepsek'    => 'required|string|max:255',
            'nip_kepsek'           => 'nullable|string|max:30',
            'setuju'               => 'required',
        ]);

        $peserta = Peserta::updateOrCreate(
            ['nip' => $validated['nip']],
            [
                'nama_gelar'           => $validated['nama_gelar'],
                'unit_kerja'           => $validated['unit_kerja'],
                'pangkat_golongan'     => $validated['pangkat_golongan'],
                'tempat_tanggal_lahir' => $validated['tempat_tanggal_lahir'],
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

        $pendaftaran->load(['peserta', 'kegiatan']);

        return view('daftar.sukses', compact('pendaftaran'));
    }

    public function unduh(Pendaftaran $pendaftaran, string $jenis)
    {
        $pendaftaran->load(['peserta', 'kegiatan']);
        $kegiatan = $pendaftaran->kegiatan;

        // ===== Bukti Pendaftaran: langsung dirender jadi PDF dari Blade, tanpa docx =====
        if ($jenis === 'bukti') {
            $pdf = Pdf::loadView('dokumen.bukti-pendaftaran', compact('pendaftaran'))
                ->setPaper('a4', 'portrait');

            $namaFile = 'bukti-pendaftaran-' . Str::slug($pendaftaran->nama_gelar) . '.pdf';

            return $pdf->download($namaFile);
        }

        $templateMap = [
            'surat-tugas' => storage_path('app/templates/surat-tugas.docx'),
            'sppd'        => storage_path('app/templates/sppd.docx'),
            'sertifikat'  => storage_path('app/templates/sertifikat.docx'),
        ];

        abort_unless(isset($templateMap[$jenis]), 404);

        // Sertifikat cuma boleh diunduh kalau absensi sudah lengkap
        if ($jenis === 'sertifikat') {
            abort_unless(
                $pendaftaran->status === 'sertifikat',
                403,
                'Sertifikat belum bisa diunduh — kehadiranmu belum tercatat lengkap.'
            );
        }

        $template = new TemplateProcessor($templateMap[$jenis]);

        // Unit kerja versi KOP SURAT: bold + ukuran 13, dipakai HANYA sekali di header
        $unitKerjaKop = new TextRun();
        $unitKerjaKop->addText(strtoupper($pendaftaran->unit_kerja), ['bold' => true, 'size' => 13]);
        $template->setComplexValue('unit_kerja_kop', $unitKerjaKop);

        // Unit kerja versi BADAN SURAT: teks polos, muncul berkali-kali (setValue otomatis ganti semua)
        $template->setValue('unit_kerja', strtoupper($pendaftaran->unit_kerja));

        // Data peserta
        $template->setValue('nomor_pendaftaran', $pendaftaran->nomor_pendaftaran);
        $template->setValue('nama', $pendaftaran->nama_gelar);
        $template->setValue('nip', $pendaftaran->nip);
        $template->setValue('jabatan', $pendaftaran->jabatan);
        $template->setValue('nama_ks', $pendaftaran->nama_gelar_kepsek);
        $template->setValue('nip_ks', $pendaftaran->nip_kepsek ?? '-');

        // Data kegiatan
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

        // Clone baris "hari" sebanyak jumlah hari kegiatan
        $template->cloneRow('lokasi_hari', $jumlahHari);

        foreach ($periode as $index => $tanggal) {
            $ke = $index + 1; // PHPWord mulai indeks clone dari 1

            $template->setValue("romawi_hari#{$ke}", $this->angkaRomawi($index + 2));
            $template->setValue("lokasi_hari#{$ke}", $kegiatan->lokasi);
            $template->setValue("tanggal_hari#{$ke}", $tanggal->translatedFormat('d F Y'));
            $template->setValue("panitia_nama_hari#{$ke}", $kegiatan->nama_panitia ?? '-');
            $template->setValue("panitia_nip_hari#{$ke}", $kegiatan->nip_panitia ?? '-');
        }

        // Baris terakhir (tiba kembali di sekolah asal)
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