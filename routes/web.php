<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GatewayController;

/*
|--------------------------------------------------------------------------
| PUBLIC (GATEWAY)
|--------------------------------------------------------------------------
*/
Route::get('/', [GatewayController::class, 'index'])->name('gateway');
Route::get('/ppdb', [GatewayController::class, 'ppdb'])->name('ppdb');
Route::get('/informasi', [GatewayController::class, 'informasi'])->name('informasi');

/*
|--------------------------------------------------------------------------
| AUTH & DASHBOARD DEFAULT
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| ROLE BASED DASHBOARD
|--------------------------------------------------------------------------
*/


// MURID
use App\Http\Controllers\Murid\DashboardController as MuridDashboard;
Route::middleware(['auth', 'role:murid'])->group(function () {
    Route::get('/murid/dashboard', [MuridDashboard::class, 'index'])->name('murid.dashboard');
});

// SPMB
use App\Http\Controllers\Spmb\DashboardController as SpmbDashboard;
Route::middleware(['auth', 'role:spmb'])->group(function () {
    Route::get('/spmb/dashboard', [SpmbDashboard::class, 'index'])->name('spmb.dashboard');
});

/*
|--------------------------------------------------------------------------
| PROFILE (BREEZE)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| ROUTE FILE TERPISAH
|--------------------------------------------------------------------------
*/
require __DIR__.'/admin.php';
require __DIR__.'/superadmin.php';
require __DIR__.'/tu.php';
require __DIR__.'/guru.php';
require __DIR__.'/auth.php';


