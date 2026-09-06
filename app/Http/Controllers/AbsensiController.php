<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AbsensiController extends Controller
{
    /**
     * Dipanggil saat QR peserta dipindai panitia di lokasi kegiatan.
     * Mencatat kehadiran untuk tanggal hari ini, dan otomatis menaikkan
     * status pendaftaran jadi 'sertifikat' begitu absensi lengkap
     * dari tanggal_mulai sampai tanggal_selesai kegiatan.
     */
    public function scan(Request $request)
    {
        $validated = $request->validate([
            'token_kehadiran' => 'required|string',
        ]);

        $pendaftaran = Pendaftaran::with('kegiatan')
            ->where('token_kehadiran', $validated['token_kehadiran'])
            ->first();

        if (! $pendaftaran) {
            throw ValidationException::withMessages([
                'token_kehadiran' => 'QR tidak dikenali atau tidak valid.',
            ]);
        }

        $kegiatan = $pendaftaran->kegiatan;
        $hariIni = now()->toDateString();

        abort_unless(
            $hariIni >= $kegiatan->tanggal_mulai->toDateString()
            && $hariIni <= $kegiatan->tanggal_selesai->toDateString(),
            422,
            'Hari ini di luar rentang tanggal kegiatan.'
        );

        $absensi = Absensi::firstOrCreate(
            [
                'pendaftaran_id' => $pendaftaran->id,
                'tanggal_hadir'  => $hariIni,
            ],
            [
                'waktu_scan' => now(),
            ]
        );

        $sudahLengkap = $pendaftaran->absensiLengkap();

        if ($sudahLengkap && $pendaftaran->status !== 'sertifikat') {
            $pendaftaran->update(['status' => 'sertifikat']);
        }

        $progres = $pendaftaran->progresAbsensi();

        return response()->json([
            'berhasil'       => true,
            'baru_dicatat'   => $absensi->wasRecentlyCreated,
            'nama'           => $pendaftaran->nama_gelar,
            'kegiatan'       => $kegiatan->nama,
            'tanggal_hadir'  => $hariIni,
            'progres'        => "{$progres['hadir']} dari {$progres['wajib']} hari",
            'sudah_lengkap'  => $sudahLengkap,
            'status'         => $pendaftaran->status,
        ]);
    }
}