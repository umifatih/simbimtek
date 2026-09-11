<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\CekStatusController;
use App\Http\Controllers\SertifikatController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminPesertaController;
use App\Http\Controllers\Admin\AdminKegiatanController;
use App\Http\Controllers\Admin\AdminDashboardController;

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
Route::get('/pendaftaran/{pendaftaran}/unduh/{jenis}', [PendaftaranController::class, 'unduh'])
    ->name('pendaftaran.unduh');

Route::get('/cek-status', [CekStatusController::class, 'index'])->name('cek-status');
Route::get('/sertifikat', [SertifikatController::class, 'index'])->name('sertifikat');

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

    Route::get('/kegiatan', [AdminKegiatanController::class, 'index'])->name('admin.kegiatan.index');
    Route::post('/kegiatan', [AdminKegiatanController::class, 'store'])->name('admin.kegiatan.store');
    Route::put('/kegiatan/{kegiatan}', [AdminKegiatanController::class, 'update'])->name('admin.kegiatan.update');
    Route::delete('/kegiatan/{kegiatan}', [AdminKegiatanController::class, 'destroy'])->name('admin.kegiatan.destroy');

    Route::get('/peserta', [AdminPesertaController::class, 'index'])->name('admin.peserta.index');

    // [PERBAIKAN] Penamaan route dirapikan agar tidak memicu MethodNotAllowed
    Route::get('/absensi', [AbsensiController::class, 'indexScan'])->name('admin.absensi.index'); // Untuk halaman
    Route::get('/absensi/{kegiatan}/riwayat', [AbsensiController::class, 'riwayat'])->name('admin.absensi.riwayat'); // Untuk fetch data
    Route::post('/absensi/scan', [AbsensiController::class, 'scan'])->name('admin.absensi.process'); // Untuk POST scanner
    Route::get('/absensi/{kegiatan}/export', [AbsensiController::class, 'exportExcel'])->name('admin.absensi.export');

    Route::get('/cetak', fn () => view('admin.cetak.index'))->name('admin.cetak.index');
    Route::get('/cetak/daftar-peserta', fn () => view('admin.cetak.daftar-peserta'))->name('admin.cetak.daftar-peserta');
    Route::get('/cetak/daftar-hadir', fn () => view('admin.cetak.daftar-hadir'))->name('admin.cetak.daftar-hadir');
    Route::get('/cetak/konsumsi-atk', fn () => view('admin.cetak.konsumsi-atk'))->name('admin.cetak.konsumsi-atk');
});