<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\AbsensiSiswa;
use App\Models\NilaiSiswa;
use App\Models\PerkembanganSiswa;
use App\Models\Siswa;

class RaportController extends Controller
{
    public function show(Siswa $siswa)
    {
        /*
        |--------------------------------------------------------------------------
        | NILAI
        |--------------------------------------------------------------------------
        */

        $nilai = NilaiSiswa::with('mapel')
            ->where(
                'siswa_id',
                $siswa->id
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->get();

        /*
        |--------------------------------------------------------------------------
        | RATA-RATA
        |--------------------------------------------------------------------------
        */

        $rataRata = round(
            $nilai->avg('nilai_akhir') ?? 0,
            2
        );

        /*
        |--------------------------------------------------------------------------
        | ABSENSI
        |--------------------------------------------------------------------------
        */

        $hadir = AbsensiSiswa::where(
                'siswa_id',
                $siswa->id
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->where(
                'status',
                'hadir'
            )
            ->count();

        $izin = AbsensiSiswa::where(
                'siswa_id',
                $siswa->id
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->where(
                'status',
                'izin'
            )
            ->count();

        $sakit = AbsensiSiswa::where(
                'siswa_id',
                $siswa->id
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->where(
                'status',
                'sakit'
            )
            ->count();

        $alpha = AbsensiSiswa::where(
                'siswa_id',
                $siswa->id
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->where(
                'status',
                'alpha'
            )
            ->count();

        /*
        |--------------------------------------------------------------------------
        | PERKEMBANGAN
        |--------------------------------------------------------------------------
        */

        $perkembangan = PerkembanganSiswa::where(
                'siswa_id',
                $siswa->id
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->latest()
            ->first();

        return view(
            'guru.raport.show',
            compact(
                'siswa',
                'nilai',
                'rataRata',
                'hadir',
                'izin',
                'sakit',
                'alpha',
                'perkembangan'
            )
        );
    }
}