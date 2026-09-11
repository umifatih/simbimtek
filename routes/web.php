<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\CekStatusController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminPesertaController;
use App\Http\Controllers\Admin\AdminKegiatanController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\DataMasterController;
use App\Http\Controllers\Admin\AdminPengaturanController; // [BARU]
use App\Http\Controllers\Admin\AdminSertifikatController; // [BARU]

/*
|--------------------------------------------------------------------------
| Web Routes — Publik
|--------------------------------------------------------------------------
*/

Route::get('/', [BerandaController::class, 'index'])->name('beranda');

Route::get('/cara-kerja', fn () => view('peserta.cara-kerja'))->name('cara-kerja');

Route::get('/pendaftaran', [PendaftaranController::class, 'create'])->name('pendaftaran.create');
Route::post('/pendaftaran', [PendaftaranController::class, 'store'])->name('pendaftaran.store');
Route::get('/pendaftaran/cari-nip/{nip}', [PendaftaranController::class, 'cariNip'])->name('pendaftaran.cari-nip');

Route::get('/pendaftaran/cari-sekolah', [PendaftaranController::class, 'cariSekolah'])->name('pendaftaran.cari-sekolah');
Route::get('/pendaftaran/cari-peserta', [PendaftaranController::class, 'cariPeserta'])->name('pendaftaran.cari-peserta');

Route::get('/pendaftaran/{pendaftaran}/unduh/{jenis}', [PendaftaranController::class, 'unduh'])
    ->name('pendaftaran.unduh');

Route::get('/cek-status', [CekStatusController::class, 'index'])->name('cek-status');

/*
|--------------------------------------------------------------------------
| Admin — Auth
|--------------------------------------------------------------------------
*/

Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

/*
|--------------------------------------------------------------------------
| Admin — Panel
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/data-master', [DataMasterController::class, 'index'])->name('admin.data-master.index');
    Route::post('/data-master/import', [DataMasterController::class, 'import'])->name('admin.data-master.import');
    Route::post('/data-master', [DataMasterController::class, 'store'])->name('admin.data-master.store');
    Route::put('/data-master/{dataMaster}', [DataMasterController::class, 'update'])->name('admin.data-master.update');
    Route::delete('/data-master/{dataMaster}', [DataMasterController::class, 'destroy'])->name('admin.data-master.destroy');
    Route::get('/data-master/template', [DataMasterController::class, 'unduhTemplate'])->name('admin.data-master.template');
    Route::post('/data-master/rapikan', [DataMasterController::class, 'rapikan'])->name('admin.data-master.rapikan');

    Route::get('/kegiatan', [AdminKegiatanController::class, 'index'])->name('admin.kegiatan.index');
    Route::post('/kegiatan', [AdminKegiatanController::class, 'store'])->name('admin.kegiatan.store');
    Route::put('/kegiatan/{kegiatan}', [AdminKegiatanController::class, 'update'])->name('admin.kegiatan.update');
    Route::delete('/kegiatan/{kegiatan}', [AdminKegiatanController::class, 'destroy'])->name('admin.kegiatan.destroy');

    Route::get('/peserta', [AdminPesertaController::class, 'index'])->name('admin.peserta.index');

    Route::get('/absensi', [AbsensiController::class, 'indexScan'])->name('admin.absensi.scan');
    Route::get('/absensi/{kegiatan}/riwayat', [AbsensiController::class, 'riwayat'])->name('absensi.riwayat');
    Route::post('/absensi/scan', [AbsensiController::class, 'scan'])->name('absensi.scan');

    Route::get('/cetak', fn () => view('admin.cetak.index'))->name('admin.cetak.index');
    Route::get('/cetak/daftar-peserta', fn () => view('admin.cetak.daftar-peserta'))->name('admin.cetak.daftar-peserta');
    Route::get('/cetak/daftar-hadir', fn () => view('admin.cetak.daftar-hadir'))->name('admin.cetak.daftar-hadir');
    Route::get('/cetak/konsumsi-atk', fn () => view('admin.cetak.konsumsi-atk'))->name('admin.cetak.konsumsi-atk');

    // [BARU] Sertifikat — daftar peserta siap cetak per kegiatan
    Route::get('/sertifikat', [AdminSertifikatController::class, 'index'])->name('admin.sertifikat.index');
    Route::patch('sertifikat/ketua', [AdminSertifikatController::class, 'updateKetua'])
    ->name('admin.sertifikat.ketua.update');
    Route::post('/sertifikat/materi', [AdminSertifikatController::class, 'storeMateri'])->name('admin.sertifikat.materi.store');
Route::delete('/sertifikat/materi/{materi}', [AdminSertifikatController::class, 'destroyMateri'])->name('admin.sertifikat.materi.destroy');
    
    // [BARU] Pengaturan beranda / identitas situs
    Route::get('/pengaturan', [AdminPengaturanController::class, 'edit'])->name('admin.pengaturan.edit');
    Route::post('/pengaturan', [AdminPengaturanController::class, 'update'])->name('admin.pengaturan.update');

    Route::get('/debug-setting', function () {
        return response()->json(\App\Models\SiteSetting::first());
    });

    Route::get('/debug-reset-logo', function () {
        $s = \App\Models\SiteSetting::first();
        $s->logo_path = null;
        $s->save();
        return 'Logo berhasil direset ke null';
    });
});