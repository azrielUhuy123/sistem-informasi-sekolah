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
use App\Models\PembinaEkskul;
use Illuminate\Support\Facades\Auth;

Route::prefix('guru')
    ->name('guru.')
    ->middleware(['auth', 'role:guru', 'guru.mode'])
    ->group(function () {

        Route::get(
            '/dashboard-gurubidang',
            [DashboardGuruBidangController::class, 'index']
        )->name('dashboard-gurubidang');
        
        Route::get(
            '/switch-mode/{mode}',
            function ($mode) {

                $guru = Auth::user()->guru;

                if ($mode === 'akademik') {

                    session([
                        'guru_mode' => 'akademik'
                    ]);

                    return back();
                }

                if ($mode === 'ekskul') {

                    $isPembina = PembinaEkskul::where(
                        'guru_id',
                        $guru->id
                    )
                    ->where(
                        'tahun_ajaran_id',
                        current_tahun_id()
                    )
                    ->exists();

                    if ($isPembina) {

                        session([
                            'guru_mode' => 'ekskul'
                        ]);

                    }

                    return back();
                }

                abort(404);
            }
        )->name('switch-mode');

        Route::get(
            '/ekskul/dashboard',
            [EkskulController::class, 'dashboard']
        )->name('ekskul.dashboard');

        Route::get(
            '/ekskul',
            [EkskulController::class, 'index']
        )->name('ekskul.index');

        /*
        |--------------------------------------------------------------------------
        | ANGGOTA EKSKUL
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/ekskul/anggota',
            [EkskulAnggotaController::class, 'index']
        )->name('ekskul.anggota.index');

        Route::post(
            '/ekskul/anggota/store',
            [EkskulAnggotaController::class, 'store']
        )->name('ekskul.anggota.store');

        Route::delete(
            '/ekskul/anggota/{id}',
            [EkskulAnggotaController::class, 'destroy']
        )->name('ekskul.anggota.destroy');
        
        /*
        |--------------------------------------------------------------------------
        | DAFTAR ANGGOTA EKSKUL
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/ekskul/daftar-anggota',
            [EkskulAnggotaController::class, 'daftar']
        )->name('ekskul.daftar-anggota');

        Route::delete(
            '/ekskul/daftar-anggota/{id}',
            [EkskulAnggotaController::class, 'destroy']
        )->name('ekskul.daftar-anggota.destroy');

        Route::prefix('ekskul')
            ->name('ekskul.')
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
        )->name('ekskul.jurnal.index');

        Route::post(
            '/ekskul/jurnal',
            [JurnalEkskulController::class, 'store']
        )->name('ekskul.jurnal.store');

        Route::delete(
            '/ekskul/jurnal/{id}',
            [JurnalEkskulController::class, 'destroy']
        )->name('ekskul.jurnal.destroy');

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
            '/raport/{siswa}/pdf',
            [RaportController::class, 'pdf']
        )->name('raport.pdf');

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
            '/rekap-bulanan',
            [AbsensiController::class, 'rekapBulanan']
        )->name('absensi.rekap-bulanan');

        Route::get(
            '/rekap-semester',
            [AbsensiController::class, 'rekapSemester']
        )->name('absensi.rekap-semester');

        Route::resource(
        'e-learning',
        ElearningController::class
        );

            Route::post(
            '/e-learning/upload-image',
            [ElearningController::class, 'uploadImage']
        )->name('e-learning.upload-image');

        Route::post(
            '/e-learning/{elearning}/komentar',
            [\App\Http\Controllers\Elearning\ElearningKomentarController::class, 'store']
        )->name('e-learning.komentar.store');

        Route::post(
            '/e-learning-komentar/{komentar}/reply',
            [\App\Http\Controllers\Elearning\ElearningKomentarReplyController::class, 'store']
        )->name(
            'e-learning.reply.store'
        );

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

        Route::patch(
            '/kenaikan-kelas/submit',
            [KenaikanKelasGuruController::class, 'submit']
        )->name('kenaikan.submit');

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

        Route::get(
            '/ekskul/catatan-pembinaan',
            [CatatanPembinaanController::class, 'index']
        )->name('ekskul.pembinaan.index');

        Route::post(
            '/ekskul/catatan-pembinaan/store',
            [CatatanPembinaanController::class, 'store']
        )->name('ekskul.pembinaan.store');

        Route::get(
            '/ekskul/jadwal',
            [JadwalEkskulController::class, 'index']
        )->name('ekskul.jadwal.index');

        Route::post(
            '/ekskul/jadwal/store',
            [JadwalEkskulController::class, 'store']
        )->name('ekskul.jadwal.store');

        Route::post(
            '/ekskul/jadwal/submit',
            [JadwalEkskulController::class, 'submit']
        )->name('ekskul.jadwal.submit');

        Route::get(
            '/ekskul/status-validasi',
            [StatusValidasiEkskulController::class, 'index']
        )->name('ekskul.validasi.index');

        Route::get(
            '/ekskul/jadwal/{jadwal}/edit',
            [
                JadwalEkskulController::class,
                'edit'
            ]
        )->name(
            'ekskul.jadwal.edit'
        );

        Route::put(
            '/ekskul/jadwal/{jadwal}',
            [
                JadwalEkskulController::class,
                'update'
            ]
        )->name(
            'ekskul.jadwal.update'
        );

        Route::delete(
            '/ekskul/jadwal/{jadwal}',
            [
                JadwalEkskulController::class,
                'destroy'
            ]
        )->name(
            'ekskul.jadwal.destroy'
        );

        Route::get('/profile/detail', [ProfileController::class, 'detail'])
            ->name('profile.detail');

        Route::put('/profile/detail', [ProfileController::class, 'updateDetail'])
            ->name('profile.detail.update');

        Route::patch(
            'kenaikan-kelas/submit',
            [KenaikanKelasGuruController::class, 'submit']
        )->name('kenaikan.submit');

        Route::patch(
            'kenaikan-kelas/cancel',
            [KenaikanKelasGuruController::class, 'cancelSubmission']
        )->name('kenaikan.cancel');

});