<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\PembinaEkskul;
use App\Models\Ekskul;
use App\Models\EkskulSiswa;
use App\Models\JurnalEkskul;
use App\Models\CatatanPembinaanEkskul;
use App\Models\Siswa;
use Illuminate\Http\Request;

class EkskulController extends Controller
{
   public function dashboard()
    {
        /*
        |--------------------------------------------------------------------------
        | GURU LOGIN
        |--------------------------------------------------------------------------
        */

        $guru = auth()->user()->guru;

        /*
        |--------------------------------------------------------------------------
        | EKSKUL YANG DIBINA
        |--------------------------------------------------------------------------
        */

        $ekskuls = PembinaEkskul::with('ekskul')
            ->where(
                'guru_id',
                $guru->id
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->get();

        $ekskulIds = $ekskuls
            ->pluck('ekskul_id');

        /*
        |--------------------------------------------------------------------------
        | STATISTIK
        |--------------------------------------------------------------------------
        */

        $totalEkskul = $ekskuls->count();

        $totalAnggota = EkskulSiswa::whereIn(
                'ekskul_id',
                $ekskulIds
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->count();

        $totalJurnal = JurnalEkskul::whereIn(
                'ekskul_id',
                $ekskulIds
            )
            ->count();

        $totalCatatan = CatatanPembinaanEkskul::whereIn(
                'ekskul_id',
                $ekskulIds
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->count();

        /*
        |--------------------------------------------------------------------------
        | DATA TERBARU
        |--------------------------------------------------------------------------
        */

        $jurnalTerbaru = JurnalEkskul::with('ekskul')
            ->whereIn(
                'ekskul_id',
                $ekskulIds
            )
            ->latest()
            ->take(5)
            ->get();

        $catatanTerbaru = CatatanPembinaanEkskul::whereIn(
                'ekskul_id',
                $ekskulIds
            )
            ->latest()
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'guru.ekskul.dashboard',
            compact(

                'guru',
                'ekskuls',

                'totalEkskul',
                'totalAnggota',
                'totalJurnal',
                'totalCatatan',

                'jurnalTerbaru',
                'catatanTerbaru'

            )
        );
    }

    public function anggota(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | GURU LOGIN
        |--------------------------------------------------------------------------
        */

        $guru = auth()->user()->guru;

        /*
        |--------------------------------------------------------------------------
        | EKSKUL YANG DIPEGANG
        |--------------------------------------------------------------------------
        */

        $ekskuls = PembinaEkskul::with(
                'ekskul'
            )
            ->where(
                'guru_id',
                $guru->id
            )
            ->get();

        /*
        |--------------------------------------------------------------------------
        | FILTER EKSKUL
        |--------------------------------------------------------------------------
        */

        $ekskulId = $request->ekskul_id;

        /*
        |--------------------------------------------------------------------------
        | SISWA AKTIF
        |--------------------------------------------------------------------------
        */

        $siswa = Siswa::orderBy('nama')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | ANGGOTA EKSKUL
        |--------------------------------------------------------------------------
        */

        $anggota = collect();

        if ($ekskulId) {

            $anggota = EkskulSiswa::with([

                    'siswa',
                    'ekskul'

                ])
                ->where(
                    'ekskul_id',
                    $ekskulId
                )
                ->where(
                    'tahun_ajaran_id',
                    current_tahun_id()
                )
                ->latest()
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'guru.ekskul.anggota',
            compact(

                'ekskuls',
                'ekskulId',
                'siswa',
                'anggota'

            )
        );
    }
    public function storeAnggota(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'ekskul_id' => 'required',

            'siswa_id' => 'required'

        ]);

        /*
        |--------------------------------------------------------------------------
        | DATA EKSKUL
        |--------------------------------------------------------------------------
        */

        $ekskul = Ekskul::findOrFail(
            $request->ekskul_id
        );

        /*
        |--------------------------------------------------------------------------
        | CEK DUPLIKAT
        |--------------------------------------------------------------------------
        */

        $exists = EkskulSiswa::where(
                'ekskul_id',
                $request->ekskul_id
            )
            ->where(
                'siswa_id',
                $request->siswa_id
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->exists();

        if ($exists) {

            return back()->with(
                'error',
                'Siswa sudah menjadi anggota'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | CEK KUOTA
        |--------------------------------------------------------------------------
        */

        $jumlah = EkskulSiswa::where(
                'ekskul_id',
                $request->ekskul_id
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

            'ekskul_id' => $request->ekskul_id,

            'siswa_id' => $request->siswa_id,

            'tahun_ajaran_id' => current_tahun_id(),

            'status' => 'aktif'

        ]);

        return back()->with(
            'success',
            'Anggota berhasil ditambahkan'
        );
    }
    public function destroyAnggota($id)
    {
        EkskulSiswa::findOrFail($id)
            ->delete();

        return back()->with(
            'success',
            'Anggota berhasil dihapus'
        );
    }
}