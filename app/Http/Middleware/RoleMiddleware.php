<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $roles)
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        // superadmin selalu lolos
        if ($request->user()->role === 'superadmin') {
            return $next($request);
        }

        $allowedRoles = array_map('trim', explode(',', $roles));

        if (! in_array($request->user()->role, $allowedRoles)) {
            return redirect($request->user()->getDashboardUrl());
        }

        return $next($request);
    }
}
