<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;

class BerandaController extends Controller
{
    public function index()
    {
        $kegiatanList = Kegiatan::withCount(['pendaftaran as kuota_terisi'])
            ->where('status', 'dibuka')
            ->orderBy('tanggal_mulai')
            ->take(3)
            ->get();

        return view('beranda', compact('kegiatanList'));
    }
}