<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Usage in routes: ->middleware('role:kepsek') or ->middleware('role:tu,guru')
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $roles (comma separated)
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $roles)
    {
        if (! $request->user()) {
            // tidak login
            return redirect()->route('login');
        }

        $allowed = array_map('trim', explode(',', $roles));

        if (! $request->user()->hasRole($allowed)) {
            // akses ditolak
            return redirect($request->user()->getDashboardUrl());
        }

        return $next($request);

        if (auth()->user()->role === 'superadmin') {
            return $next($request);
        }
    }
}