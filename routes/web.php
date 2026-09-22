<?php

use App\Http\Controllers\PendaftaranController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/laporan', [PendaftaranController::class, 'laporan'])->name('laporan');

Route::get('/register', [PendaftaranController::class, 'index'])->name('register');
Route::get('/pendaftaran/create', [PendaftaranController::class, 'create'])->name('pendaftaran.create');
Route::post('/register', [PendaftaranController::class, 'store'])->name('register.store');
Route::get('/register/success', [PendaftaranController::class, 'success'])->name('register.success');
Route::get('/pendaftaran/{pendaftaran}/edit', [PendaftaranController::class, 'edit'])->name('pendaftaran.edit');
Route::put('/pendaftaran/{pendaftaran}', [PendaftaranController::class, 'update'])->name('pendaftaran.update');
Route::delete('/pendaftaran/{pendaftaran}', [PendaftaranController::class, 'destroy'])->name('pendaftaran.destroy');
