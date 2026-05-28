<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\AbsensiSiswa;
use App\Models\NilaiSiswa;
use App\Models\PengajuanJadwal;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | SISWA LOGIN
        |--------------------------------------------------------------------------
        */

        $siswa = auth()->user()->siswa;

        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        if (!$siswa) {

            abort(404);

        }

        /*
        |--------------------------------------------------------------------------
        | TAHUN AJARAN AKTIF
        |--------------------------------------------------------------------------
        */

        $tahunId = current_tahun_id();

        /*
        |--------------------------------------------------------------------------
        | HARI INI
        |--------------------------------------------------------------------------
        */

        $hariIni = Carbon::now()
            ->locale('id')
            ->translatedFormat('l');

        /*
        |--------------------------------------------------------------------------
        | KONVERSI HARI
        |--------------------------------------------------------------------------
        */

        $hariMap = [

            'Senin' => 1,
            'Selasa' => 2,
            'Rabu' => 3,
            'Kamis' => 4,
            'Jumat' => 5,
            'Sabtu' => 6,

        ];

        $hariId = $hariMap[$hariIni] ?? null;

        /*
        |--------------------------------------------------------------------------
        | JADWAL HARI INI
        |--------------------------------------------------------------------------
        */

        $jadwalHariIni = collect();

        if ($siswa->kelas_id && $hariId) {

            $jadwalHariIni = PengajuanJadwal::with([

                    'mapel',
                    'guru',
                    'jam'

                ])
                ->where(
                    'kelas_id',
                    $siswa->kelas_id
                )
                ->where(
                    'hari_id',
                    $hariId
                )
                ->where(
                    'tahun_ajaran_id',
                    $tahunId
                )
                ->whereIn(
                    'status',
                    ['approved', 'published']
                )
                ->orderBy('jam_pelajaran_id')
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | NILAI SISWA
        |--------------------------------------------------------------------------
        */

        $nilai = NilaiSiswa::with('mapel')
            ->where(
                'siswa_id',
                $siswa->id
            )
            ->where(
                'tahun_ajaran_id',
                $tahunId
            )
            ->get();

        $rataNilai = round(
            $nilai->avg('nilai_akhir') ?? 0
        );

        /*
        |--------------------------------------------------------------------------
        | ABSENSI SISWA
        |--------------------------------------------------------------------------
        */

        $absensi = AbsensiSiswa::where(
                'siswa_id',
                $siswa->id
            )
            ->where(
                'tahun_ajaran_id',
                $tahunId
            )
            ->get();

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

        $totalAbsensi = $absensi->count();

        $persentaseKehadiran = $totalAbsensi > 0
            ? round(($hadir / $totalAbsensi) * 100)
            : 0;

        /*
        |--------------------------------------------------------------------------
        | TOTAL MAPEL
        |--------------------------------------------------------------------------
        */

        $totalMapel = $nilai
            ->pluck('mapel_id')
            ->unique()
            ->count();

        /*
        |--------------------------------------------------------------------------
        | STATUS AKADEMIK
        |--------------------------------------------------------------------------
        */

        $statusAkademik = 'Aktif';

        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'siswa.dashboard.index',
            compact(

                'siswa',
                'jadwalHariIni',
                'rataNilai',
                'hadir',
                'izin',
                'sakit',
                'alpha',
                'persentaseKehadiran',
                'totalMapel',
                'statusAkademik'

            )
        );
    }
}