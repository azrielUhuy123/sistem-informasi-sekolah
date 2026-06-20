<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Guru;

class GuruController extends Controller
{
    public function index()
    {
        $gurus = Guru::with([
            'waliKelas.kelas',
            'mapels'
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
            'user',
            'waliKelas.kelas',
            'mapels'
        ]);

        return view(
            'superadmin.guru.show',
            compact('guru')
        );
    }
}