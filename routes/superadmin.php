<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\UserController;
use App\Http\Controllers\SuperAdmin\UserApprovalController;
use App\Http\Controllers\SuperAdmin\MonitoringJadwalController;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\HariSekolah;
use App\Models\JamPelajaran;
use App\Http\Controllers\SuperAdmin\DataKepegawaianApprovalController;
use App\Http\Controllers\SuperAdmin\DokumenSuperAdminController;
use App\Http\Controllers\SuperAdmin\ValidasiSuratController;
use App\Http\Controllers\SuperAdmin\NomorSuratController;
use App\Http\Controllers\SuperAdmin\GuruController;
use App\Http\Controllers\SuperAdmin\PengampuMapelController;
use App\Http\Controllers\SuperAdmin\WaliKelasController;
use App\Http\Controllers\SuperAdmin\JadwalFinalController;
use App\Http\Controllers\SuperAdmin\MataPelajaranController;
use App\Http\Controllers\Superadmin\PembinaEkskulController;
use App\Http\Controllers\Superadmin\MonitoringEkskulController;
use App\Http\Controllers\Superadmin\JadwalFinalEkskulController;
use App\Http\Controllers\SuperAdmin\SiswaController;
use App\Http\Controllers\SuperAdmin\KenaikanKelasController;

Route::middleware(['auth', 'role:superadmin'])
    ->prefix('superadmin')
    ->name('superadmin.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/users', [UserController::class, 'index'])
            ->name('users.index');

        Route::get('/users/{user}/edit', [UserController::class, 'edit'])
            ->name('users.edit');

        Route::put('/users/{user}', [UserController::class, 'update'])
            ->name('users.update');

        Route::get('/users/pending', [UserApprovalController::class, 'pending'])
            ->name('users.pending');

        Route::patch('/users/{user}/approve', [UserApprovalController::class, 'approve'])
            ->name('users.approve');

        Route::patch('/users/{user}/reject', [UserApprovalController::class, 'reject'])
            ->name('users.reject');

        Route::get('/users/approved', [UserApprovalController::class, 'approved'])
            ->name('users.approved');

        Route::get('/users/rejected', [UserApprovalController::class, 'rejected'])
            ->name('users.rejected');
        
        Route::post(
            '/users/{user}/block',
            [UserApprovalController::class, 'block']
        )->name('users.block');

        Route::post(
            '/users/{user}/unblock',
            [UserApprovalController::class, 'unblock']
        )->name('users.unblock');

        Route::get(
            '/monitoring-jadwal',
            [MonitoringJadwalController::class, 'index']
        )->name('monitoring-jadwal.index');

        Route::get('/monitoring-jadwal/{kelas}', [MonitoringJadwalController::class, 'show'])
            ->name('monitoring-jadwal.show');

        Route::post(
            '/monitoring-jadwal/{kelas}/approve',
            [MonitoringJadwalController::class, 'approve']
        )->name('monitoring-jadwal.approve');

        Route::post(
            '/monitoring-jadwal/{kelas}/reject',
            [MonitoringJadwalController::class, 'reject']
        )->name('monitoring-jadwal.reject');

        Route::patch(
            '/monitoring-jadwal/{kelas}/publish',
            [MonitoringJadwalController::class, 'publish']
        )->name('monitoring-jadwal.publish');

        Route::resource(
            'guru',
            GuruController::class
        );
        
        Route::prefix('wali-kelas')
        ->name('wali-kelas.')
        ->controller(WaliKelasController::class)
        ->group(function () {

            Route::get('/', 'index')
                ->name('index');

            Route::get('/create', 'create')
                ->name('create');

            Route::post('/', 'store')
                ->name('store');

            Route::delete('/{waliKelas}', 'destroy')
                ->name('destroy');

        });

        Route::get(
            '/mapel',
            [MataPelajaranController::class, 'index']
        )->name('mapel.index');
        
        Route::get(
            '/pengampu-mapel',
            [PengampuMapelController::class, 'index']
        )->name('pengampu-mapel.index');

        Route::post(
            '/pengampu-mapel',
            [PengampuMapelController::class, 'store']
        )->name('pengampu-mapel.store');

        Route::delete(
            '/pengampu-mapel/{pengampuMapel}',
            [PengampuMapelController::class, 'destroy']
        )->name('pengampu-mapel.destroy');

        Route::get(
            '/pembina-ekskul',
            [PembinaEkskulController::class, 'index']
        )->name('pembina-ekskul.index');

        Route::post(
            '/pembina-ekskul',
            [PembinaEkskulController::class, 'store']
        )->name('pembina-ekskul.store');

        Route::delete(
            '/pembina-ekskul/{id}',
            [PembinaEkskulController::class, 'destroy']
        )->name('pembina-ekskul.destroy');

        Route::get(
            '/jadwal-final',
            [JadwalFinalController::class, 'index']
        )->name('jadwal-final.index');
        Route::post(
            '/jadwal-final/export',
            [JadwalFinalController::class, 'export']
        )->name('jadwal-final.export');

        Route::get(
            '/datakepegawaian/approval',
            [DataKepegawaianApprovalController::class, 'index']
        )->name('datakepegawaian.approval');

        Route::get(
            '/datakepegawaian/{id}',
            [DataKepegawaianApprovalController::class, 'show']
        )->name('datakepegawaian.show');

        Route::patch(
            '/datakepegawaian/{id}/approve',
            [DataKepegawaianApprovalController::class, 'approve']
        )->name('datakepegawaian.approve');

        Route::patch(
            '/datakepegawaian/{id}/reject',
            [DataKepegawaianApprovalController::class, 'reject']
        )->name('datakepegawaian.reject');

        Route::get('/dokumen/surat', [DokumenSuperAdminController::class, 'index'])
    ->name('dokumen.surat');

    Route::get(
    '/pengajuan-surat',
    [ValidasiSuratController::class, 'index']
)->name('pengajuan-surat.index');

Route::get(
    '/pengajuan-surat/{id}',
    [ValidasiSuratController::class, 'show']
)->name('pengajuan-surat.show');

Route::patch(
    '/pengajuan-surat/{id}/validasi',
    [ValidasiSuratController::class, 'validasi']
)->name('pengajuan.validasi');

Route::patch(
    '/pengajuan-surat/{id}/tolak',
    [ValidasiSuratController::class, 'tolak']
)->name('pengajuan.tolak');

Route::resource(
    'nomor-surat',
    NomorSuratController::class
);

Route::post(
    '/nomor-surat/generate',
    [NomorSuratController::class, 'generate']
)->name('nomor-surat.generate');
Route::get(
    '/nomor-surat',
    [NomorSuratController::class, 'index']
)->name('nomor-surat.index');

        Route::get(
            '/monitoring-bentrok',
            function () {
                return 'Monitoring Bentrok';
            }
        )->name('monitoring-bentrok.index');

        Route::get(
            '/monitoring-ekskul',
            [MonitoringEkskulController::class, 'index']
        )->name('monitoring-ekskul.index');

        Route::get(
            '/monitoring-ekskul/{ekskul}',
            [MonitoringEkskulController::class, 'show']
        )->name('monitoring-ekskul.show');

        Route::post(
            '/monitoring-ekskul/{ekskul}/approve',
            [MonitoringEkskulController::class, 'approve']
        )->name('monitoring-ekskul.approve');

        Route::post(
            '/monitoring-ekskul/{ekskul}/reject',
            [MonitoringEkskulController::class, 'reject']
        )->name('monitoring-ekskul.reject');

        Route::post(
            '/monitoring-ekskul/{ekskul}/publish',
            [MonitoringEkskulController::class, 'publish']
        )->name('monitoring-ekskul.publish');

        Route::get(
            '/jadwal-final-ekskul',
            [JadwalFinalEkskulController::class, 'index']
        )->name('jadwal-final-ekskul.index');

        Route::get(
            '/siswa',
            [SiswaController::class, 'index']
        )->name('siswa.index');

        Route::get(
            '/siswa/{siswa}',
            [SiswaController::class, 'show']
        )->name('siswa.show');

        Route::prefix('monitoring-kenaikan-kelas')
            ->name('monitoring-kenaikan.')
            ->group(function () {

                Route::get(
                    '/',
                    [KenaikanKelasController::class, 'index']
                )->name('index');

                Route::get(
                    '/{kelas}',
                    [KenaikanKelasController::class, 'show']
                )->name('show');

                Route::patch(
                    '/{kelas}/reject',
                    [KenaikanKelasController::class, 'reject']
                )->name('reject');

                Route::patch(
                    '/{kelas}/approve',
                    [KenaikanKelasController::class, 'approve']
                )->name('approve');

            });

    });
