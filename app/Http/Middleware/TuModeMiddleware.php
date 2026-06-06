<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TuModeMiddleware
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        if (
            ! session()->has('tu_mode')
        ) {

            session([
                'tu_mode' => 'operasional'
            ]);

        }

        return $next($request);
    }
}