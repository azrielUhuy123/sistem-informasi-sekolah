<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\NilaiSiswa;

class NilaiController extends Controller
{
    public function index()
    {
        $siswa = auth()->user()->siswa;

        $nilai = NilaiSiswa::with('mapel')
            ->where(
                'siswa_id',
                $siswa->id
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->get();

        return view(
            'siswa.nilai.index',
            compact(
                'siswa',
                'nilai'
            )
        );
    }
}