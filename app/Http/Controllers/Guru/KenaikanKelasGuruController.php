<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\KeputusanKenaikan;
use App\Models\WaliKelas;
use Illuminate\Http\Request;

class KenaikanKelasGuruController extends Controller
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

            $keputusan = KeputusanKenaikan::where(
                    'siswa_id',
                    $p->siswa->id
                )
                ->where(
                    'tahun_ajaran_id',
                    current_tahun_id()
                )
                ->first();

            $p->keputusan = $keputusan;
        }

        return view(
            'guru.kenaikan.index',
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

        foreach ($request->keputusan as $siswaId => $k) {

            KeputusanKenaikan::updateOrCreate(

                [
                    'siswa_id' => $siswaId,

                    'tahun_ajaran_id' => current_tahun_id(),
                ],

                [
                    'kelas_id' => $waliKelas->kelas_id,

                    'guru_id' => $guru->id,

                    'status' => $k['status'],

                    'catatan' => $k['catatan'] ?? null,
                ]
            );
        }

        return back()->with(
            'success',
            'Keputusan kenaikan berhasil disimpan'
        );
    }
}