<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Siswa\DashboardController;
use App\Http\Controllers\Siswa\NilaiController;
use App\Http\Controllers\Siswa\AbsensiController;
use App\Http\Controllers\Siswa\NomorSuratSiswaController;
use App\Http\Controllers\Siswa\DownloadDokumenController;

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

        Route::get(
            '/download-dokumen',
            function () {
                return 'Halaman Download Dokumen';
            }
        )->name('download-dokumen.index');

        Route::get(
        '/download-dokumen',
        [DownloadDokumenController::class, 'index']
    )->name('download-dokumen.index');

        Route::get(
            '/nomor-surat',
            function () {
                return 'Halaman Nomor Surat';
            }
        )->name('nomor-surat.index');

        Route::get(
            '/nomor-surat',
            [NomorSuratSiswaController::class, 'index']
        )->name('nomor-surat.index');

        Route::post(
            '/nomor-surat/generate',
            [NomorSuratSiswaController::class, 'generate']
        )->name('nomor-surat.generate');

    });