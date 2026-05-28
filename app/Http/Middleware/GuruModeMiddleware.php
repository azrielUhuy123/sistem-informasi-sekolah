<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class GuruModeMiddleware
{
    public function handle(
        Request $request,
        Closure $next
    ) {

        /*
        |--------------------------------------------------------------------------
        | DEFAULT MODE
        |--------------------------------------------------------------------------
        */

        if (!session()->has('guru_mode')) {

            session([
                'guru_mode' => 'akademik'
            ]);
        }

        return $next($request);
    }
}