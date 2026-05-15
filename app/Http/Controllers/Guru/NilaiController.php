<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\AbsensiSiswa;
use App\Models\Kelas;
use App\Models\NilaiSiswa;
use App\Models\PengajuanJadwal;
use App\Models\WaliKelas;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    public function index(Request $request)
    {
        $guru = auth()->user()->guru;

        /*
        |--------------------------------------------------------------------------
        | CEK APAKAH WALI KELAS
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
        | JIKA WALI KELAS
        |--------------------------------------------------------------------------
        */

        if ($waliKelas) {

            $kelas = $waliKelas->kelas;

            /*
            |--------------------------------------------------------------------------
            | AMBIL MAPEL YANG DIAJAR DI KELAS ITU
            |--------------------------------------------------------------------------
            */

            $mapels = PengajuanJadwal::with('mapel')
                ->where(
                    'guru_id',
                    $guru->id
                )
                ->where(
                    'kelas_id',
                    $kelas->id
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
                ->pluck('mapel')
                ->unique('id')
                ->values();

            /*
            |--------------------------------------------------------------------------
            | DEFAULT MAPEL
            |--------------------------------------------------------------------------
            */

            $mapelId = $request->mapel_id
                ?? optional($mapels->first())->id;

            /*
            |--------------------------------------------------------------------------
            | SISWA
            |--------------------------------------------------------------------------
            */

            $penempatan = $kelas
                ->siswaAktif()
                ->with('siswa')
                ->get();

            /*
            |--------------------------------------------------------------------------
            | LOAD NILAI EXISTING
            |--------------------------------------------------------------------------
            */

            foreach ($penempatan as $p) {

                /*
                |--------------------------------------------------------------------------
                | NILAI EXISTING
                |--------------------------------------------------------------------------
                */

                $nilai = NilaiSiswa::where(
                        'siswa_id',
                        $p->siswa->id
                    )
                    ->where(
                        'kelas_id',
                        $kelas->id
                    )
                    ->where(
                        'mapel_id',
                        $mapelId
                    )
                    ->where(
                        'tahun_ajaran_id',
                        current_tahun_id()
                    )
                    ->first();

                $p->nilai_existing = $nilai;

                /*
                |--------------------------------------------------------------------------
                | PERSENTASE KEHADIRAN
                |--------------------------------------------------------------------------
                */

                $total = AbsensiSiswa::where(
                        'siswa_id',
                        $p->siswa->id
                    )
                    ->where(
                        'tahun_ajaran_id',
                        current_tahun_id()
                    )
                    ->count();

                $hadir = AbsensiSiswa::where(
                        'siswa_id',
                        $p->siswa->id
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

                $persen = 0;

                if ($total > 0) {

                    $persen = round(
                        ($hadir / $total) * 100
                    );
                }

                $p->persentase_kehadiran = $persen;
            }

            return view(
                'guru.nilai.index',
                compact(
                    'kelas',
                    'mapels',
                    'mapelId',
                    'penempatan',
                    'waliKelas'
                )
            );
        }

        /*
        |--------------------------------------------------------------------------
        | GURU BIDANG
        |--------------------------------------------------------------------------
        */

        $kelasId = $request->kelas_id;

        $mapelId = $request->mapel_id;

        $jadwalGuru = PengajuanJadwal::with([
                'kelas',
                'mapel'
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
            ->get();

        $kelasList = $jadwalGuru
            ->pluck('kelas')
            ->unique('id')
            ->values();

        $mapels = collect();

        if ($kelasId) {

            $mapels = $jadwalGuru
                ->where(
                    'kelas_id',
                    $kelasId
                )
                ->pluck('mapel')
                ->unique('id')
                ->values();
        }

        $penempatan = collect();

        $jadwalValid = PengajuanJadwal::where(
                'guru_id',
                $guru->id
            )
            ->where(
                'kelas_id',
                $kelasId
            )
            ->where(
                'mapel_id',
                $mapelId
            )
            ->whereIn(
                'status',
                ['approved', 'published']
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->exists();

        if (
            $kelasId
            &&
            $mapelId
            &&
            $jadwalValid
        ) {

            $kelas = Kelas::find($kelasId);

            $penempatan = $kelas
                ->siswaAktif()
                ->with('siswa')
                ->get();

            /*
            |--------------------------------------------------------------------------
            | LOAD NILAI EXISTING
            |--------------------------------------------------------------------------
            */

            foreach ($penempatan as $p) {

                /*
                |--------------------------------------------------------------------------
                | NILAI EXISTING
                |--------------------------------------------------------------------------
                */

                $nilai = NilaiSiswa::where(
                        'siswa_id',
                        $p->siswa->id
                    )
                    ->where(
                        'kelas_id',
                        $kelas->id
                    )
                    ->where(
                        'mapel_id',
                        $mapelId
                    )
                    ->where(
                        'tahun_ajaran_id',
                        current_tahun_id()
                    )
                    ->first();

                $p->nilai_existing = $nilai;

                /*
                |--------------------------------------------------------------------------
                | PERSENTASE KEHADIRAN
                |--------------------------------------------------------------------------
                */

                $total = AbsensiSiswa::where(
                        'siswa_id',
                        $p->siswa->id
                    )
                    ->where(
                        'tahun_ajaran_id',
                        current_tahun_id()
                    )
                    ->count();

                $hadir = AbsensiSiswa::where(
                        'siswa_id',
                        $p->siswa->id
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

                $persen = 0;

                if ($total > 0) {

                    $persen = round(
                        ($hadir / $total) * 100
                    );
                }

                $p->persentase_kehadiran = $persen;
            }
        }
        return view(
            'guru.nilai.index',
            compact(
                'kelasList',
                'mapels',
                'kelasId',
                'mapelId',
                'penempatan'
            )
        );
    }

    public function store(Request $request)
    {
        $guru = auth()->user()->guru;

        /*
        |--------------------------------------------------------------------------
        | VALIDASI JADWAL
        |--------------------------------------------------------------------------
        */

        $jadwalValid = PengajuanJadwal::where(
                'guru_id',
                $guru->id
            )
            ->where(
                'kelas_id',
                $request->kelas_id
            )
            ->where(
                'mapel_id',
                $request->mapel_id
            )
            ->whereIn(
                'status',
                ['approved', 'published']
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->exists();

        if (!$jadwalValid) {

            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN NILAI
        |--------------------------------------------------------------------------
        */

        foreach ($request->nilai as $siswaId => $n) {
            $tugas = $n['tugas'] ?? 0;

            $uts = $n['uts'] ?? 0;

            $uas = $n['uas'] ?? 0;

            $akhir = (
                $tugas
                +
                $uts
                +
                $uas
            ) / 3;

            NilaiSiswa::updateOrCreate(

                [
                    'siswa_id' => $siswaId,

                    'kelas_id' => $request->kelas_id,

                    'mapel_id' => $request->mapel_id,

                    'tahun_ajaran_id' => current_tahun_id(),
                ],

                [
                    'guru_id' => $guru->id,

                    'nilai_tugas' => $tugas,

                    'nilai_uts' => $uts,

                    'nilai_uas' => $uas,

                    'nilai_akhir' => round($akhir, 2),
                ]
            );
        }

        return back()->with(
            'success',
            'Nilai berhasil disimpan'
        );
    }
}