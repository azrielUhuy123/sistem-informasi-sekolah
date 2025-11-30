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
                return redirect('/kepsek/dashboard');

            case 'guru':
                return redirect('/guru/dashboard');

            case 'tu':
                return redirect('/tu/dashboard');

            case 'murid':
                return redirect('/murid/dashboard');

            case 'spmb':
                return redirect('/spmb/dashboard');

            default:
                return redirect('/dashboard');
        }
    }
}
