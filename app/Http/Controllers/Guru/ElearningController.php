<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Elearning;
use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\ElearningLampiran;
use App\Models\PengampuMapel;
use App\Models\WaliKelas;
use app\Models\User;
use Illuminate\Support\Facades\Storage;

class ElearningController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $guru = auth()->user()->guru;

        /*
        |--------------------------------------------------------------------------
        | KELAS WALI
        |--------------------------------------------------------------------------
        */
        $waliKelasIds = \App\Models\WaliKelas::where(
                'guru_id',
                $guru->id
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->pluck('id');

        /*
        |--------------------------------------------------------------------------
        | PENGAMPU MAPEL
        |--------------------------------------------------------------------------
        */
        $pengampuIds = \App\Models\PengampuMapel::where(
                'guru_id',
                $guru->id
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->pluck('id');

        /*
        |--------------------------------------------------------------------------
        | FORUM YANG BOLEH DILIHAT
        |--------------------------------------------------------------------------
        */
        $forums = Elearning::with([
                'guru',
                'kelas',
                'mapel',
                'lampiran',
                'pengampu',
                'waliKelas'
            ])
            ->where(function ($query) use (
                $waliKelasIds,
                $pengampuIds
            ) {

                $query->whereIn(
                    'wali_kelas_id',
                    $waliKelasIds
                );

                $query->orWhereIn(
                    'pengampu_id',
                    $pengampuIds
                );
            })
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
        $guru = auth()->user()->guru;

        /*
        |--------------------------------------------------------------------------
        | Guru Bidang
        |--------------------------------------------------------------------------
        */

        $pengampu = PengampuMapel::with([
            'kelas',
            'mapel'
        ])
        ->where('guru_id', $guru->id)
        ->where('tahun_ajaran_id', current_tahun_id())
        ->get();

        /*
        |--------------------------------------------------------------------------
        | Wali Kelas
        |--------------------------------------------------------------------------
        */

        $waliKelas = WaliKelas::with('kelas')
            ->where('guru_id', $guru->id)
            ->where('tahun_ajaran_id', current_tahun_id())
            ->first();

        $mapelWali = collect();

        if ($waliKelas) {

            $mapelWali = $this->getMapelWali();

        }

        return view(
            'guru.elearning.create',
            compact(
                'pengampu',
                'waliKelas',
                'mapelWali'
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

            'target'    => 'required',

            'deadline'  => 'nullable|date',

            'konten'    => 'nullable',

            'lampiran.*' => 'nullable|file|max:20480'

        ]);

        $guru = auth()->user()->guru;

        $kelasId = null;
        $mapelId = null;
        $pengampuId = null;
        $waliKelasId = null;

        /*
        |--------------------------------------------------------------------------
        | FORUM WALI KELAS
        |--------------------------------------------------------------------------
        */
        if (str_starts_with($request->target, 'wali_')) {

            $mapelId = str_replace(
                'wali_',
                '',
                $request->target
            );

            $wali = WaliKelas::where(
                'guru_id',
                $guru->id
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->firstOrFail();

            $waliKelasId = $wali->id;

            $kelasId = $wali->kelas_id;
        }

                /*
        |--------------------------------------------------------------------------
        | FORUM PENGAmpu
        |--------------------------------------------------------------------------
        */
        elseif (str_starts_with($request->target, 'pengampu_')) {

            $pengampuId = str_replace(
                'pengampu_',
                '',
                $request->target
            );

            $pengampu = PengampuMapel::where(
                'id',
                $pengampuId
            )
            ->where(
                'guru_id',
                $guru->id
            )
            ->firstOrFail();

            $kelasId = $pengampu->kelas_id;

            $mapelId = $pengampu->mata_pelajaran_id;
        }

        $forum = Elearning::create([

            'guru_id'       => $guru->id,

            'pengampu_id'   => $pengampuId,

            'wali_kelas_id' => $waliKelasId,

            'kelas_id'      => $kelasId,

            'mapel_id' => $mapelId,

            'judul'         => $request->judul,

            'konten'        => $request->konten,

            'deadline'      => $request->deadline

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

    private function getMapelWali()
    {
        return MataPelajaran::whereIn(
            'nama_mapel',
            [
                'Bahasa Indonesia',
                'Matematika',
                'Pendidikan Pancasila',
                'Ilmu Pengetahuan Alam dan Sosial',
            ]
        )
        ->orderBy('nama_mapel')
        ->get();
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
        $guru = auth()->user()->guru;

        $waliKelasIds = \App\Models\WaliKelas::where(
                'guru_id',
                $guru->id
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->pluck('id');

        $pengampuIds = \App\Models\PengampuMapel::where(
                'guru_id',
                $guru->id
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->pluck('id');

        $elearning = Elearning::with([

                'guru',
                'kelas',
                'mapel',
                'lampiran',

                'komentar.user',
                'komentar.replies.user',
                'komentar.replies.lampiran',

            ])
            ->where(function ($query) use (
                $waliKelasIds,
                $pengampuIds
            ) {

                $query->whereIn(
                    'wali_kelas_id',
                    $waliKelasIds
                );

                $query->orWhereIn(
                    'pengampu_id',
                    $pengampuIds
                );
            })
            ->findOrFail($e_learning);

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
