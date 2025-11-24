<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Kepsek\DashboardController;
use App\Http\Controllers\Kepsek\MonitoringController;

// Semua route kepala sekolah
Route::middleware(['auth', 'role:kepsek'])
    ->prefix('kepsek')
    ->name('kepsek.')
    ->group(function () {

        // Dashboard Kepala Sekolah
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Monitoring Guru
        Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');
    });
