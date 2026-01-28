<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;

Route::middleware(['auth','role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/users', [UserController::class, 'index'])
            ->name('users.index');

        Route::get('/users/create', [UserController::class, 'create'])
            ->name('users.create');

        Route::get('/users/{user}/edit', [UserController::class, 'edit'])
            ->middleware('forbid.superadmin')
            ->name('users.edit');

        Route::post('/users', [UserController::class, 'store'])
            ->name('users.store');

        Route::put('/users/{user}', [UserController::class, 'update'])
            ->middleware('forbid.superadmin')
            ->name('users.update');


        Route::delete('/users/{user}', [UserController::class, 'destroy'])
            ->middleware('forbid.superadmin')
            ->name('users.destroy');

        Route::patch('/users/{user}/toggle', [UserController::class, 'toggleStatus'])
            ->name('users.toggle');

    });
