<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\AbsensiSiswa;
use App\Models\GuruMapel;
use App\Models\NilaiSiswa;
use App\Models\PengajuanJadwal;
use App\Models\PerkembanganSiswa;
use App\Models\Siswa;
use App\Models\WaliKelas;
use Illuminate\Support\Facades\Auth;

class WaliKelasController extends Controller
{
    public function kelasSaya()
    {
        $guru = Auth::user()?->guru;

        if (!$guru) {

            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | CEK WALIKELAS
        |--------------------------------------------------------------------------
        */

        $waliKelas = WaliKelas::with('kelas')
            ->where(
                'guru_id',
                $guru->id
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->first();

        /*
        |--------------------------------------------------------------------------
        | JIKA WALIKELAS
        |--------------------------------------------------------------------------
        */

        if ($waliKelas) {

            $kelas = $waliKelas->kelas;

            $penempatan = $kelas
                ->siswaAktif()
                ->with('siswa')
                ->get();
            // Hitung total siswa laki-laki dan perempuan
            $totalLaki = $penempatan->where('siswa.jenis_kelamin', 'L')->count();
            $totalPerempuan = $penempatan->where('siswa.jenis_kelamin', 'P')->count();
            $totalSiswa = $penempatan->count();

            return view(
                'guru.kelas-saya.index',
                compact(
                    'waliKelas',
                    'kelas',
                    'penempatan',
                    'totalSiswa',     // Tambahkan jika dibutuhkan view
                    'totalLaki',      // <-- Tambahkan ini
                    'totalPerempuan'
                )
            );
        }

        /*
        |--------------------------------------------------------------------------
        | GURU BIDANG
        |--------------------------------------------------------------------------
        */

        $kelasDiampu = PengajuanJadwal::with([
                'kelas',
                'mapel',
            ])
            ->where(
                'guru_id',
                $guru->id
            )
            ->whereIn(
                'status',
                ['approved', 'published']
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->get()
            ->unique(function ($item) {

                return $item->kelas_id . '-' . $item->mapel_id;
            })
            ->values();

        foreach ($kelasDiampu as $item) {

            /*
            |--------------------------------------------------------------------------
            | TOTAL SISWA
            |--------------------------------------------------------------------------
            */

            $item->total_siswa = $item->kelas
                ->siswaAktif()
                ->count();

            /*
            |--------------------------------------------------------------------------
            | NILAI TERISI
            |--------------------------------------------------------------------------
            */

            $item->nilai_terisi = NilaiSiswa::where(
                    'kelas_id',
                    $item->kelas_id
                )
                ->where(
                    'mapel_id',
                    $item->mapel_id
                )
                ->where(
                    'tahun_ajaran_id',
                    current_tahun_id()
                )
                ->count();
        }
        return view(
            'guru.kelas-saya.guru-bidang',
            compact(
                'kelasDiampu'
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