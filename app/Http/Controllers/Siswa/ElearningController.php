<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Elearning;

class ElearningController extends Controller
{
    public function index()
    {
        $siswa = auth()->user()->siswa;

        $kelasAktif = $siswa->penempatanAktif?->kelas;

        if (!$kelasAktif) {

            $forums = collect();

            return view(
                'siswa.elearning.index',
                compact('forums')
            );
        }

        $forums = Elearning::with([
                'guru',
                'kelas',
                'mapel',
                'lampiran'
            ])
            ->where(
                'kelas_id',
                $kelasAktif->id
            )
            ->latest()
            ->paginate(12);

        return view(
            'siswa.elearning.index',
            compact('forums')
        );
    }

    public function show($elearning)
    {
        $siswa = auth()->user()->siswa;

        $kelasAktif = $siswa->penempatanAktif?->kelas;

        if (!$kelasAktif) {
            abort(403);
        }

        $forum = Elearning::with([
            'guru',
            'kelas',
            'mapel',
            'lampiran',
            'komentar.user',
            'komentar.replies.user'
        ])->findOrFail($elearning);

        if (
            $forum->kelas_id != $kelasAktif->id
        ) {
            abort(403);
        }

        return view(
            'siswa.elearning.show',
            compact('forum')
        );
    }
}