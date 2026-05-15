<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\PengajuanJadwal;
use App\Models\HariSekolah;
use App\Models\JamPelajaran;
use Illuminate\Http\Request;

class MonitoringJadwalController extends Controller
{
    public function index()
    {
        $kelasPerTingkat = Kelas::with([
            'kelasGrup.kelasMaster',

            'pengajuanJadwal' => function ($query) {

                $query->where(
                    'tahun_ajaran_id',
                    current_tahun_id()
                );

            }
        ])
        ->get()

        ->groupBy(function ($kelas) {

            return optional(
                optional($kelas->kelasGrup)
                    ->kelasMaster
            )->tingkat ?? 'Lainnya';

        });

        return view(
            'superadmin.monitoring-jadwal.index',
            compact('kelasPerTingkat')
        );
    }

    public function show(Kelas $kelas)
    {
        $view = request('view', 'grid');

        $jadwals = PengajuanJadwal::with([
            'guru',
            'mapel',
            'hari',
            'jamPelajaran'
        ])
        ->where('kelas_id', $kelas->id)
        ->where(
            'tahun_ajaran_id',
            current_tahun_id()
        )
        ->get();

        $haris = HariSekolah::orderBy('id')->get();

        $jams = JamPelajaran::orderBy('jam_ke')->get();

        $formattedJadwal = [];

        foreach ($jadwals as $jadwal) {

            $formattedJadwal
            [$jadwal->hari_id]
            [$jadwal->jam_pelajaran_id]
                = $jadwal;
        }

        $conflicts = [];

        foreach ($jadwals as $jadwal) {

            $bentrok = PengajuanJadwal::where('guru_id', $jadwal->guru_id)
                ->where('hari_id', $jadwal->hari_id)
                ->where('jam_pelajaran_id', $jadwal->jam_pelajaran_id)
                ->where('id', '!=', $jadwal->id)
                ->where(
                    'tahun_ajaran_id',
                    current_tahun_id()
                )
                ->exists();

            $conflicts[$jadwal->id] = $bentrok;
        }

        return view(
            'superadmin.monitoring-jadwal.show',
            compact(
                'kelas',
                'haris',
                'jams',
                'formattedJadwal',
                'jadwals',
                'conflicts',
                'view'
            )
        );
    }

    public function approve(Kelas $kelas)
    {
        PengajuanJadwal::where('kelas_id', $kelas->id)
            ->where('tahun_ajaran_id', current_tahun_id())
            ->where('status', 'submitted')
            ->update([
                'status' => 'approved'
            ]);

        return redirect()->back()->with('success', 'Jadwal berhasil di-approve.');
    }

    public function reject(Request $request, Kelas $kelas)
    {
        $request->validate([
            'hari' => 'required',
            'jam' => 'required'
        ]);

        PengajuanJadwal::where('kelas_id', $kelas->id)
            ->where('tahun_ajaran_id', current_tahun_id())
            ->where('status', 'submitted')
            ->update([
                'status' => 'rejected',
                'catatan' =>

                    ($request->alasan
                        ? "Alasan: " . $request->alasan . "\n"
                        : '') .

                    "Hari: " . $request->hari . "\n" .

                    "Jam: " . $request->jam .

                    ($request->catatan_tambahan
                        ? "\nCatatan: " . $request->catatan_tambahan
                        : '')
            ]);

        return redirect()
            ->back()
            ->with('success', 'Jadwal berhasil direject.');
    }

    public function publish(Kelas $kelas)
    {
        $masihBelumFinal =
            PengajuanJadwal::where(
                'kelas_id',
                $kelas->id
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->whereIn('status', [
                'draft',
                'submitted',
                'rejected'
            ])
            ->exists();

        if ($masihBelumFinal) {

            return back()->with(
                'error',
                'Masih ada jadwal yang belum final.'
            );

        }

        PengajuanJadwal::where(
                'kelas_id',
                $kelas->id
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
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
            'Jadwal berhasil dipublish.'
        );
    }
}