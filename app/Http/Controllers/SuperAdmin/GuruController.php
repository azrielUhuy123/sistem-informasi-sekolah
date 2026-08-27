<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Guru;

class GuruController extends Controller
{
    public function index()
    {
        $gurus = Guru::with([
            'pegawai',
            'waliKelas.kelas',
            'pengampuMapel.mapel',
        ])
        ->latest()
        ->get();

        return view(
            'superadmin.guru.index',
            compact('gurus')
        );
    }

    public function show(Guru $guru)
    {
        $guru->load([
            'pegawai',
            'user',
            'waliKelas.kelas',
            'pengampuMapel.mapel',
        ]);

        $pegawai = $guru;

        return view(
            'superadmin.guru.show',
            compact('pegawai')
        );
    }
}