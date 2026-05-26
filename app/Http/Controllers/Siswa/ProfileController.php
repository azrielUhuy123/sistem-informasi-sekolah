<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function index()
    {
        $siswa = auth()->user()->siswa;

        return view(
            'siswa.profile.index',
            compact('siswa')
        );
    }
}