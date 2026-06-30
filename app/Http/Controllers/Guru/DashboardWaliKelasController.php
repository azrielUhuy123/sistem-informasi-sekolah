<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\AbsensiSiswa;
use App\Models\NilaiSiswa;
use App\Models\PerkembanganSiswa;
use App\Models\WaliKelas;
use Illuminate\Support\Facades\Auth;

class DashboardWaliKelasController extends Controller
{
    public function index()
    {
        $guru = Auth::user()->guru;

        /*
        |--------------------------------------------------------------------------
        | WALIKELAS
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
            ->firstOrFail();

        $kelas = $waliKelas->kelas;

        /*
        |--------------------------------------------------------------------------
        | SISWA AKTIF
        |--------------------------------------------------------------------------
        */

        $penempatan = $kelas
            ->siswaAktif()
            ->with('siswa')
            ->get();

        $totalSiswa = $penempatan->count();

        /*
        |--------------------------------------------------------------------------
        | GENDER
        |--------------------------------------------------------------------------
        */

        $totalLaki = $penempatan
            ->where('siswa.jenis_kelamin', 'L')
            ->count();

        $totalPerempuan = $penempatan
            ->where('siswa.jenis_kelamin', 'P')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | ABSENSI
        |--------------------------------------------------------------------------
        */

        $siswaIds = $penempatan
            ->pluck('siswa.id');

        $totalAbsensi = AbsensiSiswa::whereIn(
                'siswa_id',
                $siswaIds
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->count();

        $hadir = AbsensiSiswa::whereIn(
                'siswa_id',
                $siswaIds
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

        $persentaseHadir = 0;

        if ($totalAbsensi > 0) {

            $persentaseHadir = round(
                ($hadir / $totalAbsensi) * 100
            );
        }

        /*
        |--------------------------------------------------------------------------
        | RATA-RATA NILAI
        |--------------------------------------------------------------------------
        */

        $rataNilai = round(

            NilaiSiswa::whereIn(
                    'siswa_id',
                    $siswaIds
                )
                ->where(
                    'tahun_ajaran_id',
                    current_tahun_id()
                )
                ->avg('nilai_akhir') ?? 0,

            2
        );

        /*
        |--------------------------------------------------------------------------
        | BELUM ADA PERKEMBANGAN
        |--------------------------------------------------------------------------
        */

        $belumPerkembangan = 0;

        foreach ($penempatan as $p) {

            $perkembangan = PerkembanganSiswa::where(
                    'siswa_id',
                    $p->siswa->id
                )
                ->where(
                    'tahun_ajaran_id',
                    current_tahun_id()
                )
                ->first();

            if (!$perkembangan) {

                $belumPerkembangan++;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | NILAI TERENDAH
        |--------------------------------------------------------------------------
        */

        $nilaiTerendah = NilaiSiswa::with('siswa')
            ->whereIn(
                'siswa_id',
                $siswaIds
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->orderBy('nilai_akhir')
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | ABSENSI TERBURUK
        |--------------------------------------------------------------------------
        */

        $alphaTerbanyak = [];

        foreach ($penempatan as $p) {

            $alpha = AbsensiSiswa::where(
                    'siswa_id',
                    $p->siswa->id
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

            $alphaTerbanyak[] = [
                'nama' => $p->siswa->nama,
                'alpha' => $alpha,
            ];
        }

        usort($alphaTerbanyak, function ($a, $b) {

            return $b['alpha'] <=> $a['alpha'];
        });

        $alphaTerbanyak = array_slice(
            $alphaTerbanyak,
            0,
            5
        );

        return view(
            'guru.dashboard-walikelas.index',
            compact(
                'kelas',
                'totalSiswa',
                'totalLaki',
                'totalPerempuan',
                'persentaseHadir',
                'rataNilai',
                'belumPerkembangan',
                'nilaiTerendah',
                'alphaTerbanyak'
            )
        );
    }
}