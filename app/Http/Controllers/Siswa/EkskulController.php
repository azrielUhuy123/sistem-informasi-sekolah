<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;

use App\Models\Ekskul;
use App\Models\EkskulSiswa;
use App\Models\AbsensiEkskul;

class EkskulController extends Controller
{
    public function index()
    {
        $siswa = auth()->user()->siswa;

        $ekskul = EkskulSiswa::with([

                'ekskul',
                'ekskul.pembina.guru'

            ])
            ->where(
                'siswa_id',
                $siswa->id
            )
            ->where(
                'status',
                'aktif'
            )
            ->get();

        return view(
            'siswa.ekskul.index',
            compact(
                'ekskul'
            )
        );
    }

    public function show(Ekskul $ekskul)
{
    $siswa = auth()->user()->siswa;

    $anggota = EkskulSiswa::where(
            'ekskul_id',
            $ekskul->id
        )
        ->where(
            'status',
            'aktif'
        )
        ->count();

    $totalPertemuan = AbsensiEkskul::where(
            'ekskul_id',
            $ekskul->id
        )
        ->distinct('tanggal')
        ->count();

    $hadir = AbsensiEkskul::where(
            'ekskul_id',
            $ekskul->id
        )
        ->where(
            'siswa_id',
            $siswa->id
        )
        ->where(
            'status',
            'hadir'
        )
        ->count();

    $persentase = $totalPertemuan > 0
        ? round(
            ($hadir / $totalPertemuan) * 100
        )
        : 0;

    return view(
        'siswa.ekskul.detail',
        compact(
            'ekskul',
            'anggota',
            'totalPertemuan',
            'hadir',
            'persentase'
        )
    );
}

    public function absensi(Ekskul $ekskul)
{
    $siswa = auth()->user()->siswa;

    $absensi = AbsensiEkskul::where(
            'ekskul_id',
            $ekskul->id
        )
        ->where(
            'siswa_id',
            $siswa->id
        )
        ->latest('tanggal')
        ->paginate(15);

    $hadir = AbsensiEkskul::where(
            'ekskul_id',
            $ekskul->id
        )
        ->where(
            'siswa_id',
            $siswa->id
        )
        ->where(
            'status',
            'hadir'
        )
        ->count();

    $izin = AbsensiEkskul::where(
            'ekskul_id',
            $ekskul->id
        )
        ->where(
            'siswa_id',
            $siswa->id
        )
        ->where(
            'status',
            'izin'
        )
        ->count();

    $sakit = AbsensiEkskul::where(
            'ekskul_id',
            $ekskul->id
        )
        ->where(
            'siswa_id',
            $siswa->id
        )
        ->where(
            'status',
            'sakit'
        )
        ->count();

    $alpa = AbsensiEkskul::where(
            'ekskul_id',
            $ekskul->id
        )
        ->where(
            'siswa_id',
            $siswa->id
        )
        ->where(
            'status',
            'alpa'
        )
        ->count();

    return view(
        'siswa.ekskul.absensi',
        compact(
            'ekskul',
            'absensi',
            'hadir',
            'izin',
            'sakit',
            'alpa'
        )
    );
}
}