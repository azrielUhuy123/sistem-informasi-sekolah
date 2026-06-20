<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Siswa\DashboardController;
use App\Http\Controllers\Siswa\NilaiController;
use App\Http\Controllers\Siswa\AbsensiController;
use App\Http\Controllers\Siswa\NomorSuratSiswaController;
use App\Http\Controllers\Siswa\DownloadDokumenController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Siswa\JadwalController;
use App\Http\Controllers\Siswa\EkskulController;
use App\Http\Controllers\Elearning\ElearningKomentarController;
use App\Http\Controllers\Siswa\ElearningController;
use app\Http\Controllers\Elearning\ElearningKomentarReplyController;

Route::prefix('siswa')
    ->name('siswa.')
    ->middleware(['auth'])
    ->group(function () {

        Route::get(
            '/dashboard',
            [DashboardController::class, 'index']
        )->name('dashboard.index');

        Route::get(
            '/jadwal',
            [JadwalController::class, 'index']
        )->name('jadwal.index');

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
            [ProfileController::class, 'index']
        )->name('profile');

        Route::get(
            '/ekskul',
            [EkskulController::class, 'index']
        )->name('ekskul.index');

        Route::get(
            '/ekskul/{ekskul}',
            [EkskulController::class, 'show']
        )->name('ekskul.show');

        Route::get(
            '/ekskul/{ekskul}/absensi',
            [EkskulController::class, 'absensi']
        )->name('ekskul.absensi');

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

        Route::prefix('e-learning')
            ->name('e-learning.')
            ->group(function () {

                Route::get(
                    '/',
                    [ElearningController::class, 'index']
                )->name('index');

                Route::get(
                    '/mapel/{mapel}',
                    [ElearningController::class, 'mapel']
                )->name('mapel');

                Route::get(
                    '/{elearning}',
                    [ElearningController::class, 'show']
                )->name('show');

            });
            
            Route::post(
                '/e-learning/{elearning}/komentar',
                [\App\Http\Controllers\Elearning\ElearningKomentarController::class, 'store']
            )->name('e-learning.komentar.store');

            Route::post(
                '/e-learning-komentar/{komentar}/reply',
                [\App\Http\Controllers\Elearning\ElearningKomentarReplyController::class, 'store']
            )->name('e-learning.reply.store');

           

    });