<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\UserController;
use App\Http\Controllers\SuperAdmin\UserApprovalController;

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



    });
