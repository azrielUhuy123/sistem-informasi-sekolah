<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;

class GatewayController extends Controller
{
    public function statistik()
    {
        return response()->json([
            'siswa'  => Siswa::count(),
            'guru'   => Guru::count(),
            'kelas' => Kelas::count(),
        ]);
    }
}