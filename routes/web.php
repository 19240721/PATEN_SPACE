<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CamatController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PendaftaranController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::get('/dashboard', function () {
    return match (Auth::user()->role) {
        'masyarakat' => redirect()->route('masyarakat.dashboard'),
        'camat' => redirect()->route('camat.dashboard'),
        default => redirect()->route('petugas.dashboard'),
    };
})->middleware('auth')->name('dashboard');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::get('/login/captcha', [AuthController::class, 'captchaImage'])->name('login.captcha');
Route::get('/daftar', [AuthController::class, 'showRegister'])->name('account.register');
Route::post('/daftar', [AuthController::class, 'register'])->name('account.register.store');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'role:masyarakat'])->prefix('masyarakat')->name('masyarakat.')->group(function () {
    Route::get('/dashboard', [PendaftaranController::class, 'citizenDashboard'])->name('dashboard');
    Route::get('/permohonan', [PendaftaranController::class, 'citizenCreate'])->name('permohonan.create');
    Route::post('/permohonan', [PendaftaranController::class, 'store'])->name('permohonan.store');
    Route::get('/permohonan/{pendaftaran}/sukses', [PendaftaranController::class, 'citizenSuccess'])->name('permohonan.success');
    Route::get('/permohonan/{pendaftaran}/dokumen/{index}', [PendaftaranController::class, 'document'])->name('dokumen');
});

Route::get('/petugas/dashboard', [PendaftaranController::class, 'petugasDashboard'])->middleware(['auth', 'role:petugas'])->name('petugas.dashboard');

Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/permohonan', [PendaftaranController::class, 'permohonanMasuk'])->name('permohonan.index');
    Route::get('/permohonan/{pendaftaran}', [PendaftaranController::class, 'showPermohonan'])->name('permohonan.show');
    Route::patch('/permohonan/{pendaftaran}/status', [PendaftaranController::class, 'updateStatus'])->name('permohonan.status');
});

Route::middleware(['auth', 'role:petugas'])->group(function () {
    Route::get('/laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');
    Route::get('/laporan', [PendaftaranController::class, 'laporan'])->name('laporan');
    Route::get('/semua-data', [PendaftaranController::class, 'semuaData'])->name('pendaftaran.index');
    Route::get('/pelayanan/{jenisLayanan}', [PendaftaranController::class, 'layanan'])->name('pelayanan.index');
    Route::get('/pendaftaran/create', [PendaftaranController::class, 'create'])->name('pendaftaran.create');
    Route::post('/pendaftaran', [PendaftaranController::class, 'store'])->name('pendaftaran.store');
    Route::get('/pendaftaran/success', [PendaftaranController::class, 'success'])->name('pendaftaran.success');
    Route::get('/pendaftaran/{pendaftaran}/edit', [PendaftaranController::class, 'edit'])->name('pendaftaran.edit');
    Route::put('/pendaftaran/{pendaftaran}', [PendaftaranController::class, 'update'])->name('pendaftaran.update');
    Route::delete('/pendaftaran/{pendaftaran}', [PendaftaranController::class, 'destroy'])->name('pendaftaran.destroy');
    Route::get('/pendaftaran/{pendaftaran}/dokumen/{index}', [PendaftaranController::class, 'document'])->name('pendaftaran.document');
});

Route::get('/camat', fn () => redirect()->route('camat.dashboard'))->name('camat.home');
Route::middleware(['auth', 'role:camat'])->prefix('camat')->name('camat.')->group(function () {
    Route::get('/dashboard', [CamatController::class, 'dashboard'])->name('dashboard');
    Route::get('/monitoring', [CamatController::class, 'monitoring'])->name('monitoring');
    Route::get('/statistik', [CamatController::class, 'statistik'])->name('statistik');
    Route::get('/laporan', [CamatController::class, 'laporan'])->name('laporan');
    Route::get('/aktivitas', [CamatController::class, 'aktivitas'])->name('aktivitas');
    Route::get('/notifikasi', [CamatController::class, 'notifikasi'])->name('notifikasi');
    Route::get('/profil', [CamatController::class, 'profil'])->name('profil');
});
