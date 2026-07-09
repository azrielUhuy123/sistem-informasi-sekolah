<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\PengampuMapel;

class DashboardGuruBidangController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | GURU LOGIN
        |--------------------------------------------------------------------------
        */

        $guru = auth()->user()->guru;

        /*
        |--------------------------------------------------------------------------
        | TAHUN AJARAN
        |--------------------------------------------------------------------------
        */

        $tahunId = current_tahun_id();

        /*
        |--------------------------------------------------------------------------
        | TOTAL MAPEL
        |--------------------------------------------------------------------------
        */

        $totalMapel = PengampuMapel::where(
                'guru_id',
                $guru->id
            )
            ->where(
                'tahun_ajaran_id',
                $tahunId
            )
            ->count();

        /*
        |--------------------------------------------------------------------------
        | TOTAL JADWAL
        |--------------------------------------------------------------------------
        */

        $totalJadwal = PengampuMapel::where(
            'guru_id',
            $guru->id
        )
        ->where(
            'tahun_ajaran_id',
            current_tahun_id()
        )
        ->count();

        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'guru.dashboard-gurubidang.index',
            compact(
                'guru',
                'totalMapel',
                'totalJadwal'
            )
        );
    }
}