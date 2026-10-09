<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GatewayController;
use App\Http\Controllers\NotificationController;

Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');
    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])
        ->name('notifications.readAll');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'read'])
        ->name('notifications.read');
});

/*
|--------------------------------------------------------------------------
| PUBLIC (GATEWAY)
|--------------------------------------------------------------------------
*/
Route::get('/{any?}', [GatewayController::class, 'index'])
    ->where(
        'any',
        '^(?!login|forgot-password|reset-password|dashboard|guru|tu|siswa|superadmin|ppdb).*$'
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
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post(
        '/profile/update',
        [ProfileController::class, 'updateInformasi']
    )->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| ROUTE FILE TERPISAH
|--------------------------------------------------------------------------
*/
require __DIR__.'/superadmin.php';
require __DIR__.'/admin.php';
require __DIR__.'/tu.php';
require __DIR__.'/guru.php';
require __DIR__.'/siswa.php';
require __DIR__.'/ppdb-public.php';
require __DIR__.'/ppdb-admin.php';
require __DIR__.'/auth.php';


