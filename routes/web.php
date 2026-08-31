<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\CekStatusController;
use App\Http\Controllers\SertifikatController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\Admin\AdminPesertaController;
use App\Http\Controllers\Admin\AdminKegiatanController;

/*
|--------------------------------------------------------------------------
| Web Routes — Publik
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => view('beranda'))->name('beranda');
Route::get('/cara-kerja', fn () => view('cara-kerja'))->name('cara-kerja');

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
| TODO: bungkus grup ini dengan middleware('auth:admin') begitu login admin
| sungguhan (bukan placeholder) sudah siap.
*/

Route::prefix('admin')->group(function () {
    Route::get('/dashboard', fn () => view('admin.dashboard'))->name('admin.dashboard');

    Route::get('/kegiatan', [AdminKegiatanController::class, 'index'])->name('admin.kegiatan.index');
    Route::post('/kegiatan', [AdminKegiatanController::class, 'store'])->name('admin.kegiatan.store');
    Route::put('/kegiatan/{kegiatan}', [AdminKegiatanController::class, 'update'])->name('admin.kegiatan.update');
    Route::delete('/kegiatan/{kegiatan}', [AdminKegiatanController::class, 'destroy'])->name('admin.kegiatan.destroy');

    Route::get('/peserta', [AdminPesertaController::class, 'index'])->name('admin.peserta.index');
    Route::get('/peserta/verifikasi', [AdminPesertaController::class, 'verifikasi'])->name('admin.peserta.verifikasi');
    Route::post('/peserta/{pendaftaran}/setujui', [AdminPesertaController::class, 'setujui'])->name('admin.peserta.setujui');
    Route::post('/peserta/{pendaftaran}/tolak', [AdminPesertaController::class, 'tolak'])->name('admin.peserta.tolak');

    Route::get('/absensi', fn () => view('admin.absensi.scan'))->name('absensi.scan.page');
    Route::post('/absensi/scan', [AbsensiController::class, 'scan'])->name('absensi.scan');

    Route::get('/cetak', fn () => view('admin.cetak.index'))->name('admin.cetak.index');
    Route::get('/cetak/daftar-peserta', fn () => view('admin.cetak.daftar-peserta'))->name('admin.cetak.daftar-peserta');
    Route::get('/cetak/daftar-hadir', fn () => view('admin.cetak.daftar-hadir'))->name('admin.cetak.daftar-hadir');
    Route::get('/cetak/konsumsi-atk', fn () => view('admin.cetak.konsumsi-atk'))->name('admin.cetak.konsumsi-atk');
});