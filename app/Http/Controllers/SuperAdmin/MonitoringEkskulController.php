<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Ekskul;
use App\Models\PengajuanJadwal;
use App\Models\PengajuanJadwalEkskul;
use Illuminate\Http\Request;

class MonitoringEkskulController extends Controller
{
    public function index()
    {
        $ekskul = Ekskul::with(
                'pembina.guru'
            )
            ->orderBy('nama')
            ->get();

        return view(
            'superadmin.monitoring-ekskul.index',
            compact(
                'ekskul'
            )
        );
    }

    public function show(Ekskul $ekskul)
    {
        $jadwal = PengajuanJadwalEkskul::with([

                'hari',
                'guru'

            ])
            ->where(
                'ekskul_id',
                $ekskul->id
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->orderBy('hari_id')
            ->orderBy('jam_mulai')
            ->get();

        foreach ($jadwal as $item) {

            $item->bentrok = $this->cekBentrok(
                $item
            );
        }

        $bisaApprove = $jadwal->every(

            fn ($j) => $j->bentrok === 'aman'

        );

        return view(
            'superadmin.monitoring-ekskul.show',
            compact(
                'ekskul',
                'jadwal',
                'bisaApprove'
            )
        );
    }

    public function approve(Ekskul $ekskul)
    {
        $jadwal = PengajuanJadwalEkskul::where(
                'ekskul_id',
                $ekskul->id
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->get();

        foreach ($jadwal as $item) {

            if (
                $this->cekBentrok($item)
                !== 'aman'
            ) {

                return back()->with(
                    'error',
                    'Masih terdapat jadwal yang bentrok.'
                );
            }
        }

        PengajuanJadwalEkskul::where(
            'ekskul_id',
            $ekskul->id
        )->update([

            'status' => 'approved',

            'catatan' => null

        ]);

        return back()->with(
            'success',
            'Jadwal berhasil disetujui'
        );
    }

    public function reject(
        Request $request,
        Ekskul $ekskul
    )
    {
        PengajuanJadwalEkskul::where(
            'ekskul_id',
            $ekskul->id
        )->update([

            'status' => 'rejected',

            'catatan' => $request->catatan

        ]);

        return back()->with(
            'success',
            'Jadwal berhasil ditolak'
        );
    }

    public function publish(Ekskul $ekskul)
    {
        $jadwal = PengajuanJadwalEkskul::where(
                'ekskul_id',
                $ekskul->id
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->get();

        foreach ($jadwal as $item) {

            if (
                $this->cekBentrok($item)
                !== 'aman'
            ) {

                return back()->with(
                    'error',
                    'Masih terdapat jadwal yang bentrok.'
                );
            }
        }

        PengajuanJadwalEkskul::where(
            'ekskul_id',
            $ekskul->id
        )
        ->where(
            'status',
            'approved'
        )
        ->update([

            'status' => 'published'

        ]);

        return back()->with(
            'success',
            'Jadwal berhasil dipublish'
        );
    }

    private function cekBentrok(
        PengajuanJadwalEkskul $jadwal
    )
    {
        /*
        |--------------------------------------------------------------------------
        | CEK BENTROK AKADEMIK
        |--------------------------------------------------------------------------
        */

        $jadwalAkademik = PengajuanJadwal::with(
                'jamPelajaran'
            )
            ->where(
                'hari_id',
                $jadwal->hari_id
            )
            ->where(
                'tahun_ajaran_id',
                $jadwal->tahun_ajaran_id
            )
            ->where(
                'status',
                'published'
            )
            ->get();

        foreach ($jadwalAkademik as $akademik) {

            if (
                !$akademik->jamPelajaran
            ) {
                continue;
            }

            $mulaiAkademik =
                $akademik
                    ->jamPelajaran
                    ->mulai;

            $selesaiAkademik =
                $akademik
                    ->jamPelajaran
                    ->selesai;

            if (

                $jadwal->jam_mulai
                    < $selesaiAkademik

                &&

                $jadwal->jam_selesai
                    > $mulaiAkademik

            ) {

                return 'akademik';
            }
        }

        /*
        |--------------------------------------------------------------------------
        | CEK BENTROK EKSKUL LAIN
        |--------------------------------------------------------------------------
        */

        $jadwalEkskul = PengajuanJadwalEkskul::query()

            ->where(
                'hari_id',
                $jadwal->hari_id
            )

            ->where(
                'tahun_ajaran_id',
                $jadwal->tahun_ajaran_id
            )

            ->where(
                'status',
                'published'
            )

            ->where(
                'id',
                '!=',
                $jadwal->id
            )

            ->get();

        foreach ($jadwalEkskul as $ekskulLain) {

            if (

                $jadwal->jam_mulai
                    < $ekskulLain->jam_selesai

                &&

                $jadwal->jam_selesai
                    > $ekskulLain->jam_mulai

            ) {

                return 'ekskul';
            }
        }

        return 'aman';
    }
}