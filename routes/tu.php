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

       

    Route::prefix('kelas')->name('kelas.')->group(function () {

    //Grup Kelas
    Route::get('/', [KelasController::class, 'index'])
        ->name('index');

    Route::get('/grup/create', [KelasController::class, 'createGrup'])
        ->name('grup.create');

    Route::post('/grup', [KelasController::class, 'storeGrup'])
        ->name('grup.store');
        

    //Rombel dalam grup
    Route::get('/rombel/{kelas_master}/{tahun_ajaran}',
        [KelasController::class, 'rombelIndex'])
        ->name('rombel.index');

    /**
     * ===============================
     * CRUD ROMBEL (tetap)
     * ===============================
     */
    Route::get('/create', [KelasController::class, 'create'])->name('create');
    Route::post('/', [KelasController::class, 'store'])->name('store');

    Route::get('/{id}/edit', [KelasController::class, 'edit'])->name('edit');
    Route::put('/{id}', [KelasController::class, 'update'])->name('update');

    Route::delete('/{id}', [KelasController::class, 'destroy'])->name('destroy');

    Route::get('/{id}/detail', [KelasController::class, 'detail'])->name('detail');

    Route::post('/{id}/restore', [KelasController::class, 'restore'])->name('restore');
    Route::delete('/{id}/force-delete', [KelasController::class, 'forceDelete'])->name('forceDelete');

    Route::post('/bulk', [KelasController::class, 'bulkAction'])->name('bulk');
    Route::post('/store-multiple', [KelasController::class, 'storeMultiple'])->name('storeMultiple');

    Route::get('/search/ajax', [KelasController::class, 'ajaxSearch'])->name('search');

    // form tambah rombel
    Route::get(
        '/rombel/{kelas_master}/{tahun_ajaran}/create',
        [KelasController::class, 'createRombel']
    )->name('rombel.create');

    // simpan rombel
    Route::post(
        '/rombel/{kelas_master}/{tahun_ajaran}',
        [KelasController::class, 'storeRombel']
    )->name('rombel.store');

});




        /*
        |--------------------------------------------------------------------------
        | SISWA
        |--------------------------------------------------------------------------
        */
        Route::get('/siswa/search/ajax', [SiswaController::class, 'ajaxSearch'])
            ->name('siswa.search');

        Route::post('/siswa/bulk', [SiswaController::class, 'bulkAction'])
            ->name('siswa.bulk');

        Route::get('/siswa/{id}/detail', [SiswaController::class, 'detail'])
            ->name('siswa.detail');

        Route::post('/siswa/import', [SiswaController::class, 'importExcel'])
            ->name('siswa.import');

        Route::post('/siswa/{id}/restore', [SiswaController::class, 'restore'])
            ->name('siswa.restore');

        Route::delete('/siswa/{id}/force-delete', [SiswaController::class, 'forceDelete'])
            ->name('siswa.forceDelete');

        Route::resource('siswa', SiswaController::class);


        /*
        |--------------------------------------------------------------------------
        | GURU
        |--------------------------------------------------------------------------
        */
        Route::get('/guru/search/ajax', [GuruController::class, 'ajaxSearch'])
            ->name('guru.search');

        Route::post('/guru/bulk', [GuruController::class, 'bulkAction'])
            ->name('guru.bulk');

        Route::get('/guru/{id}/detail', [GuruController::class, 'detail'])
            ->name('guru.detail');

        Route::resource('guru', GuruController::class);

        /*
        |--------------------------------------------------------------------------
        | SEARCH GLOBAL
        |--------------------------------------------------------------------------
        */
        Route::get('/search', [SearchController::class, 'index'])
            ->name('search');

        Route::get('/search-ajax', [SearchController::class, 'ajax'])
            ->name('search.ajax');

        /*
        |--------------------------------------------------------------------------
        | JADWAL
        |--------------------------------------------------------------------------
        */
        Route::get('/jadwal/search/ajax', [JadwalController::class, 'ajaxSearch'])
            ->name('jadwal.search');

        Route::post('/jadwal/bulk', [JadwalController::class, 'bulkAction'])
            ->name('jadwal.bulk');

        Route::get('/jadwal/{id}/detail', [JadwalController::class, 'detail'])
            ->name('jadwal.detail');

        Route::resource('jadwal', JadwalController::class);

        /*
        |--------------------------------------------------------------------------
        | MATA PELAJARAN
        |--------------------------------------------------------------------------
        */
        Route::get('/mapel/search/ajax', [MataPelajaranController::class, 'ajaxSearch'])
            ->name('mapel.search');

        Route::post('/mapel/bulk', [MataPelajaranController::class, 'bulkAction'])
            ->name('mapel.bulk');

        Route::post('/mapel/{id}/restore', [MataPelajaranController::class, 'restore'])
            ->name('mapel.restore');

        Route::delete('/mapel/{id}/force-delete', [MataPelajaranController::class, 'forceDelete'])
            ->name('mapel.forceDelete');

        Route::get('/mapel/{id}/detail', [MataPelajaranController::class, 'show'])
            ->name('mapel.detail');

        Route::resource('mapel', MataPelajaranController::class);

        /*
        |--------------------------------------------------------------------------
        | TAHUN AJARAN
        |--------------------------------------------------------------------------
        */
        Route::get('/tahun/search/ajax', [TahunAjaranController::class, 'ajaxSearch'])
            ->name('tahun.search');

        Route::post('/tahun/bulk', [TahunAjaranController::class, 'bulkAction'])
            ->name('tahun.bulk');

        Route::post('/tahun/{id}/restore', [TahunAjaranController::class, 'restore'])
            ->name('tahun.restore');

        Route::delete('/tahun/{id}/force-delete', [TahunAjaranController::class, 'forceDelete'])
            ->name('tahun.forceDelete');

        Route::get('/tahun/{id}/detail', [TahunAjaranController::class, 'detail'])
            ->name('tahun.detail');

        Route::resource('tahun', TahunAjaranController::class);

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

        Route::get('/arsip-dokumen/{id}/preview',
            [ArsipDokumenController::class, 'preview'])
            ->name('arsip-dokumen.preview');

        Route::get('/arsip-dokumen/{id}/download',
            [ArsipDokumenController::class, 'download'])
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
        Route::prefix('keuangan')
            ->name('keuangan.')
            ->group(function () {

                Route::get('/pemasukan',
                    [KeuanganPemasukanController::class, 'index'])
                    ->name('pemasukan.index');

                Route::get('/pemasukan/create',
                    [KeuanganPemasukanController::class, 'create'])
                    ->name('pemasukan.create');

                Route::post('/pemasukan',
                    [KeuanganPemasukanController::class, 'store'])
                    ->name('pemasukan.store');
            });

        
            
// 🔥 PENEMPATAN (TANPA INDEX)
    Route::prefix('penempatan-siswa')
        ->name('penempatan-siswa.')
        ->group(function () {

        Route::get('/create', [PenempatanSiswaController::class, 'create'])
            ->name('create');

        Route::post('/', [PenempatanSiswaController::class, 'store'])
            ->name('store');

        Route::post('/naik-kelas', [PenempatanSiswaController::class, 'naikKelas'])
            ->name('naik-kelas');

        Route::get('/preview-naik-kelas', [PenempatanSiswaController::class, 'previewNaikKelas'])
            ->name('preview-naik-kelas');
    });

    Route::resource('siswa', SiswaController::class);


    Route::post('siswa/{id}/restore', [SiswaController::class, 'restore'])
        ->name('siswa.restore');

    Route::delete('siswa/{id}/force-delete', [SiswaController::class, 'forceDelete'])
        ->name('siswa.forceDelete');



    });
