<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use App\Models\User;

class MonitoringController extends Controller
{
    public function index()
    {
        // ambil semua user dengan role 'guru'
        $guru = User::where('role', 'guru')->get();

        return view('kepsek.monitoring.index', compact('guru'));
    }
}
