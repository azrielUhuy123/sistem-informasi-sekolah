<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\WaliKelas;

class WaliKelasController extends Controller
{
    public function kelasSaya()
    {
        $guru = auth()->user()->guru;

        if (!$guru) {

            abort(403);

        }

        $waliKelas = WaliKelas::with([

            'kelas',
        ])
        ->where(
            'guru_id',
            $guru->id
        )
        ->where(
            'tahun_ajaran_id',
            current_tahun_id()
        )
        ->first();

        if (!$waliKelas) {

            return view(
                'guru.kelas-saya.empty'
            );

        }

        $kelas = $waliKelas->kelas;

        $penempatan = $kelas
            ->siswaAktif()
            ->with('siswa')
            ->get();

        return view(
            'guru.kelas-saya.index',
            compact(
                'waliKelas',
                'kelas',
                'penempatan'
            )
        );
    }
}