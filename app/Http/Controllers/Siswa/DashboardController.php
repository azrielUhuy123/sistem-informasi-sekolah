<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $siswa = auth()->user()->siswa;

        return view(
            'siswa.dashboard.index',
            compact('siswa')
        );
    }
}