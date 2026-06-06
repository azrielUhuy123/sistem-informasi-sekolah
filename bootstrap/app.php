<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        then: function () {
            Route::middleware('web')
                ->group(base_path('routes/tu.php'));

            Route::middleware('web')
                ->group(base_path('routes/guru.php'));
                
            Route::middleware('web')
                ->group(base_path('routes/superadmin.php'));
            
            Route::middleware('web')
            ->group(base_path('routes/siswa.php'));
            
            Route::middleware('web')
                ->group(
                    base_path('routes/ppdb-public.php')
                );
        },
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'forbid.superadmin' => \App\Http\Middleware\ForbidSuperadminTarget::class,
            'approved' => \App\Http\Middleware\EnsureUserApproved::class,
            'tahun.aktif' => \App\Http\Middleware\EnsureTahunAjaranAktif::class,
            'guru.mode' => \App\Http\Middleware\GuruModeMiddleware::class,
            'tu.mode' => \App\Http\Middleware\TuModeMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //

    })->create();
