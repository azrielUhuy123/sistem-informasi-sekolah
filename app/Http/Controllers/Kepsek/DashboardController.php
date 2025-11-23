<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('kepsek.dashboard');
    }
}
    