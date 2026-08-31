<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PendaftaranController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kegiatan_id'          => 'required|string',
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

        // TODO: ganti dengan Pendaftaran::create([...$validated, 'token_kehadiran' => Str::random(32)])
        // begitu model & migration siap. token_kehadiran WAJIB unique di database.
        $pendaftaran = (object) array_merge($validated, [
            'id' => 1,
            'nomor_pendaftaran' => 'BT-2026-' . str_pad(rand(1, 999), 4, '0', STR_PAD_LEFT),
            'token_kehadiran' => Str::random(32), // isi QR — acak, bukan nomor pendaftaran
            'kegiatan' => (object) [
                'nama' => 'Bimtek Pengelolaan Keuangan Desa',
                'tanggal' => '14–16 Sep 2026',
            ],
        ]);

        return view('daftar.sukses', compact('pendaftaran'));
    }

    public function unduh($pendaftaran, $jenis)
    {
        // sementara — nanti diganti generate PDF dari template pakai fpdi
        return "Placeholder unduh dokumen: {$jenis} untuk pendaftaran #{$pendaftaran}";
    }
}