<?php

use Illuminate\Support\Facades\Route;
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

Route::middleware(['auth', 'role:tu'])
    ->prefix('tu')
    ->name('tu.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Kelas
        Route::get('/kelas/search/ajax', [KelasController::class, 'ajaxSearch'])
             ->name('kelas.search');
        Route::get('/kelas/{id}/detail', [KelasController::class, 'detail'])->name('kelas.detail');
        
        Route::post('kelas/{id}/restore', [KelasController::class, 'restore'])->name('kelas.restore');
        Route::delete('kelas/{id}/force-delete', [KelasController::class, 'forceDelete'])->name('kelas.forceDelete');

        // Bulk actions
        Route::post('kelas/bulk', [KelasController::class, 'bulkAction'])->name('kelas.bulk');

        Route::post('/kelas/store-multiple', [KelasController::class, 'storeMultiple'])
            ->name('kelas.storeMultiple');

        Route::resource('kelas', KelasController::class);
        // Siswa
        Route::get('/siswa/search/ajax', [SiswaController::class, 'ajaxSearch'])
    ->name('siswa.search');

Route::post('siswa/bulk', [SiswaController::class, 'bulkAction'])
    ->name('siswa.bulk');

Route::get('/siswa/{id}/detail', [SiswaController::class, 'detail'])
    ->name('siswa.detail');

Route::resource('siswa', SiswaController::class);

        Route::resource('siswa', SiswaController::class);
        

        // Guru
        Route::get('/guru/{id}/detail', [GuruController::class, 'detail'])->name('guru.detail');

Route::post('guru/bulk', [GuruController::class, 'bulkAction'])->name('guru.bulk');

Route::get('/guru/search/ajax', [GuruController::class, 'ajaxSearch'])->name('guru.search');


        Route::resource('guru', GuruController::class);

        // Search
        Route::get('/search', [SearchController::class, 'index'])->name('search');
        Route::get('/search-ajax', [SearchController::class, 'ajax'])->name('search.ajax');
        

        // Jadwal
        Route::get('/jadwal/{id}/detail', [JadwalController::class, 'detail'])->name('jadwal.detail');

Route::post('jadwal/bulk', [JadwalController::class, 'bulkAction'])->name('jadwal.bulk');

Route::get('/jadwal/search/ajax', [JadwalController::class, 'ajaxSearch'])->name('jadwal.search');

Route::resource('jadwal', JadwalController::class);


        // Mapel
        // Detail
Route::get('/mapel/{id}/detail', [MataPelajaranController::class, 'show'])->name('mapel.detail');

// Restore & Force Delete
Route::post('/mapel/{id}/restore', [MataPelajaranController::class, 'restore'])->name('mapel.restore');
Route::delete('/mapel/{id}/force-delete', [MataPelajaranController::class, 'forceDelete'])->name('mapel.forceDelete');

// Bulk Action
Route::post('/mapel/bulk', [MataPelajaranController::class, 'bulkAction'])->name('mapel.bulk');

// AJAX Search
Route::get('/mapel/search/ajax', [MataPelajaranController::class, 'ajaxSearch'])->name('mapel.search');

// Default CRUD
Route::resource('mapel', MataPelajaranController::class);


        // AJAX Live Search Tahun Ajaran
Route::get('/tahun/search/ajax', [TahunAjaranController::class, 'ajaxSearch'])
    ->name('tahun.search');

// RESTORE + FORCE DELETE
Route::post('/tahun/{id}/restore', [TahunAjaranController::class, 'restore'])->name('tahun.restore');
Route::delete('/tahun/{id}/force-delete', [TahunAjaranController::class, 'forceDelete'])->name('tahun.forceDelete');

// Bulk Action
Route::post('/tahun/bulk', [TahunAjaranController::class, 'bulkAction'])->name('tahun.bulk');

// CRUD
Route::resource('tahun', TahunAjaranController::class);

        // Ruangan
        Route::resource('ruangan', RuanganController::class);

        // Wali Kelas
        Route::resource('walikelas', WaliKelasController::class)->except(['show','edit','update']);

        // Arsip Dokumen
        Route::resource('arsip-dokumen', ArsipDokumenController::class);

        // Tambahan preview & download
        Route::get('arsip-dokumen/{id}/preview', [ArsipDokumenController::class, 'preview'])->name('arsip-dokumen.preview');
        Route::get('arsip-dokumen/{id}/download', [ArsipDokumenController::class, 'download'])->name('arsip-dokumen.download');
        Route::get('arsip-dokumen/{id}/viewer', function($id) {
            $doc = \App\Models\ArsipDokumen::findOrFail($id);
            $pdfPath = 'storage/' . $doc->file_path;
            return view('tu.arsip.viewer', compact('pdfPath'));
        })->name('arsip-dokumen.viewer');

        // Resource (index, create, store, edit, update, delete)
        Route::resource('datakepegawaian', DataKepegawaianController::class);

        // DETAIL PEGAWAI
        Route::get('/datakepegawaian/{id}/detail', 
            [DataKepegawaianController::class, 'detail']
        )->name('datakepegawaian.detail');

        // Tambahan Soft Delete + Bulk Action untuk Kelas

 });