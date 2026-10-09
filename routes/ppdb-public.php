<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PPDB\PublicController;

Route::prefix('ppdb')
    ->name('ppdb.')
    ->group(function () {

        Route::get(
            '/',
            [PublicController::class, 'informasi']
        )->name('informasi');

        Route::get(
            '/alur',
            [PublicController::class, 'alur']
        )->name('alur');

        Route::get(
            '/daftar',
            [PublicController::class, 'create']
        )->name('create');

        Route::post(
            '/daftar',
            [PublicController::class, 'store']
        )->name('store');

        Route::get(
            '/bukti/{no}',
            [PublicController::class, 'bukti']
        )->where('no', '.*')->name('bukti');

        Route::get(
            '/status',
            [PublicController::class, 'statusForm']
        )->name('status');

        Route::post(
            '/status',
            [PublicController::class, 'cekStatus']
        )->name('status.cek');

        Route::get(
            '/daftar-ulang',
            [PublicController::class, 'formDaftarUlang']
        )->name('daftar-ulang');

        Route::post(
            '/daftar-ulang',
            [PublicController::class, 'simpanDaftarUlang']
        )->name('daftar-ulang.simpan');

        Route::get(
            '/status/{pendaftar}',
            [PublicController::class, 'statusByLink']
        )->name('status.link')
        ->middleware('signed');

    });