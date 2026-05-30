<?php

namespace App\Http\Controllers\Guru\Ekskul;

use App\Http\Controllers\Controller;
use App\Models\CatatanPembinaanEkskul;
use App\Models\EkskulSiswa;
use App\Models\PembinaEkskul;
use Illuminate\Http\Request;

class CatatanPembinaanController extends Controller
{
    public function index()
    {
        $guru = auth()->user()->guru;

        $pembina = PembinaEkskul::with(
                'ekskul'
            )
            ->where(
                'guru_id',
                $guru->id
            )
            ->firstOrFail();

        $anggota = EkskulSiswa::with(
                'siswa'
            )
            ->where(
                'ekskul_id',
                $pembina->ekskul_id
            )
            ->where(
                'status',
                'aktif'
            )
            ->paginate(15);

        return view(
            'guru.ekskul.catatan.index',
            [

                'ekskul' => $pembina->ekskul,

                'anggota' => $anggota

            ]
        );
    }

    public function store(Request $request)
    {
        $request->validate([

            'ekskul_id' => 'required',

            'siswa_id' => 'required',

            'kehadiran' => 'nullable',

            'partisipasi' => 'nullable',

            'perkembangan' => 'nullable',

            'catatan' => 'nullable',

        ]);

        CatatanPembinaanEkskul::updateOrCreate(

            [

                'ekskul_id' => $request->ekskul_id,

                'siswa_id' => $request->siswa_id,

                'tahun_ajaran_id' => current_tahun_id(),

            ],

            [

                'kehadiran' => $request->kehadiran,

                'partisipasi' => $request->partisipasi,

                'perkembangan' => $request->perkembangan,

                'catatan' => $request->catatan,

            ]

        );

        return back()->with(

            'success',

            'Catatan pembinaan berhasil disimpan'

        );
    }
}