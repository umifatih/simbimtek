<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SertifikatController extends Controller
{
    public function index(Request $request)
    {
        $nomor = $request->query('nomor_pendaftaran');
        $dicari = filled($nomor);

        $pendaftaran = null;

        if ($dicari) {
            // TODO: ganti dengan query asli, contoh:
            // $pendaftaran = Pendaftaran::with('kegiatan')->where('nomor_pendaftaran', $nomor)->first();

            if (str_starts_with(strtoupper($nomor), 'BT-')) {
                $pendaftaran = (object) [
                    'id' => 1,
                    'nomor_pendaftaran' => strtoupper($nomor),
                    'nama_gelar' => 'Siti Aminah, S.Pd.',
                    'status' => 'sertifikat', // ganti ke 'sppd' untuk lihat state "belum terbit"
                    'kegiatan' => (object) [
                        'nama' => 'Bimtek Pengelolaan Keuangan Desa',
                        'tanggal' => '14–16 Sep 2026',
                    ],
                ];
            }
        }

        return view('sertifikat.index', compact('pendaftaran', 'dicari'));
    }
}