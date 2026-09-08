<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\SiteSetting;

class BerandaController extends Controller
{
    public function index()
    {
        $kegiatanList = Kegiatan::withCount(['pendaftaran as kuota_terisi'])
            ->get()
            ->sortBy(fn ($k) => ($k->status === 'selesai' ? '1' : '0') . '-' . $k->tanggal_mulai->format('Ymd'))
            ->values();

        $setting = SiteSetting::current();

        return view('peserta.beranda', compact('kegiatanList', 'setting'));
    }
}