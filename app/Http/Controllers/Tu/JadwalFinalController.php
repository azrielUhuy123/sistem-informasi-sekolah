<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\HariSekolah;
use App\Models\JamPelajaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
class JadwalFinalController extends Controller
{
    public function index()
    {
        $kelas = Kelas::with([

            'kelasGrup.kelasMaster',

            'pengajuanJadwal' => function ($q) {

                $q->where(
                        'tahun_ajaran_id',
                        current_tahun_id()
                    )
                    ->where(
                        'status',
                        'published'
                    )
                    ->with([
                        'guru',
                        'mapel',
                        'hari',
                        'jamPelajaran',
                    ]);

            }

        ])
        ->orderBy('nama_kelas')
        ->get();

        $kelasByTingkat = $kelas->groupBy(function ($k) {

            return $k->kelasGrup
                ->kelasMaster
                ->tingkat;

        });

        $hariList = HariSekolah::orderBy('urutan')->get();

        $jamList = JamPelajaran::orderBy('mulai')->get();

        return view(
            'tu.jadwal-final.index',
            compact(
                'kelasByTingkat',
                'hariList',
                'jamList'
            )
        );
    }
    public function export(Request $request)
    {
        $kelasIds = $request->kelas_ids ?? [];

        if (empty($kelasIds)) {

            return back()->with(
                'error',
                'Pilih minimal 1 kelas'
            );

        }

        $kelas = Kelas::with([

            'kelasGrup.kelasMaster',

            'pengajuanJadwal' => function ($q) {

                $q->where(
                        'tahun_ajaran_id',
                        current_tahun_id()
                    )
                    ->where(
                        'status',
                        'published'
                    )
                    ->with([
                        'guru',
                        'mapel',
                        'hari',
                        'jamPelajaran',
                    ]);

            }

        ])
        ->whereIn('id', $kelasIds)
        ->orderBy('nama_kelas')
        ->get();

        $hariList = HariSekolah::orderBy('urutan')->get();

        $jamList = JamPelajaran::orderBy('mulai')->get();

        $pdf = Pdf::loadView(
            'tu.jadwal-final.pdf',
            compact(
                'kelas',
                'hariList',
                'jamList'
            )
        )->setPaper('a4', 'landscape');

        return $pdf->download(
            'jadwal-final.pdf'
        );
    }
}