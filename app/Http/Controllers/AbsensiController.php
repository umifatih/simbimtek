<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function scan(Request $request)
    {
        $kode = trim($request->input('kode'));

        // TODO: ganti dengan logika asli, contoh:
        // $pendaftaran = Pendaftaran::where('token_kehadiran', $kode)->first();
        // if (!$pendaftaran) return response()->json(['status' => 'gagal', 'pesan' => 'QR tidak dikenali']);
        // if ($pendaftaran->hadir_pada) return response()->json(['status' => 'duplikat', 'nama' => $pendaftaran->nama_gelar, 'pesan' => 'Sudah absen pukul ' . $pendaftaran->hadir_pada->format('H:i')]);
        // $pendaftaran->update(['hadir_pada' => now()]);

        // dummy: token acak dari Str::random(32) selalu 32 karakter — cek panjangnya saja untuk demo
        if (strlen($kode) !== 32) {
            return response()->json([
                'status' => 'gagal',
                'pesan' => 'QR tidak dikenali',
            ]);
        }

        return response()->json([
            'status' => 'berhasil',
            'nama' => 'Siti Aminah, S.Pd.',
            'unit_kerja' => 'SD NEGERI 1 MANDIRAJA KULON',
            'kegiatan' => 'Bimtek Pengelolaan Keuangan Desa',
        ]);
    }
}