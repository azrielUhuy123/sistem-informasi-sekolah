<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Ekskul;
use App\Models\EkskulSiswa;

class EkskulController extends Controller
{
    public function index()
    {
        $siswa = auth()->user()->siswa;

        $ekskul = Ekskul::with('guru')
            ->where('status', 'aktif')
            ->latest()
            ->get();

        $pendaftaran = EkskulSiswa::where(
                'siswa_id',
                $siswa->id
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->pluck('ekskul_id')
            ->toArray();

        return view(
            'siswa.ekskul.index',
            compact(
                'ekskul',
                'pendaftaran'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DAFTAR
    |--------------------------------------------------------------------------
    */

    public function daftar($id)
    {
        $siswa = auth()->user()->siswa;

        $ekskul = Ekskul::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | SUDAH TERDAFTAR?
        |--------------------------------------------------------------------------
        */

        $sudah = EkskulSiswa::where(
                'siswa_id',
                $siswa->id
            )
            ->where(
                'ekskul_id',
                $ekskul->id
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->exists();

        if ($sudah) {

            return back()->with(
                'error',
                'Sudah terdaftar pada ekskul ini'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | CEK KUOTA
        |--------------------------------------------------------------------------
        */

        $jumlah = EkskulSiswa::where(
                'ekskul_id',
                $ekskul->id
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->count();

        if ($jumlah >= $ekskul->kuota) {

            return back()->with(
                'error',
                'Kuota ekskul penuh'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN
        |--------------------------------------------------------------------------
        */

        EkskulSiswa::create([

            'ekskul_id' => $ekskul->id,

            'siswa_id' => $siswa->id,

            'tahun_ajaran_id' => current_tahun_id(),

            'status' => 'aktif'

        ]);

        return back()->with(
            'success',
            'Berhasil mendaftar ekskul'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | BATALKAN
    |--------------------------------------------------------------------------
    */

    public function batal($id)
    {
        $siswa = auth()->user()->siswa;

        EkskulSiswa::where(
                'ekskul_id',
                $id
            )
            ->where(
                'siswa_id',
                $siswa->id
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->delete();

        return back()->with(
            'success',
            'Pendaftaran dibatalkan'
        );
    }
}