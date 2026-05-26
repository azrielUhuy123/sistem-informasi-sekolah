<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function index()
    {
        $guru = auth()->user()
            ->guru()
            ->first();

        return view('guru.profile', compact('guru'));
    }
}