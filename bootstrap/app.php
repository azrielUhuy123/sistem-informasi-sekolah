<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'forbid.superadmin' => \App\Http\Middleware\ForbidSuperadminTarget::class,
            'approved' => \App\Http\Middleware\EnsureUserApproved::class,
            'tahun.aktif' => \App\Http\Middleware\EnsureTahunAjaranAktif::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //

    })->create();
