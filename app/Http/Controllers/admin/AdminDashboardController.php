<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Kegiatan;
use App\Models\Pendaftaran;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalPeserta = Pendaftaran::count();
        $menungguVerifikasi = Pendaftaran::where('status', 'daftar')->count();
        $kegiatanAktif = Kegiatan::where('status', 'dibuka')->count();

        // Jumlah peserta yang sudah absen HARI INI (lintas semua kegiatan yang berjalan)
        $hadirHariIni = Absensi::whereDate('tanggal_hadir', now())->count();

        $kegiatanBerjalan = Kegiatan::withCount(['pendaftaran as jumlah_peserta'])
            ->where('status', 'dibuka')
            ->orderBy('tanggal_mulai')
            ->take(3)
            ->get();

        $antreanVerifikasi = Pendaftaran::with('peserta')
            ->where('status', 'daftar')
            ->latest()
            ->take(3)
            ->get();

        return view('admin.dashboard', compact(
            'totalPeserta',
            'menungguVerifikasi',
            'kegiatanAktif',
            'hadirHariIni',
            'kegiatanBerjalan',
            'antreanVerifikasi',
        ));
    }
}