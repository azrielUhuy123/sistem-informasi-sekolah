<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\AbsensiSiswa;
use App\Models\MataPelajaran;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | SISWA LOGIN
        |--------------------------------------------------------------------------
        */

        $siswa = auth()->user()->siswa;

        /*
        |--------------------------------------------------------------------------
        | FILTER
        |--------------------------------------------------------------------------
        */

        $mapelId = $request->mapel_id;

        $bulanInput = $request->bulan;

        $bulan = $bulanInput
            ? \Carbon\Carbon::parse($bulanInput)->format('m')
            : now()->format('m');

        /*
        |--------------------------------------------------------------------------
        | DATA MAPEL
        |--------------------------------------------------------------------------
        */

        $mapels = MataPelajaran::orderBy('nama_mapel')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | QUERY ABSENSI
        |--------------------------------------------------------------------------
        */

        $absensi = AbsensiSiswa::with([

                'jadwal.mapel',
                'jadwal.guru'

            ])
            ->where(
                'siswa_id',
                $siswa->id
            )
            ->whereMonth(
                'tanggal',
                $bulan
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            );

        /*
        |--------------------------------------------------------------------------
        | FILTER MAPEL
        |--------------------------------------------------------------------------
        */

        if ($mapelId) {

            $absensi->whereHas(
                'jadwal',
                function ($q) use ($mapelId) {

                    $q->where(
                        'mapel_id',
                        $mapelId
                    );
                }
            );

        }

        /*
        |--------------------------------------------------------------------------
        | GET DATA
        |--------------------------------------------------------------------------
        */

        $absensi = $absensi
            ->latest('tanggal')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | STATISTIK
        |--------------------------------------------------------------------------
        */

        $hadir = $absensi
            ->where('status', 'hadir')
            ->count();

        $izin = $absensi
            ->where('status', 'izin')
            ->count();

        $sakit = $absensi
            ->where('status', 'sakit')
            ->count();

        $alpha = $absensi
            ->where('status', 'alpha')
            ->count();

        $total = $absensi->count();

        /*
        |--------------------------------------------------------------------------
        | PERSENTASE
        |--------------------------------------------------------------------------
        */

        $persentase = $total > 0
            ? round(($hadir / $total) * 100)
            : 0;

        /*
        |--------------------------------------------------------------------------
        | KESIMPULAN
        |--------------------------------------------------------------------------
        */

        if ($persentase >= 90) {

            $kesimpulan = 'Sangat Baik';

        } elseif ($persentase >= 75) {

            $kesimpulan = 'Baik';

        } elseif ($persentase >= 60) {

            $kesimpulan = 'Cukup';

        } else {

            $kesimpulan = 'Kurang';

        }

        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'siswa.absensi.index',
            compact(

                'absensi',
                'mapels',
                'mapelId',
                'bulan',
                'bulanInput',

                'hadir',
                'izin',
                'sakit',
                'alpha',

                'persentase',
                'kesimpulan'
            )
        );
    }
}
