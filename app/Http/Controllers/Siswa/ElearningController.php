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

            $mapels = collect();

            return view(
                'siswa.elearning.index',
                compact('mapels')
            );
        }

        $mapels = Elearning::with([
                'mapel',
                'guru',
                'kelas'
            ])
            ->where(
                'kelas_id',
                $kelasAktif->id
            )
            ->latest()
            ->get()
            ->groupBy('mapel_id');

        return view(
            'siswa.elearning.index',
            compact('mapels')
        );
    }

    public function mapel($mapel)
    {
        $siswa = auth()->user()->siswa;

        $kelasAktif = $siswa->penempatanAktif?->kelas;

        if (!$kelasAktif) {
            abort(403);
        }

        $forums = Elearning::with([
                'guru',
                'kelas',
                'mapel',
                'lampiran',
                'komentar'
            ])
            ->where(
                'kelas_id',
                $kelasAktif->id
            )
            ->where(
                'mapel_id',
                $mapel
            )
            ->latest()
            ->paginate(12);

        return view(
            'siswa.elearning.mapel',
            compact(
                'forums',
                'mapel'
            )
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
            'komentar.lampiran',
            'komentar.replies.user',
            'komentar.replies.lampiran'
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