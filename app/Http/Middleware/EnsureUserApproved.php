<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserApproved
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // Belum login → biarkan auth middleware yang handle
        if (! $user) {
            return redirect()->route('login');
        }

        // Superadmin selalu lolos
        if ($user->role === 'superadmin') {
            return $next($request);
        }

        // Belum di-approve
        if ($user->approval_status !== 'approved') {
            auth()->logout();

            return redirect()->route('login')
                ->withErrors([
                    'email' => 'Akun belum disetujui oleh Kepala Sekolah',
                ]);
        }

        // Akun nonaktif
        if (! $user->is_active) {
            auth()->logout();

            return redirect()->route('login')
                ->withErrors([
                    'email' => 'Akun sedang dinonaktifkan',
                ]);
        }

        return $next($request);
    }
}
