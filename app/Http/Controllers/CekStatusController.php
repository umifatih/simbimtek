<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CekStatusController extends Controller
{
    public function index(Request $request)
    {
        $nomor = $request->query('nomor_pendaftaran');
        $dicari = filled($nomor);

        $pendaftaran = null;

        if ($dicari) {
            // TODO: ganti dengan query asli begitu model & tabel siap, contoh:
            // $pendaftaran = Pendaftaran::with('kegiatan')
            //     ->where('nomor_pendaftaran', $nomor)
            //     ->first();

            // Placeholder sementara supaya alur bisa dites: anggap ketemu
            // untuk nomor apa pun yang formatnya benar (diawali "BT-").
            if (str_starts_with(strtoupper($nomor), 'BT-')) {
                $pendaftaran = (object) [
                    'id' => 1,
                    'nomor_pendaftaran' => strtoupper($nomor),
                    'token_kehadiran' => 'DEMO7f3a9c1e2b8d4f6a0c5e9b1d3f7a2c4e', // dummy, tetap sama tiap dicari untuk testing
                    'nama_gelar' => 'Siti Aminah, S.Pd.',
                    'unit_kerja' => 'SD NEGERI 1 MANDIRAJA KULON',
                    'jabatan' => 'Bendahara BOSP',
                    'status' => 'verifikasi', // coba ganti: daftar | verifikasi | sppd | sertifikat
                    'kegiatan' => (object) [
                        'nama' => 'Bimtek Pengelolaan Keuangan Desa',
                        'tanggal' => '14–16 Sep 2026',
                    ],
                ];
            }
        }

        return view('status.index', compact('pendaftaran', 'dicari'));
    }
}