<?php

namespace App\Http\Controllers\Murid;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('murid.dashboard');
    }
}
