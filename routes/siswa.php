<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Siswa\DashboardController;
use App\Http\Controllers\Siswa\NilaiController;
use App\Http\Controllers\Siswa\AbsensiController;

Route::prefix('siswa')
    ->name('siswa.')
    ->middleware(['auth'])
    ->group(function () {

        Route::get(
            '/dashboard',
            [DashboardController::class, 'index']
        )->name('dashboard');

        Route::get(
            '/nilai',
            [NilaiController::class, 'index']
        )->name('nilai.index');

        Route::get(
            '/absensi',
            [AbsensiController::class, 'index']
        )->name('absensi.index');

        Route::get(
            '/profile',
            [\App\Http\Controllers\Siswa\ProfileController::class, 'index']
        )->name('profile');
    });