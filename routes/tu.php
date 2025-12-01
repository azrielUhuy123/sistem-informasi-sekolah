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

Route::middleware(['auth', 'role:tu'])
    ->prefix('tu')
    ->name('tu.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // Kelas
        Route::resource('kelas', KelasController::class);
        Route::get('/kelas/{id}/detail', [KelasController::class, 'detail'])
            ->name('kelas.detail');

        // Siswa
        Route::resource('siswa', SiswaController::class);
        Route::get('/siswa/{id}/detail', [SiswaController::class, 'detail'])
            ->name('siswa.detail');
        Route::post('/siswa/import', [SiswaController::class, 'import'])
            ->name('siswa.import');
        Route::get('/siswa/export-excel', [SiswaController::class, 'exportExcel'])
            ->name('siswa.export.excel');
        Route::get('/siswa/export-pdf', [SiswaController::class, 'exportPDF'])
            ->name('siswa.export.pdf');

        // Guru
        Route::resource('guru', GuruController::class);

        // Search
        Route::get('/search', [SearchController::class, 'index'])
            ->name('search');
        Route::get('/search-ajax', [SearchController::class, 'ajax'])
            ->name('search.ajax');

        // Jadwal Kelas & Guru
        Route::resource('jadwal', JadwalController::class);

        Route::get('/jadwal/{id}/detail', [JadwalController::class, 'detail'])
            ->name('jadwal.detail');

        // Export PDF Jadwal per Kelas
        Route::get('/jadwal/{kelas_id}/export-pdf', [JadwalController::class, 'exportPDF'])
            ->name('jadwal.export.pdf');

        // Export PDF Jadwal per Guru
        Route::get('/guru/{id}/jadwal/export-pdf', [JadwalController::class, 'exportGuruPDF'])
            ->name('jadwal.guru.export.pdf');

        // Mapel
        Route::resource('mapel', \App\Http\Controllers\Tu\MataPelajaranController::class);

        // Tahun Ajaran
        Route::resource('tahun', \App\Http\Controllers\Tu\TahunAjaranController::class);

        // Ruangan
        Route::resource('ruangan', RuanganController::class);

        // Wali Kelas
        Route::resource('walikelas', WaliKelasController::class)->except(['show','edit','update']);

        // Arsip Dokumen
Route::resource('arsip-dokumen', ArsipDokumenController::class);

// Tambahan route khusus preview & download
Route::get('arsip-dokumen/{id}/preview', [ArsipDokumenController::class, 'preview'])
    ->name('arsip-dokumen.preview');

Route::get('arsip-dokumen/{id}/download', [ArsipDokumenController::class, 'download'])
    ->name('arsip-dokumen.download');

    Route::get('/arsip-dokumen/{id}/viewer', function($id) {
    $doc = \App\Models\ArsipDokumen::findOrFail($id);

    $pdfPath = '/storage/' . $doc->file_path;

    return view('tu.arsip.viewer', compact('pdfPath'));
})->name('tu.arsip-dokumen.viewer');



    });
