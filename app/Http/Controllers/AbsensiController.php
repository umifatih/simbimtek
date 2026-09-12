<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Kegiatan;
use App\Models\Pendaftaran;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AbsensiController extends Controller
{
    public function indexScan(Request $request)
    {
        // Semua kegiatan tetap ditampilkan (termasuk yang sudah lewat) supaya
        // riwayat kehadiran lama tetap bisa dilihat & diunduh. Yang dibatasi
        // tanggal cuma proses SCAN QR-nya (lihat $bisaAbsen di bawah & di view).
        $daftarKegiatan = Kegiatan::orderByDesc('tanggal_mulai')->get();

        $kegiatan = null;
        $kegiatanIdDipilih = $request->query('kegiatan_id');

        if ($kegiatanIdDipilih) {
            $kegiatan = $daftarKegiatan->firstWhere('id', (int) $kegiatanIdDipilih);
        }

        // Kalau admin belum pilih apa-apa: prioritas 1) yang sedang berlangsung,
        // 2) yang paling dekat akan datang, 3) yang paling baru saja selesai.
        if (! $kegiatan) {
            $kegiatan = $daftarKegiatan->first(
                fn ($k) => $k->tanggal_mulai->lte(now()) && $k->tanggal_selesai->gte(now())
            );

            if (! $kegiatan) {
                $kegiatan = $daftarKegiatan
                    ->filter(fn ($k) => $k->tanggal_mulai->gt(now()))
                    ->sortBy('tanggal_mulai')
                    ->first();
            }

            if (! $kegiatan) {
                $kegiatan = $daftarKegiatan
                    ->filter(fn ($k) => $k->tanggal_selesai->lt(now()))
                    ->sortByDesc('tanggal_selesai')
                    ->first();
            }

            $kegiatan = $kegiatan ?? $daftarKegiatan->first();
        }

        // Absen (scan QR) cuma boleh sampai tanggal_selesai — H+1 sudah terkunci.
        // Riwayat & unduhan tetap jalan walau $bisaAbsen false.
        $bisaAbsen = $kegiatan
            ? now()->toDateString() <= $kegiatan->tanggal_selesai->toDateString()
            : false;

        $jadwal_harian = [];

        if ($kegiatan) {
            $periode = CarbonPeriod::create($kegiatan->tanggal_mulai, $kegiatan->tanggal_selesai);
            $hariKe = 1;
            foreach ($periode as $tanggal) {
                $jadwal_harian[$tanggal->toDateString()] = "Hari ke-{$hariKe} (" . $tanggal->translatedFormat('d M Y') . ")";
                $hariKe++;
            }
        }

        return view('admin.absensi.scan', compact('kegiatan', 'jadwal_harian', 'daftarKegiatan', 'bisaAbsen'));
    }

    public function riwayat(Request $request, Kegiatan $kegiatan)
    {
        $tanggal = $request->query('tanggal', now()->toDateString());

        $data = Absensi::with('pendaftaran')
            ->whereHas('pendaftaran', fn ($q) => $q->where('kegiatan_id', $kegiatan->id))
            ->where('tanggal_hadir', $tanggal)
            ->orderByDesc('waktu_scan')
            ->get()
            ->map(fn ($a) => [
                'nama'       => $a->pendaftaran->nama_gelar,
                'unit_kerja' => $a->pendaftaran->unit_kerja,
                'jam'        => $a->waktu_scan->format('H:i'),
            ]);

        return response()->json($data);
    }

    public function scan(Request $request)
    {
        $validated = $request->validate([
            'kode'    => 'required|string',
            'tanggal' => 'required|date',
        ]);

        $pendaftaran = Pendaftaran::with(['kegiatan', 'peserta'])
            ->where('token_kehadiran', $validated['kode'])
            ->first();

        if (! $pendaftaran) {
            return response()->json([
                'status' => 'gagal',
                'pesan'  => 'QR tidak dikenali atau tidak valid.'
            ]);
        }

        $kegiatan = $pendaftaran->kegiatan;
        $tanggalDipilih = $validated['tanggal'];

        if ($tanggalDipilih < $kegiatan->tanggal_mulai->toDateString() || $tanggalDipilih > $kegiatan->tanggal_selesai->toDateString()) {
            return response()->json([
                'status' => 'gagal',
                'pesan'  => 'Tanggal terpilih di luar rentang kegiatan.'
            ]);
        }

        // Kunci keras di sisi server juga: begitu H+1 dari tanggal_selesai, scan ditolak,
        // biar tidak bisa diakali lewat request langsung ke endpoint ini.
        if (now()->toDateString() > $kegiatan->tanggal_selesai->toDateString()) {
            return response()->json([
                'status' => 'gagal',
                'pesan'  => 'Absensi untuk kegiatan ini sudah ditutup.'
            ]);
        }

        $sudahAbsen = Absensi::where('pendaftaran_id', $pendaftaran->id)
            ->where('tanggal_hadir', $tanggalDipilih)
            ->exists();

        if ($sudahAbsen) {
            return response()->json([
                'status' => 'duplikat',
                'nama'   => $pendaftaran->nama_gelar,
                'pesan'  => 'Peserta sudah diabsen untuk tanggal tersebut.'
            ]);
        }

        Absensi::create([
            'pendaftaran_id' => $pendaftaran->id,
            'tanggal_hadir'  => $tanggalDipilih,
            'waktu_scan'     => now(),
        ]);

        $sudahLengkap = $pendaftaran->absensiLengkap();
        if ($sudahLengkap && $pendaftaran->status !== 'sertifikat') {
            $pendaftaran->update(['status' => 'sertifikat']);
        }

        return response()->json([
            'status'     => 'berhasil',
            'nama'       => $pendaftaran->nama_gelar,
            'unit_kerja' => $pendaftaran->unit_kerja,
            'kegiatan'   => $kegiatan->nama,
            'jam'        => now()->format('H:i'),
        ]);
    }

    /**
     * Unduh rekap kehadiran satu kegiatan sebagai CSV (bisa langsung dibuka di Excel).
     * Kalau query ?tanggal=YYYY-MM-DD disertakan, hasilnya difilter untuk tanggal itu saja;
     * kalau tidak, semua hari kegiatan ikut terunduh.
     */
    public function exportExcel(Request $request, Kegiatan $kegiatan)
    {
        $tanggal = $request->query('tanggal');

        $query = Absensi::with('pendaftaran')
            ->whereHas('pendaftaran', fn ($q) => $q->where('kegiatan_id', $kegiatan->id));

        if ($tanggal) {
            $query->where('tanggal_hadir', $tanggal);
        }

        $data = $query->orderBy('tanggal_hadir')->orderBy('waktu_scan')->get();

        $namaFile = 'absensi-' . Str::slug($kegiatan->nama) . ($tanggal ? '-' . $tanggal : '') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $namaFile . '"',
        ];

        return response()->streamDownload(function () use ($data) {
            $handle = fopen('php://output', 'w');

            // BOM biar karakter (é, spasi khusus, dll) tampil benar saat dibuka di Excel
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['No', 'Nama', 'NIP', 'Unit Kerja', 'Jabatan', 'Tanggal Hadir', 'Jam Absen']);

            foreach ($data as $i => $a) {
                fputcsv($handle, [
                    $i + 1,
                    $a->pendaftaran->nama_gelar,
                    $a->pendaftaran->nip,
                    $a->pendaftaran->unit_kerja,
                    $a->pendaftaran->jabatan,
                    optional($a->tanggal_hadir)->translatedFormat('d F Y'),
                    optional($a->waktu_scan)->format('H:i'),
                ]);
            }

            fclose($handle);
        }, $namaFile, $headers);
    }
}