<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Guru\DashboardGuruController;
use App\Http\Controllers\Guru\PengajuanJadwalController;
use App\Http\Controllers\Guru\JadwalSayaController;
use App\Http\Controllers\Guru\StatusValidasiController;

Route::prefix('guru')
    ->name('guru.')
    ->middleware(['auth', 'role:guru'])
    ->group(function () {

        Route::get('/dashboard', [DashboardGuruController::class, 'index'])
            ->name('dashboard');

        Route::get(
            '/jadwal-saya',
            [JadwalSayaController::class, 'index']
        )->name('jadwal-saya.index');

        Route::post(
            '/jadwal-saya/pilih-slot',
            [JadwalSayaController::class, 'pilihSlot']
        )->name('jadwal-saya.pilih-slot');

        Route::delete(
            '/jadwal-saya/{id}/hapus',
            [JadwalSayaController::class, 'hapusSlot']
        )->name('jadwal-saya.hapus-slot');
        
        Route::post(
            '/jadwal-saya/submit',
            [JadwalSayaController::class, 'submitJadwal']
        )->name('jadwal-saya.submit');

        Route::resource(
            'pengajuan-jadwal',
            PengajuanJadwalController::class
        );

        Route::get(
            '/status-validasi',
            [StatusValidasiController::class, 'index']
        )->name('status-validasi.index');

    });