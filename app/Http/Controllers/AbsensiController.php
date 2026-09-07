<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Kegiatan;
use App\Models\Pendaftaran;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    /**
     * Menampilkan halaman scan QR untuk sebuah kegiatan.
     * Meng-generate rentang tanggal (jadwal harian) secara dinamis
     * untuk dikirim ke dropdown di halaman view.
     */
    public function indexScan($id_kegiatan)
    {
        $kegiatan = Kegiatan::findOrFail($id_kegiatan);
        
        $jadwal_harian = [];
        
        // Memecah rentang tanggal kegiatan menjadi list per hari menggunakan CarbonPeriod
        if ($kegiatan->tanggal_mulai && $kegiatan->tanggal_selesai) {
            $periode = CarbonPeriod::create($kegiatan->tanggal_mulai, $kegiatan->tanggal_selesai);
            $hariKe = 1;
            foreach ($periode as $tanggal) {
                // Contoh Hasil: "Hari ke-1 (12 Sep 2026)"
                $jadwal_harian[$tanggal->toDateString()] = "Hari ke-{$hariKe} (" . $tanggal->translatedFormat('d M Y') . ")";
                $hariKe++;
            }
        } else {
            // Fallback jika tanggal kosong
            $jadwal_harian[now()->toDateString()] = 'Hari Ini (' . now()->translatedFormat('d M Y') . ')';
        }

        return view('admin.absensi.scan', compact('kegiatan', 'jadwal_harian'));
    }

    /**
     * Dipanggil via AJAX saat QR peserta dipindai panitia.
     * Mencatat kehadiran untuk tanggal yang DIPILIH admin di layar.
     */
    public function scan(Request $request)
    {
        // 1. Validasi request dari Javascript (sekarang menerima 'kode' dan 'tanggal')
        $validated = $request->validate([
            'kode'    => 'required|string',
            'tanggal' => 'required|date',
        ]);

        $pendaftaran = Pendaftaran::with(['kegiatan', 'peserta'])
            ->where('token_kehadiran', $validated['kode'])
            ->first();

        // 2. Jika QR tidak terdaftar
        if (! $pendaftaran) {
            return response()->json([
                'status' => 'gagal',
                'pesan'  => 'QR tidak dikenali atau tidak valid.'
            ]);
        }

        $kegiatan = $pendaftaran->kegiatan;
        $tanggalDipilih = $validated['tanggal'];

        // 3. Pastikan tanggal yang dipilih admin masih masuk rentang kegiatan
        if ($tanggalDipilih < $kegiatan->tanggal_mulai->toDateString() || $tanggalDipilih > $kegiatan->tanggal_selesai->toDateString()) {
            return response()->json([
                'status' => 'gagal',
                'pesan'  => 'Tanggal terpilih di luar rentang kegiatan.'
            ]);
        }

        // 4. Cegah Duplikat: Cek apakah peserta sudah diabsen untuk HARI INI
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

        // 5. Catat Absensi ke Database (Karena sudah lolos semua validasi)
        $absensi = Absensi::create([
            'pendaftaran_id' => $pendaftaran->id,
            'tanggal_hadir'  => $tanggalDipilih,
            'waktu_scan'     => now(), // Tetap simpan jam saat dia scan (real-time)
        ]);

        // 6. Cek Kelengkapan (Otomatis naikkan status jadi 'sertifikat' jika sudah lengkap)
        $sudahLengkap = $pendaftaran->absensiLengkap();
        if ($sudahLengkap && $pendaftaran->status !== 'sertifikat') {
            $pendaftaran->update(['status' => 'sertifikat']);
        }

        // 7. Kembalikan respons sukses ke Javascript
        return response()->json([
            'status'     => 'berhasil',
            'nama'       => $pendaftaran->nama_gelar,
            'unit_kerja' => $pendaftaran->unit_kerja,
            'kegiatan'   => $kegiatan->nama,
        ]);
    }
}