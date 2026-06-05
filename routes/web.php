<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GatewayController;

/*
|--------------------------------------------------------------------------
| PUBLIC (GATEWAY)
|--------------------------------------------------------------------------
*/
Route::get('/{any?}', [GatewayController::class, 'index'])
    ->where(
        'any',
        '^(?!login|dashboard|guru|tu|siswa|superadmin).*$'
    )
    ->name('gateway');

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
require __DIR__.'/superadmin.php';
require __DIR__.'/tu.php';
require __DIR__.'/guru.php';
require __DIR__.'/siswa.php';
require __DIR__.'/auth.php';


