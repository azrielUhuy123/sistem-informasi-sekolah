<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\HariSekolah;
use App\Models\JamPelajaran;
use App\Models\PengajuanJadwal;
use App\Models\PenempatanSiswa;
use Illuminate\Support\Facades\Auth;

class JadwalController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | SISWA LOGIN
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();

        $siswa = $user->siswa;

        /*
        |--------------------------------------------------------------------------
        | VALIDASI SISWA
        |--------------------------------------------------------------------------
        */

        if (!$siswa) {

            abort(
                404,
                'Data siswa tidak ditemukan'
            );

        }

        /*
        |--------------------------------------------------------------------------
        | TAHUN AJARAN
        |--------------------------------------------------------------------------
        */

        $tahunId = current_tahun_id();

        /*
        |--------------------------------------------------------------------------
        | PENEMPATAN SISWA
        |--------------------------------------------------------------------------
        */

        $penempatan = PenempatanSiswa::with('kelas')
            ->where(
                'siswa_id',
                $siswa->id
            )
            ->where(
                'tahun_ajaran_id',
                $tahunId
            )
            ->latest()
            ->first();

        /*
        |--------------------------------------------------------------------------
        | VALIDASI KELAS
        |--------------------------------------------------------------------------
        */

        if (!$penempatan) {

            abort(
                404,
                'Siswa belum ditempatkan ke kelas'
            );

        }

        /*
        |--------------------------------------------------------------------------
        | KELAS AKTIF
        |--------------------------------------------------------------------------
        */

        $kelasId = $penempatan->kelas_id;

        /*
        |--------------------------------------------------------------------------
        | HARI & JAM
        |--------------------------------------------------------------------------
        */

        $hari = HariSekolah::orderBy('urutan')
            ->get();

        $jam = JamPelajaran::orderBy('mulai')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | JADWAL FINAL
        |--------------------------------------------------------------------------
        */

        $jadwal = PengajuanJadwal::with([

                'guru',
                'mapel',
                'hari',
                'jamPelajaran'

            ])
            ->where(
                'kelas_id',
                $kelasId
            )
            ->where(
                'tahun_ajaran_id',
                $tahunId
            )
            ->where(
                'status',
                'published'
            )
            ->get();

        /*
        |--------------------------------------------------------------------------
        | FORMAT GRID
        |--------------------------------------------------------------------------
        */

        $grid = [];

        foreach ($jadwal as $item) {

            $grid[
                $item->jam_pelajaran_id
            ][
                $item->hari_id
            ] = $item;

        }

        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'siswa.jadwal.index',
            compact(

                'siswa',
                'hari',
                'jam',
                'grid',
                'penempatan'

            )
        );
    }
}
