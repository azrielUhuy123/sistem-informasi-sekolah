<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Guru\DashboardGuruBidangController;
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
use App\Http\Controllers\Guru\EkskulController;
use App\Http\Controllers\Guru\EkskulAnggotaController;
use App\Http\Controllers\Guru\Ekskul\AbsensiEkskulController;
use App\Http\Controllers\Guru\Ekskul\JurnalEkskulController;
use App\Http\Controllers\Guru\Ekskul\CatatanPembinaanController;
use App\Http\Controllers\Guru\Ekskul\JadwalEkskulController;
use App\Http\Controllers\Guru\Ekskul\StatusValidasiEkskulController;
use App\Http\Controllers\Guru\ElearningController;
use App\Http\Controllers\Elearning\ElearningKomentarController;
use App\Http\Controllers\Elearning\ElearningKomentarReplyController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

Route::prefix('guru')
    ->name('guru.')
    ->middleware(['auth', 'role:guru', 'guru.mode', 'menu.route'])
    ->group(function () {

        Route::get(
            '/dashboard-gurubidang',
            [DashboardGuruBidangController::class, 'index']
        )->middleware('feature:guru.academic')->name('dashboard-gurubidang');
        
        Route::get(
            '/switch-mode/{mode}',
            function ($mode) {

                $user = Auth::user();

                if ($mode === 'akademik') {
                    session([
                        'guru_mode' => 'akademik'
                    ]);
                    return back();
                }

                if ($mode === 'ekskul' && $user->hasAnyAssignmentMenu(
                    array_keys(config('assignment_menus.types.ekskul.menus', []))
                )) {
                    session(['guru_mode' => 'ekskul']);
                    return back();
                }

                abort(404);
            }
        )->name('switch-mode');

        Route::get(
            '/ekskul/dashboard',
            [EkskulController::class, 'dashboard']
        )->middleware('feature:guru.extracurricular')->name('ekskul.dashboard');

        Route::get(
            '/ekskul',
            [EkskulController::class, 'dashboard']
        )->middleware('feature:guru.extracurricular')->name('ekskul.index');

        /*
        |--------------------------------------------------------------------------
        | ANGGOTA EKSKUL
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/ekskul/anggota',
            [EkskulAnggotaController::class, 'index']
        )->middleware('feature:guru.extracurricular')->name('ekskul.anggota.index');

        Route::post(
            '/ekskul/anggota/store',
            [EkskulAnggotaController::class, 'store']
        )->middleware('feature:guru.extracurricular')->name('ekskul.anggota.store');

        Route::delete(
            '/ekskul/anggota/{id}',
            [EkskulAnggotaController::class, 'destroy']
        )->middleware('feature:guru.extracurricular')->name('ekskul.anggota.destroy');
        
        /*
        |--------------------------------------------------------------------------
        | DAFTAR ANGGOTA EKSKUL
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/ekskul/daftar-anggota',
            [EkskulAnggotaController::class, 'daftar']
        )->middleware('feature:guru.extracurricular')->name('ekskul.daftar-anggota');

        Route::delete(
            '/ekskul/daftar-anggota/{id}',
            [EkskulAnggotaController::class, 'destroy']
        )->middleware('feature:guru.extracurricular')->name('ekskul.daftar-anggota.destroy');

        Route::prefix('ekskul')
            ->name('ekskul.')
            ->middleware('feature:guru.extracurricular')
            ->group(function () {

                Route::get(
                    '/absensi',
                    [AbsensiEkskulController::class, 'index']
                )->name('absensi.index');

                Route::post(
                    '/absensi',
                    [AbsensiEkskulController::class, 'store']
                )->name('absensi.store');

                Route::get(
                    '/rekap-absensi',
                    [AbsensiEkskulController::class, 'rekap']
                )->name('absensi.rekap');

            });

        Route::get(
            '/ekskul/jurnal',
            [JurnalEkskulController::class, 'index']
        )->middleware('feature:guru.extracurricular')->name('ekskul.jurnal.index');

        Route::post(
            '/ekskul/jurnal',
            [JurnalEkskulController::class, 'store']
        )->middleware('feature:guru.extracurricular')->name('ekskul.jurnal.store');

        Route::delete(
            '/ekskul/jurnal/{id}',
            [JurnalEkskulController::class, 'destroy']
        )->middleware('feature:guru.extracurricular')->name('ekskul.jurnal.destroy');

        Route::get(
            '/dashboard-walikelas',
            [DashboardWaliKelasController::class, 'index']
        )->middleware('feature:guru.academic')->name('dashboard-walikelas');

        Route::get(
            '/jadwal-saya',
            [JadwalSayaController::class, 'index']
        )->middleware('feature:guru.academic')->name('jadwal-saya.index');

        Route::post(
            '/jadwal-saya/pilih-slot',
            [JadwalSayaController::class, 'pilihSlot']
        )->middleware('feature:guru.academic')->name('jadwal-saya.pilih-slot');

        Route::delete(
            '/jadwal-saya/{id}/hapus',
            [JadwalSayaController::class, 'hapusSlot']
        )->middleware('feature:guru.academic')->name('jadwal-saya.hapus-slot');
        
        Route::post(
            '/jadwal-saya/submit',
            [JadwalSayaController::class, 'submitJadwal']
        )->middleware('feature:guru.academic')->name('jadwal-saya.submit');

        Route::resource(
            'pengajuan-jadwal',
            PengajuanJadwalController::class
        )->only(['index', 'create', 'store'])
            ->middleware('feature:guru.academic');

        Route::get(
            '/kelas-saya',
            [WaliKelasController::class, 'kelasSaya']
        )->middleware('feature:guru.academic')->name('kelas-saya');

        Route::get(
            '/raport/{siswa}',
            [RaportController::class, 'show']
        )->middleware('feature:guru.academic')->name('raport.show');

        Route::get(
            '/raport/{siswa}/pdf',
            [RaportController::class, 'pdf']
        )->middleware('feature:guru.academic')->name('raport.pdf');

        Route::get(
            '/absensi',
            [AbsensiController::class, 'index']
        )->middleware('feature:guru.academic')->name('absensi.index');

        Route::post(
            '/absensi',
            [AbsensiController::class, 'store']
        )->middleware('feature:guru.academic')->name('absensi.store');

        Route::get(
            '/absensi/{absensi}/edit',
            [AbsensiController::class, 'edit']
        )->middleware('feature:guru.academic')->name('absensi.edit');

        Route::put(
            '/absensi/{absensi}',
            [AbsensiController::class, 'update']
        )->middleware('feature:guru.academic')->name('absensi.update');

        Route::get(
            '/rekap-absensi',
            [AbsensiController::class, 'rekap']
        )->middleware('feature:guru.academic')->name('absensi.rekap');
        Route::get(
            '/rekap-absensi/export',
            [AbsensiController::class, 'exportPdf']
        )->middleware('feature:guru.academic')->name('absensi.export');

        Route::get(
            '/rekap-bulanan',
            [AbsensiController::class, 'rekapBulanan']
        )->middleware('feature:guru.academic')->name('absensi.rekap-bulanan');

        Route::get(
            '/rekap-semester',
            [AbsensiController::class, 'rekapSemester']
        )->middleware('feature:guru.academic')->name('absensi.rekap-semester');

        Route::resource(
        'e-learning',
        ElearningController::class
        )->middleware('feature:guru.academic');

            Route::post(
            '/e-learning/upload-image',
            [ElearningController::class, 'uploadImage']
        )->middleware('feature:guru.academic')->name('e-learning.upload-image');

        Route::post(
            '/e-learning/{elearning}/komentar',
            [\App\Http\Controllers\Elearning\ElearningKomentarController::class, 'store']
        )->middleware('feature:guru.academic')->name('e-learning.komentar.store');

        Route::post(
            '/e-learning-komentar/{komentar}/reply',
            [\App\Http\Controllers\Elearning\ElearningKomentarReplyController::class, 'store']
        )->middleware('feature:guru.academic')->name(
            'e-learning.reply.store'
        );

        Route::get(
            '/nilai',
            [NilaiController::class, 'index']
        )->middleware('feature:guru.academic')->name('nilai.index');

        Route::post(
            '/nilai',
            [NilaiController::class, 'store']
        )->middleware('feature:guru.academic')->name('nilai.store');

        Route::get(
            '/perkembangan-siswa',
            [PerkembanganSiswaController::class, 'index']
        )->middleware('feature:guru.academic')->name('perkembangan.index');

        Route::post(
            '/perkembangan-siswa',
            [PerkembanganSiswaController::class, 'store']
        )->middleware('feature:guru.academic')->name('perkembangan.store');

        Route::get(
            '/siswa/{siswa}',
            [WaliKelasController::class, 'show']
        )->middleware('feature:guru.academic')->name('kelas-saya.show');

        Route::get(
            '/kenaikan-kelas',
            [KenaikanKelasGuruController::class, 'index']
        )->middleware('feature:guru.academic')->name('kenaikan.index');

        Route::post(
            '/kenaikan-kelas',
            [KenaikanKelasGuruController::class, 'store']
        )->middleware('feature:guru.academic')->name('kenaikan.store');

        Route::patch(
            '/kenaikan-kelas/submit',
            [KenaikanKelasGuruController::class, 'submit']
        )->middleware('feature:guru.academic')->name('kenaikan.submit');

        Route::get(
            '/status-validasi',
            [StatusValidasiController::class, 'index']
        )->middleware('feature:guru.academic')->name('status-validasi.index');

        Route::get('/dokumen/surat', [DokumenGuruController::class, 'index'])
            ->middleware('feature:guru.academic')
            ->name('dokumen.surat');

        Route::resource('pengajuan-surat',App\Http\Controllers\Guru\PengajuanSuratController::class)
            ->middleware('feature:guru.academic');

        Route::get('/nomor-surat',[NomorSuratGuruController::class, 'index'])
            ->middleware('feature:guru.academic')
            ->name('nomor-surat.index');

        Route::post('/nomor-surat/generate',[NomorSuratGuruController::class, 'generate'])
            ->middleware('feature:guru.academic')
            ->name('nomor-surat.generate');

        Route::get('/profile', [ProfileController::class, 'index'])
            ->name('profile');

        Route::get(
            '/ekskul/catatan-pembinaan',
            [CatatanPembinaanController::class, 'index']
        )->middleware('feature:guru.extracurricular')->name('ekskul.pembinaan.index');

        Route::post(
            '/ekskul/catatan-pembinaan/store',
            [CatatanPembinaanController::class, 'store']
        )->middleware('feature:guru.extracurricular')->name('ekskul.pembinaan.store');

        Route::get(
            '/ekskul/jadwal',
            [JadwalEkskulController::class, 'index']
        )->middleware('feature:guru.extracurricular')->name('ekskul.jadwal.index');

        Route::post(
            '/ekskul/jadwal/store',
            [JadwalEkskulController::class, 'store']
        )->middleware('feature:guru.extracurricular')->name('ekskul.jadwal.store');

        Route::get(
            '/ekskul/status-validasi',
            [StatusValidasiEkskulController::class, 'index']
        )->middleware('feature:guru.extracurricular')->name('ekskul.validasi.index');

        Route::get(
            '/ekskul/jadwal/{jadwal}/edit',
            [
                JadwalEkskulController::class,
                'edit'
            ]
        )->middleware('feature:guru.extracurricular')->name(
            'ekskul.jadwal.edit'
        );

        Route::put(
            '/ekskul/jadwal/{jadwal}',
            [
                JadwalEkskulController::class,
                'update'
            ]
        )->middleware('feature:guru.extracurricular')->name(
            'ekskul.jadwal.update'
        );

        Route::delete(
            '/ekskul/jadwal/{jadwal}',
            [
                JadwalEkskulController::class,
                'destroy'
            ]
        )->middleware('feature:guru.extracurricular')->name(
            'ekskul.jadwal.destroy'
        );

        Route::get('/profile/detail', [ProfileController::class, 'detail'])
            ->name('profile.detail');

        Route::put('/profile/detail', [ProfileController::class, 'updateDetail'])
            ->name('profile.detail.update');

        Route::patch(
            'kenaikan-kelas/submit',
            [KenaikanKelasGuruController::class, 'submit']
        )->middleware('feature:guru.academic')->name('kenaikan.submit');

        Route::patch(
            'kenaikan-kelas/cancel',
            [KenaikanKelasGuruController::class, 'cancelSubmission']
        )->middleware('feature:guru.academic')->name('kenaikan.cancel');

});