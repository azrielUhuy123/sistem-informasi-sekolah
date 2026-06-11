<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Elearning;
use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\ElearningLampiran;
use Illuminate\Support\Facades\Storage;

class ElearningController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $forums = Elearning::with([
            'guru',
            'kelas',
            'mapel',
            'lampiran'
        ])
        ->latest()
        ->paginate(10);

        return view(
            'guru.elearning.index',
            compact('forums')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();

        $mapel = MataPelajaran::orderBy('nama_mapel')->get();

        return view(
            'guru.elearning.create',
            compact(
                'kelas',
                'mapel'
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([

            'judul'     => 'required|string|max:255',

            'kelas_id'  => 'required|exists:kelas,id',

            'mapel_id'  => 'required|exists:mata_pelajaran,id',

            'deadline'  => 'nullable|date',

            'konten'    => 'nullable',

            'lampiran.*' => 'nullable|file|max:20480'
        ]);

        $forum = Elearning::create([

            'guru_id' => auth()->user()->guru->id,

            'kelas_id' => $request->kelas_id,

            'mapel_id' => $request->mapel_id,

            'judul' => $request->judul,

            'konten' => $request->konten,

            'deadline' => $request->deadline

        ]);

        if ($request->hasFile('lampiran')) {

            foreach ($request->file('lampiran') as $file) {

                $path = $file->store(
                    'elearning',
                    'public'
                );

                ElearningLampiran::create([

                    'elearning_id' => $forum->id,

                    'nama_file' => $file->getClientOriginalName(),

                    'path' => $path
                ]);
            }
        }

        return redirect()
            ->route('guru.e-learning.index')
            ->with(
                'success',
                'Forum berhasil dibuat'
            );
    }

    public function uploadImage(Request $request)
    {
        if ($request->hasFile('upload')) {

            $path = $request->file('upload')
                ->store(
                    'elearning/images',
                    'public'
                );

            return response()->json([
                'url' => asset(
                    'storage/' . $path
                )
            ]);
        }

        return response()->json([
            'error' => [
                'message' => 'Upload gagal'
            ]
        ], 422);
    }

    /**
     * Display the specified resource.
     */
    public function show($e_learning)
{
    $elearning = Elearning::with([
        'guru',
        'kelas',
        'mapel',
        'lampiran',
        'komentar.user'
    ])->findOrFail($e_learning);

    return view(
        'guru.elearning.show',
        compact('elearning')
    );
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Elearning $elearning)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Elearning $elearning)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Elearning $elearning)
    {
        //
    }
}
