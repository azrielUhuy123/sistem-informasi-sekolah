<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\PerkembanganSiswa;
use App\Models\WaliKelas;
use Illuminate\Http\Request;
use App\Models\PengampuMapel;

class PerkembanganSiswaController extends Controller
{
    public function index(Request $request)
    {
        $guru = auth()->user()->guru;

        /*
        |--------------------------------------------------------------------------
        | WALI KELAS
        |--------------------------------------------------------------------------
        */

        $waliKelas = WaliKelas::with('kelas')
            ->where('guru_id', $guru->id)
            ->where('tahun_ajaran_id', current_tahun_id())
            ->first();

        if ($waliKelas) {

            $kelas = $waliKelas->kelas;

        } else {

            /*
            |--------------------------------------------------------------------------
            | GURU BIDANG
            |--------------------------------------------------------------------------
            */

            $kelasPengampu = PengampuMapel::with('kelas')
                ->where('guru_id', $guru->id)
                ->where('tahun_ajaran_id', current_tahun_id())
                ->get();

            if ($kelasPengampu->isEmpty()) {
                abort(404, 'Guru belum memiliki kelas yang diampu.');
            }

            $kelasId = $request->kelas_id;

            if (!$kelasId) {
                $kelasId = $kelasPengampu->first()->kelas_id;
            }

            $kelas = $kelasPengampu
                ->firstWhere('kelas_id', $kelasId)
                ->kelas;
        }

        /*
        |--------------------------------------------------------------------------
        | SISWA
        |--------------------------------------------------------------------------
        */

        $penempatan = $kelas
            ->siswaAktif()
            ->with('siswa')
            ->get();

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

            $p->perkembangan = $perkembangan;

            $filled = 0;

            if ($perkembangan) {

                if ($perkembangan->karakter) $filled++;
                if ($perkembangan->sosial) $filled++;
                if ($perkembangan->disiplin) $filled++;
                if ($perkembangan->kebersihan) $filled++;
                if ($perkembangan->catatan) $filled++;
            }

            if ($filled == 0) {

                $p->status_perkembangan = 'Belum Ada';

            } elseif ($filled < 5) {

                $p->status_perkembangan = 'Belum Lengkap';

            } else {

                $p->status_perkembangan = 'Lengkap';
            }
        }

        return view(
            'guru.perkembangan.index',
            [
                'kelas'          => $kelas,
                'penempatan'     => $penempatan,
                'kelasPengampu'  => $kelasPengampu ?? collect(),
            ]
        );
    }

    public function store(Request $request)
    {
        $guru = auth()->user()->guru;

        $waliKelas = WaliKelas::where(
                'guru_id',
                $guru->id
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->firstOrFail();

        foreach ($request->perkembangan as $siswaId => $p) {

            PerkembanganSiswa::updateOrCreate(

                [
                    'siswa_id' => $siswaId,

                    'tahun_ajaran_id' => current_tahun_id(),
                ],

                [
                    'kelas_id' => $waliKelas->kelas_id,

                    'guru_id' => $guru->id,

                    'karakter' => $p['karakter'] ?? null,

                    'sosial' => $p['sosial'] ?? null,

                    'disiplin' => $p['disiplin'] ?? null,

                    'kebersihan' => $p['kebersihan'] ?? null,

                    'catatan' => $p['catatan'] ?? null,
                ]
            );
        }

        return back()->with(
            'success',
            'Perkembangan siswa berhasil disimpan'
        );
    }
}