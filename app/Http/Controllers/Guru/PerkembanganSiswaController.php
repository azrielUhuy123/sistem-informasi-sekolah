<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\PerkembanganSiswa;
use App\Models\WaliKelas;
use Illuminate\Http\Request;

class PerkembanganSiswaController extends Controller
{
    public function index()
    {
        $guru = auth()->user()->guru;

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

            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            */

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
            compact(
                'kelas',
                'penempatan'
            )
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