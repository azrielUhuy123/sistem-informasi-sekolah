<?php

namespace App\Http\Controllers\Spmb;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('spmb.dashboard');
    }
}
