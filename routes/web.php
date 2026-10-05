<?php

use App\Http\Controllers\CamatController;
use App\Http\Controllers\PendaftaranController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [PendaftaranController::class, 'dashboard'])
    ->middleware('auth')
    ->name('dashboard');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::get('/login/captcha', [AuthController::class, 'captchaImage'])->name('login.captcha');
Route::get('/daftar', [AuthController::class, 'showRegister'])->name('account.register');
Route::post('/daftar', [AuthController::class, 'register'])->name('account.register.store');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/laporan', [PendaftaranController::class, 'laporan'])->name('laporan');
Route::get('/semua-data', [PendaftaranController::class, 'semuaData'])->name('pendaftaran.index');
Route::get('/pelayanan/{jenisLayanan}', [PendaftaranController::class, 'layanan'])->name('pelayanan.index');

Route::get('/pendaftaran/create', [PendaftaranController::class, 'create'])->name('pendaftaran.create');
Route::post('/pendaftaran', [PendaftaranController::class, 'store'])->name('pendaftaran.store');
Route::get('/pendaftaran/success', [PendaftaranController::class, 'success'])->name('pendaftaran.success');
Route::get('/pendaftaran/{pendaftaran}/edit', [PendaftaranController::class, 'edit'])->name('pendaftaran.edit');
Route::put('/pendaftaran/{pendaftaran}', [PendaftaranController::class, 'update'])->name('pendaftaran.update');

/*
|--------------------------------------------------------------------------
| Routes Khusus Dashboard Camat (Ruang Monitoring & Pimpinan)
|--------------------------------------------------------------------------
*/
Route::prefix('camat')->name('camat.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('camat.dashboard');
    });
    Route::get('/dashboard', [CamatController::class, 'dashboard'])->name('dashboard');
    Route::get('/monitoring', [CamatController::class, 'monitoring'])->name('monitoring');
    Route::get('/statistik', [CamatController::class, 'statistik'])->name('statistik');
    Route::get('/laporan', [CamatController::class, 'laporan'])->name('laporan');
    Route::get('/aktivitas', [CamatController::class, 'aktivitas'])->name('aktivitas');
    Route::get('/notifikasi', [CamatController::class, 'notifikasi'])->name('notifikasi');
    Route::get('/profil', [CamatController::class, 'profil'])->name('profil');
});

Route::delete('/pendaftaran/{pendaftaran}', [PendaftaranController::class, 'destroy'])->name('pendaftaran.destroy');
Route::get('/laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');
