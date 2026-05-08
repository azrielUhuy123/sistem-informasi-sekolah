<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\HariSekolah;
use App\Models\JamPelajaran;
use App\Models\MataPelajaran;
use App\Models\PengajuanJadwal;
use Illuminate\Http\Request;

class JadwalSayaController extends Controller
{
    public function index(Request $request)
    {
        $guru = auth()->user()->guru;

        $kelas = $guru->waliKelas?->kelas;

        if (!$kelas) {
            abort(403, 'Anda belum menjadi wali kelas');
        }

        $mapels = $guru->mapels;

        $selectedMapel = $request->mapel_id;

        $haris = HariSekolah::orderBy('urutan')->get();

        $jams = JamPelajaran::orderBy('jam_ke')->get();

        $jadwal = PengajuanJadwal::with('mapel')
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->where('guru_id', $guru->id)
            ->where('kelas_id', $kelas->id)
            ->get();

        $jadwals = [];

        foreach ($jadwal as $item) {

            $jadwals[$item->hari_id][$item->jam_pelajaran_id] = $item;

        }

        // TOTAL JP TERPAKAI
        $totalJP = PengajuanJadwal::where(
            'kelas_id',
            $kelas->id
        )
        ->where(
            'tahun_ajaran_id',
            current_tahun_id()
        )
        ->count();

        // MAX JP KELAS
        $maxJP = max_jp_kelas(
            $kelas->kelasGrup
                ->kelasMaster
                ->tingkat
        );

        $jpTracker = [];

        foreach ($mapels as $mapel) {

            $current = PengajuanJadwal::where(
                'kelas_id',
                $kelas->id
            )
            ->where(
                'mapel_id',
                $mapel->id
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->count();

            $target = $mapel->jp_per_minggu;

            $status = 'aman';

            if ($current >= $target) {

                $status = 'penuh';

            } elseif ($current >= ($target - 1)) {

                $status = 'hampir';

            }

            $jpTracker[$mapel->id] = [

                'current' => $current,

                'target' => $target,

                'status' => $status,

            ];

        }

        return view('guru.jadwal-saya.index', compact(
            'kelas',
            'mapels',
            'selectedMapel',
            'haris',
            'jams',
            'jadwals',
            'totalJP',
            'maxJP',
            'jpTracker'
        ));
    }

    public function pilihSlot(Request $request)
    {
        $request->validate([
            'mapel_id' => 'required|exists:mata_pelajaran,id',
            'hari_id' => 'required|exists:hari_sekolah,id',
            'jam_id' => 'required|exists:jam_pelajaran,id',
        ]);

        $guru = auth()->user()->guru;

        $kelas = $guru->waliKelas?->kelas;

        if (!$kelas) {
            abort(403);
        }

        // AMBIL MAPEL
        $mapel = MataPelajaran::findOrFail(
            $request->mapel_id
        );

        // =========================
        // VALIDASI SLOT SUDAH ADA
        // =========================

        $exists = PengajuanJadwal::where([
            'kelas_id' => $kelas->id,
            'hari_id' => $request->hari_id,
            'jam_pelajaran_id' => $request->jam_id,
        ])
        ->where(
            'tahun_ajaran_id',
            current_tahun_id()
        )
        ->exists();

        if ($exists) {

            return back()->with(
                'error',
                'Slot sudah terisi'
            );

        }

        // =========================
        // VALIDASI JP MAPEL
        // =========================

        $currentJP = PengajuanJadwal::where(
            'kelas_id',
            $kelas->id
        )
        ->where(
            'mapel_id',
            $mapel->id
        )
        ->where(
            'tahun_ajaran_id',
            current_tahun_id()
        )
        ->count();

        if ($currentJP >= $mapel->jp_per_minggu) {

            return back()->with(
                'error',
                'JP mapel sudah penuh'
            );

        }

        // =========================
        // VALIDASI TOTAL JP KELAS
        // =========================

        $totalJP = PengajuanJadwal::where(
            'kelas_id',
            $kelas->id
        )
        ->where(
            'tahun_ajaran_id',
            current_tahun_id()
        )
        ->count();

        $maxJP = max_jp_kelas(
            $kelas->kelasGrup
                ->kelasMaster
                ->tingkat
        );

        if ($totalJP >= $maxJP) {

            return back()->with(
                'error',
                'Total JP kelas sudah penuh'
            );

        }

        // =========================
        // SIMPAN
        // =========================

        PengajuanJadwal::create([

            'guru_id' => $guru->id,

            'kelas_id' => $kelas->id,

            'mapel_id' => $request->mapel_id,

            'hari_id' => $request->hari_id,

            'jam_pelajaran_id' => $request->jam_id,

            'tahun_ajaran_id' => current_tahun_id(),

            'status' => 'draft',

        ]);

        return back()->with(
            'success',
            'Slot berhasil dipilih'
        );
    }
    public function hapusSlot($id)
    {
        $guru = auth()->user()->guru;

        $jadwal = PengajuanJadwal::where(
            'guru_id',
            $guru->id
        )
        ->where(
            'tahun_ajaran_id',
            current_tahun_id()
        )
        ->findOrFail($id);

        // hanya draft boleh dihapus
        if (
            $jadwal->status !== 'draft' &&
            $jadwal->status !== 'rejected'
        ) {

            return back()->with(
                'error',
                'Jadwal sudah disubmit'
            );

        }

        $jadwal->delete();

        return back()->with(
            'success',
            'Slot berhasil dihapus'
        );
    }

    public function submitJadwal()
    {
        $guru = auth()->user()->guru;

        $kelas = $guru->waliKelas?->kelas;

        if (!$kelas) {
            abort(403);
        }

        $drafts = PengajuanJadwal::where('guru_id', $guru->id)
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->where('kelas_id', $kelas->id)
            ->whereIn('status', ['draft', 'rejected'])
            
            ->get();

        if ($drafts->count() === 0) {
            return back()->with('error', 'Tidak ada jadwal yang bisa disubmit');
        }

        foreach ($drafts as $item) {
            $item->update([
                'status' => 'submitted'
            ]);
        }

        return back()->with('success', 'Jadwal berhasil disubmit');
    }
}