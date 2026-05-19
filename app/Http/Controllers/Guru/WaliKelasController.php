<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\WaliKelas;
use App\Models\Siswa;
use App\Models\NilaiSiswa;
use App\Models\AbsensiSiswa;
use App\Models\PerkembanganSiswa;

class WaliKelasController extends Controller
{
    public function kelasSaya()
    {
        $guru = auth()->user()->guru;

        if (!$guru) {

            abort(403);

        }

        $waliKelas = WaliKelas::with([

            'kelas',
        ])
        ->where(
            'guru_id',
            $guru->id
        )
        ->where(
            'tahun_ajaran_id',
            current_tahun_id()
        )
        ->first();

        if (!$waliKelas) {

            return view(
                'guru.kelas-saya.empty'
            );

        }

        $kelas = $waliKelas->kelas;

        $penempatan = $kelas
            ->siswaAktif()
            ->with('siswa')
            ->get();

        return view(
            'guru.kelas-saya.index',
            compact(
                'waliKelas',
                'kelas',
                'penempatan'
            )
        );
    }
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
            'guru.kelas-saya.show',
            compact(
                'siswa',
                'nilai',
                'hadir',
                'izin',
                'sakit',
                'alpha',
                'perkembangan'
            )
        );
    }
}