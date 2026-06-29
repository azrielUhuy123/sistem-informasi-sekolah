<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Dokumen;
class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function index()
    {
        return view('tu.dashboard', [

            'guru'     => Guru::count(),
            'siswa'    => Siswa::count(),
            'dokumen'  => Dokumen::count(),
            'akun'     => User::count(),

            // Chart
            'guruAktif'     => User::where('role','guru')
                                    ->where('is_active',1)
                                    ->count(),

            'guruNonaktif'  => User::where('role','guru')
                                    ->where('is_active',0)
                                    ->count(),

            'siswaAktif'    => User::where('role','siswa')
                                    ->where('is_active',1)
                                    ->count(),

            'siswaNonaktif' => User::where('role','siswa')
                                    ->where('is_active',0)
                                    ->count(),

            'tuAktif'       => User::where('role','tu')
                                    ->where('is_active',1)
                                    ->count(),

            'tuNonaktif'    => User::where('role','tu')
                                    ->where('is_active',0)
                                    ->count(),

            'superadminAktif'   => User::where('role','superadmin')
                                    ->where('is_active',1)
                                    ->count(),

            'superadminNonaktif'=> User::where('role','superadmin')
                                    ->where('is_active',0)
                                    ->count(),

        ]);
    }
}