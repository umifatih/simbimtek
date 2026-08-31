<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function scan(Request $request)
    {
        $kode = trim($request->input('kode'));

        $pendaftaran = Pendaftaran::with(['peserta', 'kegiatan'])
            ->where('token_kehadiran', $kode)
            ->first();

        if (! $pendaftaran) {
            return response()->json([
                'status' => 'gagal',
                'pesan' => 'QR tidak dikenali',
            ]);
        }

        if ($pendaftaran->hadir_pada) {
            return response()->json([
                'status' => 'duplikat',
                'nama' => $pendaftaran->nama_gelar,
                'pesan' => 'Sudah absen pukul ' . $pendaftaran->hadir_pada->format('H:i'),
            ]);
        }

        $pendaftaran->update(['hadir_pada' => now()]);

        return response()->json([
            'status' => 'berhasil',
            'nama' => $pendaftaran->nama_gelar,
            'unit_kerja' => $pendaftaran->unit_kerja,
            'kegiatan' => $pendaftaran->kegiatan->nama,
        ]);
    }
}