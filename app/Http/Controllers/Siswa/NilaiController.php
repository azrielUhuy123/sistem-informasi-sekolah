<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\NilaiSiswa;

class NilaiController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | SISWA LOGIN
        |--------------------------------------------------------------------------
        */

        $siswa = auth()->user()->siswa;

        /*
        |--------------------------------------------------------------------------
        | NILAI SISWA
        |--------------------------------------------------------------------------
        */

        $nilai = NilaiSiswa::with([

                'mapel',
                'guru'

            ])
            ->where(
                'siswa_id',
                $siswa->id
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->orderBy('mapel_id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'siswa.nilai.index',
            compact(

                'siswa',
                'nilai'

            )
        );
    }
}
