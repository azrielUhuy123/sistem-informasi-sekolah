<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Ekskul;
use App\Models\Guru;
use App\Models\PembinaEkskul;
use Illuminate\Http\Request;

class PembinaEkskulController extends Controller
{
    public function index()
    {
        $guru = Guru::orderBy('nama')->get();

        $ekskul = Ekskul::orderBy('nama')->get();

        $pembina = PembinaEkskul::with([

                'guru',
                'ekskul'

            ])
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->latest()
            ->get();

        return view(
            'superadmin.pembina-ekskul.index',
            compact(

                'guru',
                'ekskul',
                'pembina'

            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([

            'guru_id' => 'required',

            'ekskul_id' => 'required'

        ]);

        /*
        |--------------------------------------------------------------------------
        | CEK DUPLIKAT
        |--------------------------------------------------------------------------
        */

        $exists = PembinaEkskul::where(

                'guru_id',
                $request->guru_id

            )
            ->where(

                'ekskul_id',
                $request->ekskul_id

            )
            ->where(

                'tahun_ajaran_id',
                current_tahun_id()

            )
            ->exists();

        if ($exists) {

            return back()->with(
                'error',
                'Pembina sudah terdaftar'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN
        |--------------------------------------------------------------------------
        */

        PembinaEkskul::create([

            'guru_id' => $request->guru_id,

            'ekskul_id' => $request->ekskul_id,

            'tahun_ajaran_id' => current_tahun_id()

        ]);

        return back()->with(
            'success',
            'Pembina ekskul berhasil ditambahkan'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        PembinaEkskul::findOrFail($id)
            ->delete();

        return back()->with(
            'success',
            'Pembina ekskul berhasil dihapus'
        );
    }
}