<?php

namespace App\Http\Controllers\Elearning;

use App\Http\Controllers\Controller;
use App\Models\Elearning;
use App\Models\ElearningKomentar;
use App\Models\ElearningKomentarLampiran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ElearningKomentarController extends Controller
{
    public function store(
        Request $request,
        $elearning
    )
    {
        $user = Auth::user();
        $siswa = $user->siswa;
        $kelasAktif = $siswa?->penempatanAktif?->kelas;

        if (!$kelasAktif) {
            abort(403);
        }

        $forum = Elearning::findOrFail($elearning);

        if ($forum->kelas_id != $kelasAktif->id) {
            abort(403);
        }

        $request->validate([
            'isi'         => 'nullable|string',
            'lampiran.*'  => 'nullable|file|max:20480'
        ]);

        if (
            blank($request->isi)
            && !$request->hasFile('lampiran')
        ) {
            return back()->withErrors([
                'isi' => 'Komentar atau lampiran wajib diisi.'
            ]);
        }

        $komentar = ElearningKomentar::create([

            'elearning_id' => $elearning,

            'user_id' => $user->id,

            'isi' => $request->isi

        ]);

        if ($request->hasFile('lampiran')) {

            foreach ($request->file('lampiran') as $file) {

                $path = $file->store(
                    'elearning/komentar',
                    'public'
                );

                ElearningKomentarLampiran::create([

                    'komentar_id' => $komentar->id,

                    'nama_file' => $file->getClientOriginalName(),

                    'path' => $path

                ]);
            }
        }

        return back()->with(
            'success',
            'Komentar berhasil ditambahkan'
        );
    }
}