<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tu\DashboardController;
use App\Http\Controllers\Tu\KelasController;
use App\Http\Controllers\Tu\SiswaController;
use App\Http\Controllers\Tu\SearchController;
use App\Http\Controllers\Tu\GuruController;

Route::middleware(['auth', 'role:tu'])
    ->prefix('tu')
    ->name('tu.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('kelas', KelasController::class);

        Route::resource('siswa', SiswaController::class);

        Route::get('/search', [SearchController::class, 'index'])->name('search');

        Route::get('/search-ajax', [SearchController::class, 'ajax'])->name('search.ajax');
  
        Route::get('/siswa/{id}/detail', [SiswaController::class, 'detail'])->name('siswa.detail');
 
        Route::post('/siswa/import', [SiswaController::class, 'import'])->name('siswa.import');

        Route::get('/siswa/export-excel', [SiswaController::class, 'exportExcel'])->name('siswa.export.excel');
        
        Route::get('/siswa/export-pdf', [SiswaController::class, 'exportPDF'])->name('siswa.export.pdf');

        Route::resource('guru', GuruController::class);


    });
