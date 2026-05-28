<?php

namespace App\Http\Controllers\Guru\Ekskul;

use App\Http\Controllers\Controller;
use App\Models\Ekskul;
use App\Models\EkskulSiswa;
use App\Models\AbsensiEkskul;
use App\Models\PembinaEkskul;
use Illuminate\Http\Request;

class AbsensiEkskulController extends Controller
{
    public function index(Request $request)
    {
        $guru = auth()->user()->guru;

        $ekskul = Ekskul::whereHas(
            'pembina',
            fn ($q) =>
                $q->where('guru_id', $guru->id)
        )->firstOrFail();

        $tanggal =
            $request->tanggal
            ??
            now()->format('Y-m-d');

        $anggota = EkskulSiswa::with('siswa')
            ->where(
                'ekskul_id',
                $ekskul->id
            )
            ->where(
                'status',
                'aktif'
            )
            ->get();

        return view(
            'guru.ekskul.absensi.index',
            compact(
                'ekskul',
                'anggota',
                'tanggal'
            )
        );
    }
    public function store(Request $request)
    {
        $guru = auth()->user()->guru;

        $ekskul = Ekskul::whereHas(
            'pembina',
            fn ($q) =>
                $q->where('guru_id', $guru->id)
        )->firstOrFail();

        foreach (
            $request->absensi
            as
            $siswaId => $a
        ) {

            AbsensiEkskul::updateOrCreate(

                [

                    'ekskul_id' => $ekskul->id,

                    'siswa_id' => $siswaId,

                    'tanggal' => $request->tanggal,

                ],

                [

                    'status' => $a['status'],

                    'created_by' => auth()->id(),

                ]

            );
        }

        return back()->with(
            'success',
            'Absensi berhasil disimpan'
        );
    }
    public function rekap(Request $request)
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
            ->firstOrFail();

        $ekskul = $pembina->ekskul;

        /*
        |--------------------------------------------------------------------------
        | FILTER
        |--------------------------------------------------------------------------
        */

        $mode =
            $request->mode
            ??
            'bulanan';

        $bulan =
            $request->bulan
            ??
            now()->month;

        $tahun =
            $request->tahun
            ??
            now()->year;

        /*
        |--------------------------------------------------------------------------
        | AMBIL ABSENSI
        |--------------------------------------------------------------------------
        */

        $query = AbsensiEkskul::with(
                'siswa'
            )
            ->where(
                'ekskul_id',
                $ekskul->id
            );

        if ($mode == 'bulanan') {

            $query
                ->whereMonth(
                    'tanggal',
                    $bulan
                )
                ->whereYear(
                    'tanggal',
                    $tahun
                );
        }

        if ($mode == 'tahunan') {

            $query
                ->whereYear(
                    'tanggal',
                    $tahun
                );
        }

        $absensi = $query->get();

        /*
        |--------------------------------------------------------------------------
        | REKAP
        |--------------------------------------------------------------------------
        */

        $rekap = [];

        foreach ($absensi as $item) {

            $id = $item->siswa_id;

            if (!isset($rekap[$id])) {

                $rekap[$id] = [

                    'siswa' => $item->siswa,

                    'hadir' => 0,

                    'izin' => 0,

                    'sakit' => 0,

                    'alpha' => 0,

                ];
            }

            $rekap[$id][
                $item->status
            ]++;
        }

        /*
        |--------------------------------------------------------------------------
        | HITUNG PERSENTASE
        |--------------------------------------------------------------------------
        */

        foreach ($rekap as &$r) {

            $total =
                $r['hadir']
                +
                $r['izin']
                +
                $r['sakit']
                +
                $r['alpha'];

            $r['persentase'] =
                $total
                ?
                round(
                    ($r['hadir'] / $total) * 100
                )
                :
                0;

            if (
                $r['persentase']
                >=
                90
            ) {

                $r['status_keaktifan']
                    =
                    'Sangat Aktif';

            } elseif (

                $r['persentase']
                >=
                75

            ) {

                $r['status_keaktifan']
                    =
                    'Aktif';

            } elseif (

                $r['persentase']
                >=
                60

            ) {

                $r['status_keaktifan']
                    =
                    'Cukup';

            } else {

                $r['status_keaktifan']
                    =
                    'Perlu Pembinaan';

            }
        }

        /*
        |--------------------------------------------------------------------------
        | SUMMARY
        |--------------------------------------------------------------------------
        */

        $summary = [

            'hadir' =>
                $absensi
                ->where(
                    'status',
                    'hadir'
                )
                ->count(),

            'izin' =>
                $absensi
                ->where(
                    'status',
                    'izin'
                )
                ->count(),

            'sakit' =>
                $absensi
                ->where(
                    'status',
                    'sakit'
                )
                ->count(),

            'alpha' =>
                $absensi
                ->where(
                    'status',
                    'alpha'
                )
                ->count(),

        ];

        return view(
            'guru.ekskul.absensi.rekap',
            compact(

                'ekskul',
                'rekap',
                'summary',
                'bulan',
                'tahun',
                'mode'

            )
        );
    }
}
