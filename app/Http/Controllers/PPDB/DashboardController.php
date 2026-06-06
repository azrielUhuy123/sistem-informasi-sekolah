<?php

namespace App\Http\Controllers\PPDB;

use App\Http\Controllers\Controller;
use App\Models\PpdbPendaftar;
use App\Models\PpdbSetting;

class DashboardController extends Controller
{
    public function index()
    {
        $total = PpdbPendaftar::count();

        $pending = PpdbPendaftar::where(
            'status',
            'pending'
        )->count();

        $diterima = PpdbPendaftar::where(
            'status',
            'diterima'
        )->count();

        $ditolak = PpdbPendaftar::where(
            'status',
            'ditolak'
        )->count();

        $kuota = PpdbSetting::latest()->first()?->kuota ?? 0;
        
        $sisaKuota = max(
            0,
            $kuota - $diterima
        );
        return view(
            'ppdb.dashboard.index',
            compact(
                'total',
                'pending',
                'diterima',
                'ditolak',
                'kuota',
                'sisaKuota'
            )
        );
    }
}