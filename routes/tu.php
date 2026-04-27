<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| TU CONTROLLERS
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Tu\DashboardController;
use App\Http\Controllers\Tu\KelasController;
use App\Http\Controllers\Tu\SiswaController;
use App\Http\Controllers\Tu\SearchController;
use App\Http\Controllers\Tu\GuruController;
use App\Http\Controllers\Tu\JadwalController;
use App\Http\Controllers\Tu\RuanganController;
use App\Http\Controllers\Tu\TahunAjaranController;
use App\Http\Controllers\Tu\WaliKelasController;
use App\Http\Controllers\Tu\ArsipDokumenController;
use App\Http\Controllers\Tu\DataKepegawaianController;
use App\Http\Controllers\Tu\MataPelajaranController;
use App\Http\Controllers\Tu\KeuanganPemasukanController;
use App\Http\Controllers\Tu\PenempatanSiswaController;
use App\Http\Controllers\Tu\AngkatanController;
use App\Http\Controllers\RombelController;

/*
|--------------------------------------------------------------------------
| TU ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:tu'])
    ->prefix('tu')
    ->name('tu.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD
        |--------------------------------------------------------------------------
        */
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | KELAS & ROMBEL
        |--------------------------------------------------------------------------
        */
        Route::middleware(['approved', 'tahun.aktif'])
            ->prefix('kelas')
            ->name('kelas.')
            ->group(function () {

                // ===== GRUP KELAS =====
                Route::get('/', [KelasController::class, 'index'])->name('index');
                Route::get('/grup/create', [KelasController::class, 'createGrup'])->name('grup.create');
                Route::post('/grup', [KelasController::class, 'storeGrup'])->name('grup.store');

                // ===== ROMBEL =====
                Route::prefix('rombel')->name('rombel.')->group(function () {

                    Route::get('/{kelas_grup}',
                        [KelasController::class, 'rombelIndex']
                    )->name('index');

                    Route::get('/{id}/show',
                        [KelasController::class, 'showRombel']
                    )->name('show');

                    Route::get('/{kelas_grup}/create',
                        [KelasController::class, 'createRombel']
                    )->name('create');

                    Route::post('/{kelas_grup}',
                        [KelasController::class, 'storeRombel']
                    )->name('store');
                });

                // ===== CRUD KELAS =====
                Route::get('/create', [KelasController::class, 'create'])->name('create');
                Route::post('/', [KelasController::class, 'store'])->name('store');
                Route::get('/{id}/edit', [KelasController::class, 'edit'])->name('edit');
                Route::put('/{id}', [KelasController::class, 'update'])->name('update');
                Route::delete('/{id}', [KelasController::class, 'destroy'])->name('destroy');
                Route::get('/{id}/detail', [KelasController::class, 'detail'])->name('detail');
                Route::post('/{id}/restore', [KelasController::class, 'restore'])->name('restore');
                Route::delete('/{id}/force-delete', [KelasController::class, 'forceDelete'])->name('forceDelete');
                Route::post('/bulk', [KelasController::class, 'bulkAction'])->name('bulk');
                Route::get('/search/ajax', [KelasController::class, 'ajaxSearch'])->name('search');
                Route::get('/{id}/wali', [KelasController::class, 'wali'])
                    ->name('wali');
            Route::post('/{id}/wali', [KelasController::class, 'storeWali'])
                    ->name('wali.store');
            });

            
        /*
        |--------------------------------------------------------------------------
        | SISWA
        |--------------------------------------------------------------------------
        */
        Route::get('/siswa/search/ajax', [SiswaController::class, 'ajaxSearch'])->name('siswa.search');
        Route::post('/siswa/bulk', [SiswaController::class, 'bulkAction'])->name('siswa.bulk');
        Route::post('/siswa/import', [SiswaController::class, 'importExcel'])->name('siswa.import');
        Route::post('/siswa/{id}/restore', [SiswaController::class, 'restore'])->name('siswa.restore');
        Route::delete('/siswa/{id}/force-delete', [SiswaController::class, 'forceDelete'])->name('siswa.forceDelete');
        Route::resource('siswa', SiswaController::class);

        /*
        |--------------------------------------------------------------------------
        | AJAX (ANGKATAN → GRUP → ROMBEL)
        |--------------------------------------------------------------------------
        */
        Route::prefix('ajax')->name('ajax.')->group(function () {

            Route::get('/kelas-grup', [SiswaController::class, 'ajaxGrup'])->name('kelas-grup');
            Route::get('/kelas', [SiswaController::class, 'ajaxKelas'])->name('kelas');
            Route::get('/siswa-table', [SiswaController::class, 'ajaxTable'])->name('siswa-table');
        });

        /*
        |--------------------------------------------------------------------------
        | GURU
        |--------------------------------------------------------------------------
        */
        Route::get('/guru/search/ajax', [GuruController::class, 'ajaxSearch'])->name('guru.search');
        Route::post('/guru/bulk', [GuruController::class, 'bulkAction'])->name('guru.bulk');
        Route::get('/guru/{id}/detail', [GuruController::class, 'detail'])->name('guru.detail');
        Route::resource('guru', GuruController::class);

        /*
        |--------------------------------------------------------------------------
        | SEARCH GLOBAL
        |--------------------------------------------------------------------------
        */
        Route::get('/search', [SearchController::class, 'index'])->name('search');
        Route::get('/search-ajax', [SearchController::class, 'ajax'])->name('search.ajax');

        /*
        |--------------------------------------------------------------------------
        | JADWAL
        |--------------------------------------------------------------------------
        */

        Route::prefix('jadwal')->name('jadwal.')->group(function () {

            Route::get('/', [JadwalController::class, 'index'])->name('index');

            Route::get('/{kelas}/{hari}/{jam}/edit', [JadwalController::class, 'edit'])
                ->name('edit');

            Route::post('/{kelas}/{hari}/{jam}', [JadwalController::class, 'update'])
                ->name('update');
        });

            Route::get('/jadwal-guru', [JadwalController::class, 'jadwalGuru'])
                ->name('jadwal.guru');
        /*
        |--------------------------------------------------------------------------
        | MATA PELAJARAN
        |--------------------------------------------------------------------------
        */
        Route::get('/mapel/search/ajax', [MataPelajaranController::class, 'ajaxSearch'])->name('mapel.search');
        Route::post('/mapel/bulk', [MataPelajaranController::class, 'bulkAction'])->name('mapel.bulk');
        Route::post('/mapel/{id}/restore', [MataPelajaranController::class, 'restore'])->name('mapel.restore');
        Route::delete('/mapel/{id}/force-delete', [MataPelajaranController::class, 'forceDelete'])->name('mapel.forceDelete');
        Route::get('/mapel/{id}/detail', [MataPelajaranController::class, 'show'])->name('mapel.detail');
        Route::resource('mapel', MataPelajaranController::class);

        /*
        |--------------------------------------------------------------------------
        | TAHUN AJARAN
        |--------------------------------------------------------------------------
        */
        Route::get('/tahun/search/ajax', [TahunAjaranController::class, 'ajaxSearch'])->name('tahun.search');
        Route::post('/tahun/bulk', [TahunAjaranController::class, 'bulkAction'])->name('tahun.bulk');
        Route::post('/tahun/{id}/restore', [TahunAjaranController::class, 'restore'])->name('tahun.restore');
        Route::delete('/tahun/{id}/force-delete', [TahunAjaranController::class, 'forceDelete'])->name('tahun.forceDelete');
        Route::get('/tahun/{id}/detail', [TahunAjaranController::class, 'detail'])->name('tahun.detail');
        Route::resource('tahun', TahunAjaranController::class);
        Route::post('/tahun/transisi',
            [TahunAjaranController::class, 'masukTransisi']
        )->name('tahun.transisi');

        Route::post('/tahun/tutup',
            [TahunAjaranController::class, 'tutupTahun']
        )->name('tahun.tutup');

        /*
        |--------------------------------------------------------------------------
        | RUANGAN & WALI KELAS
        |--------------------------------------------------------------------------
        */
        Route::resource('ruangan', RuanganController::class);
        Route::resource('walikelas', WaliKelasController::class)
            ->except(['show', 'edit', 'update']);

        /*
        |--------------------------------------------------------------------------
        | ARSIP DOKUMEN
        |--------------------------------------------------------------------------
        */
        Route::resource('arsip-dokumen', ArsipDokumenController::class);

        Route::get('/arsip-dokumen/{id}/preview', [ArsipDokumenController::class, 'preview'])
            ->name('arsip-dokumen.preview');

        Route::get('/arsip-dokumen/{id}/download', [ArsipDokumenController::class, 'download'])
            ->name('arsip-dokumen.download');

        /*
        |--------------------------------------------------------------------------
        | DATA KEPEGAWAIAN
        |--------------------------------------------------------------------------
        */
        Route::resource('datakepegawaian', DataKepegawaianController::class);

        Route::get('/datakepegawaian/{id}/detail',
            [DataKepegawaianController::class, 'detail'])
            ->name('datakepegawaian.detail');

        /*
        |--------------------------------------------------------------------------
        | KEUANGAN
        |--------------------------------------------------------------------------
        */
        Route::prefix('keuangan')->name('keuangan.')->group(function () {

            Route::get('/pemasukan', [KeuanganPemasukanController::class, 'index'])
                ->name('pemasukan.index');

            Route::get('/pemasukan/create', [KeuanganPemasukanController::class, 'create'])
                ->name('pemasukan.create');

            Route::post('/pemasukan', [KeuanganPemasukanController::class, 'store'])
                ->name('pemasukan.store');
        });

        /*
        |--------------------------------------------------------------------------
        | PENEMPATAN SISWA
        |--------------------------------------------------------------------------
        */
        Route::prefix('penempatan-siswa')
            ->name('penempatan-siswa.')
            ->group(function () {

                Route::get('/create', [PenempatanSiswaController::class, 'create'])->name('create');
                Route::post('/', [PenempatanSiswaController::class, 'store'])->name('store');
                Route::post('/naik-kelas', [PenempatanSiswaController::class, 'naikKelas'])->name('naik-kelas');
                Route::get('/preview-naik-kelas', [PenempatanSiswaController::class, 'previewNaikKelas'])
                    ->name('preview-naik-kelas');
                Route::post('/{id}/keluarkan',
                    [PenempatanSiswaController::class, 'keluarkan']
                )->name('keluarkan');
                Route::get('/naik-kelas',
                    [PenempatanSiswaController::class, 'indexNaikKelas']
                )->name('naik-kelas.index');
                Route::get('/naik-kelas/mapping',
                    [PenempatanSiswaController::class, 'mappingNaikKelas']
                )->name('naik-kelas.mapping');
            });

        /*
        |--------------------------------------------------------------------------
        | ANGKATAN
        |--------------------------------------------------------------------------
        */
        Route::resource('angkatan', AngkatanController::class)
            ->except(['show']);

        Route::post('angkatan/{angkatan}/toggle',
            [AngkatanController::class, 'toggle'])
            ->name('angkatan.toggle');
    });
