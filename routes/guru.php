<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Guru\DashboardGuruController;
use App\Http\Controllers\Guru\PengajuanJadwalController;
use App\Http\Controllers\Guru\JadwalSayaController;
use App\Http\Controllers\Guru\StatusValidasiController;
use App\Http\Controllers\Guru\DokumenGuruController;
use App\Http\Controllers\Guru\PengajuanSuratController;
use App\Http\Controllers\Guru\NomorSuratGuruController;
use App\Http\Controllers\Guru\WaliKelasController;
use App\Http\Controllers\Guru\AbsensiController;
use App\Http\Controllers\Guru\NilaiController;
use App\Http\Controllers\Guru\PerkembanganSiswaController;
use App\Http\Controllers\Guru\KenaikanKelasGuruController;
use App\Http\Controllers\Guru\RaportController;
use App\Http\Controllers\Guru\DashboardWaliKelasController;
use App\Http\Controllers\Guru\ProfileController;

Route::prefix('guru')
    ->name('guru.')
    ->middleware(['auth', 'role:guru'])
    ->group(function () {

        Route::get('/dashboard', [DashboardGuruController::class, 'index'])
            ->name('dashboard');
        
        Route::get(
            '/dashboard-walikelas',
            [DashboardWaliKelasController::class, 'index']
        )->name('dashboard-walikelas');

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
            '/kelas-saya',
            [WaliKelasController::class, 'kelasSaya']
        )->name('kelas-saya');

        Route::get(
            '/raport/{siswa}',
            [RaportController::class, 'show']
        )->name('raport.show');

        Route::get(
            '/absensi',
            [AbsensiController::class, 'index']
        )->name('absensi.index');

        Route::post(
            '/absensi',
            [AbsensiController::class, 'store']
        )->name('absensi.store');

        Route::get(
            '/absensi/{absensi}/edit',
            [AbsensiController::class, 'edit']
        )->name('absensi.edit');

        Route::put(
            '/absensi/{absensi}',
            [AbsensiController::class, 'update']
        )->name('absensi.update');

        Route::get(
            '/rekap-absensi',
            [AbsensiController::class, 'rekap']
        )->name('absensi.rekap');
        Route::get(
            '/rekap-absensi/export',
            [AbsensiController::class, 'exportPdf']
        )->name('absensi.export');

        Route::get(
            '/nilai',
            [NilaiController::class, 'index']
        )->name('nilai.index');

        Route::post(
            '/nilai',
            [NilaiController::class, 'store']
        )->name('nilai.store');

        Route::get(
            '/perkembangan-siswa',
            [PerkembanganSiswaController::class, 'index']
        )->name('perkembangan.index');

        Route::post(
            '/perkembangan-siswa',
            [PerkembanganSiswaController::class, 'store']
        )->name('perkembangan.store');

        Route::get(
            '/siswa/{siswa}',
            [WaliKelasController::class, 'show']
        )->name('kelas-saya.show');

        Route::get(
            '/kenaikan-kelas',
            [KenaikanKelasGuruController::class, 'index']
        )->name('kenaikan.index');

        Route::post(
            '/kenaikan-kelas',
            [KenaikanKelasGuruController::class, 'store']
        )->name('kenaikan.store');

        Route::get(
            '/status-validasi',
            [StatusValidasiController::class, 'index']
        )->name('status-validasi.index');

        Route::get('/dokumen/surat', [DokumenGuruController::class, 'index'])
            ->name('dokumen.surat');

        Route::resource('pengajuan-surat',App\Http\Controllers\Guru\PengajuanSuratController::class);

        Route::get('/nomor-surat',[NomorSuratGuruController::class, 'index'])
            ->name('nomor-surat.index');

        Route::post('/nomor-surat/generate',[NomorSuratGuruController::class, 'generate'])
            ->name('nomor-surat.generate');

        Route::get('/profile', [ProfileController::class, 'index'])
            ->name('profile');
    });