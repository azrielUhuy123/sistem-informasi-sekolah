<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\HariSekolah;
use App\Models\PengajuanJadwalEkskul;

class JadwalFinalEkskulController extends Controller
{
    public function index()
    {
        $hari = HariSekolah::orderBy(
            'urutan'
        )->get();

        $jadwal = PengajuanJadwalEkskul::with([

                'ekskul',
                'guru',
                'hari'

            ])
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->where(
                'status',
                'published'
            )
            ->get();

        $grid = [];

        foreach ($jadwal as $item) {

            $grid[
                $item->hari_id
            ][] = $item;
        }

        return view(
            'superadmin.jadwal-final-ekskul.index',
            compact(
                'hari',
                'grid'
            )
        );
    }
}