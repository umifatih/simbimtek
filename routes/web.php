<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\CekStatusController;
use App\Http\Controllers\SertifikatController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AdminAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes — Publik
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => view('beranda'))->name('beranda');
Route::get('/cara-kerja', fn () => view('cara-kerja'))->name('cara-kerja');

Route::get('/pendaftaran', fn () => view('daftar.index'))->name('pendaftaran.create');
Route::post('/pendaftaran', [PendaftaranController::class, 'store'])->name('pendaftaran.store');
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
| sungguhan (bukan placeholder) sudah siap, supaya tidak bisa diakses publik.
*/

Route::prefix('admin')->group(function () {
    Route::get('/dashboard', fn () => view('admin.dashboard'))->name('admin.dashboard');

    Route::get('/kegiatan', fn () => view('admin.kegiatan.index'))->name('admin.kegiatan.index');

    Route::get('/peserta', fn () => view('admin.peserta.index'))->name('admin.peserta.index');
    Route::get('/peserta/verifikasi', fn () => view('admin.peserta.verifikasi'))->name('admin.peserta.verifikasi');

    Route::get('/absensi', fn () => view('admin.absensi.scan'))->name('absensi.scan.page');
    Route::post('/absensi/scan', [AbsensiController::class, 'scan'])->name('absensi.scan');

    Route::get('/cetak', fn () => view('admin.cetak.index'))->name('admin.cetak.index');
    Route::get('/cetak/daftar-peserta', fn () => view('admin.cetak.daftar-peserta'))->name('admin.cetak.daftar-peserta');
    Route::get('/cetak/daftar-hadir', fn () => view('admin.cetak.daftar-hadir'))->name('admin.cetak.daftar-hadir');
    Route::get('/cetak/konsumsi-atk', fn () => view('admin.cetak.konsumsi-atk'))->name('admin.cetak.konsumsi-atk');
});