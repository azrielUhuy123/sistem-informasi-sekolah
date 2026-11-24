<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserApproved
{
    public function handle(Request $request, Closure $next)
    {
        $authenticatedUser = $request->user();

        // Belum login → biarkan auth middleware yang handle
        if (! $authenticatedUser) {
            return redirect()->route('login');
        }

        $user = User::query()->find($authenticatedUser->getAuthIdentifier());
        if (! $user) {
            auth()->logout();

            return redirect()->route('login');
        }

        Auth::setUser($user);
        $request->setUserResolver(fn () => $user);

        // Semua role, termasuk Superadmin, wajib aktif dan disetujui.
        if ($user->approval_status !== 'approved') {
            auth()->logout();

            return redirect()->route('login')
                ->withErrors([
                    'email' => 'Akun belum disetujui oleh Kepala Sekolah',
                ]);
        }

        if ($user->must_change_password) {
            auth()->logout();

            return redirect()->route('login')
                ->withErrors([
                    'email' => 'Akun harus mengatur password melalui tautan aktivasi.',
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
