<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('superadmin.dashboard', [
            'totalUser'   => User::count(),
            'pendingUser' => User::where('approval_status', 'pending')->count(),
            'activeUser'  => User::where('is_active', 1)->count(),
        ]);
    }
}
