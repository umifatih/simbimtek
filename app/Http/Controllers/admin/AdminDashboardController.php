<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Kegiatan;
use App\Models\Pendaftaran;
use App\Models\DataMaster;
use App\Models\SiteSetting;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalPeserta = Pendaftaran::count();
        $menungguVerifikasi = Pendaftaran::where('status', 'daftar')->count();
        $diterima = Pendaftaran::where('status', 'diterima')->count();
        $ditolak = Pendaftaran::where('status', 'ditolak')->count();

        $kegiatanAktif = Kegiatan::where('status', 'dibuka')->count();

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

        // Data Master
        $totalDataMaster = DataMaster::count();
        $dataMasterUpdatedAt = optional(DataMaster::latest('updated_at')->first())
            ->updated_at?->diffForHumans();

        // Pengaturan situs
        $setting = SiteSetting::current();
        $pengaturanLogoAda = filled($setting->logo_path);
        $pengaturanNamaAda = filled($setting->nama_aplikasi);

        return view('admin.dashboard', compact(
            'totalPeserta',
            'menungguVerifikasi',
            'diterima',
            'ditolak',
            'kegiatanAktif',
            'hadirHariIni',
            'kegiatanBerjalan',
            'antreanVerifikasi',
            'totalDataMaster',
            'dataMasterUpdatedAt',
            'pengaturanLogoAda',
            'pengaturanNamaAda',
        ));
    }
}