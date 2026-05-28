<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // 1️⃣ PROSES LOGIN (WAJIB DULU)
        $request->authenticate();

        // 2️⃣ BARU AMBIL USER
        $user = Auth::user();

        // 3️⃣ APPROVAL (KECUALI SUPERADMIN / KEPSEK)
        if ($user->role !== 'superadmin' && $user->approval_status !== 'approved') {
            Auth::logout();

            return back()->withErrors([
                'email' => 'Akun belum disetujui oleh Kepala Sekolah.',
            ]);
        }

        // 4️⃣ STATUS AKTIF (JIKA ADA)
        if (property_exists($user, 'is_active') && ! $user->is_active) {
            Auth::logout();

            return back()->withErrors([
                'email' => 'Akun dinonaktifkan.',
            ]);
        }

        // 5️⃣ SESSION & REDIRECT
        $request->session()->regenerate();

       return match(auth()->user()->role) {

            'tu' => redirect()->route('tu.dashboard'),

            'guru' => redirect()->route('guru.dashboard'),

            'superadmin' => redirect()->route('superadmin.dashboard'),

            'siswa' => redirect()->route('siswa.dashboard.index'),

            default => abort(403),

        };



    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
    
}
