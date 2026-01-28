<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class ForbidSuperadminTarget
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()->role !== 'admin') {
            return $next($request);
        }

        // ambil parameter user dari route (model binding)
        $user = $request->route('user');

        if ($user instanceof User && $user->role === 'superadmin') {
            abort(403, 'Tidak boleh memodifikasi Super Admin');
        }

        return $next($request);
    }
}

