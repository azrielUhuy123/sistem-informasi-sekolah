<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Guru\DashboardController;

Route::middleware(['auth', 'approved', 'role:guru'])
    ->prefix('guru')
    ->name('guru.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

    });
