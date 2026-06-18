<?php

namespace App\Http\Controllers\Elearning;

use App\Http\Controllers\Controller;
use App\Models\Elearning;
use App\Models\ElearningKomentar;
use App\Models\ElearningKomentarLampiran;
use Illuminate\Http\Request;

class ElearningKomentarController extends Controller
{
    public function store(
        Request $request,
        $elearning
    )
    {
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

            'user_id' => auth()->id(),

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