<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Halaman depan
Route::get('/', function () {
    return view('welcome');
});

// Dashboard default Laravel (bisa dipakai sebagai general dashboard)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// ===============================================
// ROUTE KHUSUS ROLE (kepsek, tu, guru, murid, spmb)
// ===============================================

// 1. GURU
use App\Http\Controllers\Guru\DashboardController as GuruDashboard;
Route::middleware(['auth', 'role:guru'])->group(function () {
    Route::get('/guru/dashboard', [GuruDashboard::class, 'index'])->name('guru.dashboard');
});

// 3. TATA USAHA (TU)
use App\Http\Controllers\Tu\DashboardController as TuDashboard;
Route::middleware(['auth', 'role:tu'])->group(function () {
    Route::get('/tu/dashboard', [TuDashboard::class, 'index'])->name('tu.dashboard');
});

// 4. MURID / WALI MURID
use App\Http\Controllers\Murid\DashboardController as MuridDashboard;
Route::middleware(['auth', 'role:murid'])->group(function () {
    Route::get('/murid/dashboard', [MuridDashboard::class, 'index'])->name('murid.dashboard');
});

// 5. PANITIA SPMB
use App\Http\Controllers\Spmb\DashboardController as SpmbDashboard;
Route::middleware(['auth', 'role:spmb'])->group(function () {
    Route::get('/spmb/dashboard', [SpmbDashboard::class, 'index'])->name('spmb.dashboard');
});


// ===============================================
// ROUTE UNTUK PROFILE (Bawaaan Breeze)
// ===============================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route auth Kepsek
require __DIR__.'/kepsek.php';

// Route auth Breeze
require __DIR__.'/auth.php';
