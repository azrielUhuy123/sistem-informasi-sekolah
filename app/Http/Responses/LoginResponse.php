<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Support\Facades\Auth;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = Auth::user();

        // Redirect berdasarkan role
        switch ($user->role) {
            case 'kepsek':
            case 'superadmin':
                return redirect('/superadmin/dashboard');

            case 'guru':
            case 'tu':
                return redirect($user->getDashboardUrl());

            case 'murid':
            case 'siswa':
                return redirect('/siswa/dashboard');

            default:
                return redirect('/dashboard');
        }
    }
}
