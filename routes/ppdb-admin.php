<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PPDB\DashboardController;
use App\Http\Controllers\PPDB\PengaturanController;
use App\Http\Controllers\PPDB\PendaftarController;

Route::middleware([
        'auth',
        'role:tu'
    ])
    ->prefix('tu/ppdb')
    ->name('tu.ppdb.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/dashboard',
            [DashboardController::class, 'index']
        )->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | PENGATURAN
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'pengaturan',
            PengaturanController::class
        );

        /*
        |--------------------------------------------------------------------------
        | PENDAFTAR
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/pendaftar',
            [PendaftarController::class, 'index']
        )->name('pendaftar.index');

        Route::get(
            '/pendaftar/{pendaftar}',
            [PendaftarController::class, 'show']
        )->name('pendaftar.show');

        /*
        |--------------------------------------------------------------------------
        | VERIFIKASI
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/pendaftar/{pendaftar}/terima',
            [PendaftarController::class, 'terima']
        )->name('pendaftar.terima');

        Route::post(
            '/pendaftar/{pendaftar}/tolak',
            [PendaftarController::class, 'tolak']
        )->name('pendaftar.tolak');

        /*
        |--------------------------------------------------------------------------
        | FILTER STATUS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/diterima',
            [PendaftarController::class, 'diterima']
        )->name('diterima.index');

        Route::get(
            '/ditolak',
            [PendaftarController::class, 'ditolak']
        )->name('ditolak.index');

        Route::get(
            '/verifikasi',
            [PendaftarController::class, 'verifikasi']
        )->name('verifikasi.index');

    });