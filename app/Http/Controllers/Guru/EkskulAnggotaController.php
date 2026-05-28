<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Ekskul;
use App\Models\EkskulSiswa;
use App\Models\PembinaEkskul;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class EkskulAnggotaController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | GURU LOGIN
        |--------------------------------------------------------------------------
        */

        $guru = auth()->user()->guru;

        /*
        |--------------------------------------------------------------------------
        | EKSKUL YANG DIAMPU
        |--------------------------------------------------------------------------
        */

        $pembina = PembinaEkskul::with('ekskul')
            ->where(
                'guru_id',
                $guru->id
            )
            ->first();

        abort_if(!$pembina, 403);

        $ekskul = $pembina->ekskul;

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        $search = $request->search;

        /*
        |--------------------------------------------------------------------------
        | SISWA
        |--------------------------------------------------------------------------
        */

        $siswa = Siswa::query()

            ->when($search, function ($q) use ($search) {

                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%");

            })

            ->orderBy('nama')

            ->paginate(10);

        /*
        |--------------------------------------------------------------------------
        | ANGGOTA SUDAH ADA
        |--------------------------------------------------------------------------
        */

        $anggotaIds = EkskulSiswa::where(
                'ekskul_id',
                $ekskul->id
            )
            ->pluck('siswa_id')
            ->toArray();

        return view(
            'guru.ekskul.anggota.index',
            compact(
                'ekskul',
                'siswa',
                'anggotaIds',
                'search'
            )
        );
    }

    public function store(Request $request)
    {
        
        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'siswa_id' => 'required|array'
        ]);

        /*
        |--------------------------------------------------------------------------
        | GURU LOGIN
        |--------------------------------------------------------------------------
        */

        $guru = auth()->user()->guru;

        /*
        |--------------------------------------------------------------------------
        | PEMBINA
        |--------------------------------------------------------------------------
        */

        $pembina = PembinaEkskul::where(
            'guru_id',
            $guru->id
        )->first();

        abort_if(!$pembina, 403);

        $ekskul = Ekskul::findOrFail(
            $pembina->ekskul_id
        );

        /*
        |--------------------------------------------------------------------------
        | KUOTA
        |--------------------------------------------------------------------------
        */

        $jumlahSekarang = EkskulSiswa::where(
            'ekskul_id',
            $ekskul->id
        )->count();

        $akanDitambah = count(
            $request->siswa_id
        );

        if (
            ($jumlahSekarang + $akanDitambah)
            >
            $ekskul->kuota
        ) {

            return back()->with(
                'error',
                'Kuota ekskul tidak mencukupi'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | SIMPAN
        |--------------------------------------------------------------------------
        */

        foreach ($request->siswa_id as $id) {

            EkskulSiswa::firstOrCreate(
                [
                    'ekskul_id'       => $ekskul->id,
                    'siswa_id'        => $id,
                    'tahun_ajaran_id' => $pembina->tahun_ajaran_id,
                ],
                [
                    'status' => 'aktif'
                ]
            );
            
        }

        return back()->with(
            'success',
            'Anggota berhasil ditambahkan'
        );
    }
    public function daftar(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | GURU LOGIN
        |--------------------------------------------------------------------------
        */

        $guru = auth()->user()->guru;

        /*
        |--------------------------------------------------------------------------
        | PEMBINA
        |--------------------------------------------------------------------------
        */

        $pembina = PembinaEkskul::with('ekskul')
            ->where(
                'guru_id',
                $guru->id
            )
            ->first();

        abort_if(!$pembina, 403);

        $ekskul = $pembina->ekskul;

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        $search = $request->search;

        /*
        |--------------------------------------------------------------------------
        | ANGGOTA
        |--------------------------------------------------------------------------
        */

        $anggota = EkskulSiswa::with('siswa')

            ->where(
                'ekskul_id',
                $ekskul->id
            )

            ->when($search, function ($q) use ($search) {

                $q->whereHas(
                    'siswa',
                    function ($siswa) use ($search) {

                        $siswa->where(
                            'nama',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'nis',
                            'like',
                            "%{$search}%"
                        );

                    }
                );

            })

            ->latest()

            ->paginate(15);

        return view(
            'guru.ekskul.anggota.daftar',
            compact(
                'ekskul',
                'anggota',
                'search'
            )
        );
    }
    public function destroy($id)
    {
        $anggota = EkskulSiswa::findOrFail($id);

        $anggota->delete();

        return back()->with(
            'success',
            'Anggota berhasil dikeluarkan'
        );
    }
}