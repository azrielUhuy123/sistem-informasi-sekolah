<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('tu.dashboard');
    }
}
