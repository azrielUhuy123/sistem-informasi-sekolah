<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\UserController;
use App\Http\Controllers\SuperAdmin\UserApprovalController;
use App\Http\Controllers\Superadmin\MonitoringJadwalController;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\HariSekolah;
use App\Models\JamPelajaran;

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

        Route::get(
            '/monitoring-bentrok',
            function () {
                return 'Monitoring Bentrok';
            }
        )->name('monitoring-bentrok.index');

        Route::get(
            '/jadwal-final',
            function () {
                return 'Jadwal Final';
            }
        )->name('jadwal-final.index');

        Route::get(
            '/akademik/mapel',
            function () {
                return 'Mapel';
            }
        )->name('mapel.index');

        Route::get(
            '/akademik/guru',
            function () {
                return 'Guru';
            }
        )->name('guru.index');

        Route::get(
            '/akademik/kelas',
            function () {
                return 'Kelas';
            }
        )->name('kelas.index');

    });
