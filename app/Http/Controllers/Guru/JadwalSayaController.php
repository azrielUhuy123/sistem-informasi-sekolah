<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\HariSekolah;
use App\Models\JamPelajaran;
use App\Models\Kelas;
use App\Models\KelasMaster;
use App\Models\MataPelajaran;
use App\Models\PengajuanJadwal;
use App\Models\PengampuMapel;
use App\Models\WaliKelas;
use Illuminate\Http\Request;

class JadwalSayaController extends Controller
{
    public function index(Request $request)
    {
        $guru = auth()->user()->guru;

        abort_unless($guru, 403, 'Akun guru belum terhubung.');

        $pengampu = $this->buildPengampuOptions($guru);

        $selectedKelas = $request->integer('kelas_id');

        if (! $selectedKelas) {
            $selectedKelas = $guru->waliKelas?->kelas_id
                ?? $pengampu->first()?->kelas_id;
        }

        $selectedMapel = $request->integer('mapel_id');

        $kelas = null;

        if ($selectedKelas) {
            $kelas = Kelas::with([
                'kelasGrup.kelasMaster'
            ])->find($selectedKelas);
        }

        if (! $selectedMapel && $selectedKelas) {
            $firstMapel = $pengampu->firstWhere('kelas_id', $selectedKelas);

            $selectedMapel = $firstMapel?->mata_pelajaran_id;
        }

        $haris = HariSekolah::orderBy('urutan')->get();
        $jams = JamPelajaran::orderBy('jam_ke')->get();

        $jadwal = collect();

        if ($selectedKelas) {
            $jadwal = PengajuanJadwal::with('mapel')
                ->where('tahun_ajaran_id', current_tahun_id())
                ->where('kelas_id', $selectedKelas)
                ->get();
        }

        $jadwals = [];

        foreach ($jadwal as $item) {
            $jadwals[$item->hari_id][$item->jam_pelajaran_id] = $item;
        }

        $totalJP = 0;

        if ($selectedKelas) {
            $totalJP = PengajuanJadwal::where('kelas_id', $selectedKelas)
                ->where('tahun_ajaran_id', current_tahun_id())
                ->count();
        }

        $maxJP = 0;

        if ($kelas) {
            $maxJP = max_jp_kelas(
                $kelas->kelasGrup?->kelasMaster?->tingkat
            );
        }

        $jpTracker = [];

        if ($selectedKelas) {
            foreach ($pengampu->where('kelas_id', $selectedKelas)->unique('mata_pelajaran_id') as $item) {
                $mapel = $item->mapel;

                if (! $mapel) {
                    continue;
                }

                $current = PengajuanJadwal::where('kelas_id', $selectedKelas)
                    ->where('mapel_id', $mapel->id)
                    ->where('tahun_ajaran_id', current_tahun_id())
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
        }

        $selectedJP = null;

        if ($selectedMapel && isset($jpTracker[$selectedMapel])) {
            $selectedJP = $jpTracker[$selectedMapel];
        }

        return view('guru.jadwal-saya.index', compact(
            'pengampu',
            'selectedKelas',
            'selectedMapel',
            'selectedJP',
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
        $guru = auth()->user()->guru;

        abort_unless($guru, 403, 'Akun guru belum terhubung.');

        $request->validate([
            'kelas_id' => 'required|integer|exists:kelas,id',
            'mapel_id' => 'required|integer|exists:mata_pelajaran,id',
            'hari_id' => 'required|integer|exists:hari_sekolah,id',
            'jam_id' => 'required|integer|exists:jam_pelajaran,id',
        ]);

        $kelas = Kelas::with([
            'kelasGrup.kelasMaster'
        ])->findOrFail($request->kelas_id);

        if (! $this->guruBolehAksesKelas($guru->id, $kelas->id)) {
            return back()->with('error', 'Anda tidak memiliki akses ke kelas ini.');
        }

        if (! $this->guruBolehAksesMapelUntukKelas($guru->id, $kelas->id, $request->mapel_id)) {
            return back()->with('error', 'Anda tidak memiliki assignment mapel ini.');
        }

        $published = PengajuanJadwal::where('kelas_id', $kelas->id)
            ->where('tahun_ajaran_id', current_tahun_id())
            ->where('status', 'published')
            ->exists();

        if ($published) {
            return back()->with('error', 'Jadwal sudah dipublish');
        }

        $mapel = MataPelajaran::findOrFail($request->mapel_id);

        $exists = PengajuanJadwal::where([
                'kelas_id' => $kelas->id,
                'hari_id' => $request->hari_id,
                'jam_pelajaran_id' => $request->jam_id,
            ])
            ->where('tahun_ajaran_id', current_tahun_id())
            ->exists();

        if ($exists) {
            return back()->with('error', 'Slot sudah terisi');
        }

        $currentJP = PengajuanJadwal::where('kelas_id', $kelas->id)
            ->where('mapel_id', $mapel->id)
            ->where('tahun_ajaran_id', current_tahun_id())
            ->count();

        if ($currentJP >= $mapel->jp_per_minggu) {
            return back()->with('error', 'JP mapel sudah penuh');
        }

        $totalJP = PengajuanJadwal::where('kelas_id', $kelas->id)
            ->where('tahun_ajaran_id', current_tahun_id())
            ->count();

        $maxJP = max_jp_kelas(
            $kelas->kelasGrup?->kelasMaster?->tingkat
        );

        if ($totalJP >= $maxJP) {
            return back()->with('error', 'Total JP kelas sudah penuh');
        }

        PengajuanJadwal::create([
            'guru_id' => $guru->id,
            'kelas_id' => $kelas->id,
            'mapel_id' => $request->mapel_id,
            'hari_id' => $request->hari_id,
            'jam_pelajaran_id' => $request->jam_id,
            'tahun_ajaran_id' => current_tahun_id(),
            'status' => 'draft',
        ]);

        return back()->with('success', 'Slot berhasil dipilih');
    }

    public function hapusSlot($id)
    {
        $guru = auth()->user()->guru;

        abort_unless($guru, 403, 'Akun guru belum terhubung.');

        $jadwal = PengajuanJadwal::where('guru_id', $guru->id)
            ->where('tahun_ajaran_id', current_tahun_id())
            ->findOrFail($id);

        if ($jadwal->status === 'published') {
            return back()->with('error', 'Jadwal sudah dipublish');
        }

        if (
            $jadwal->status !== 'draft' &&
            $jadwal->status !== 'rejected'
        ) {
            return back()->with('error', 'Jadwal sudah disubmit');
        }

        $jadwal->delete();

        return back()->with('success', 'Slot berhasil dihapus');
    }

    public function submitJadwal(Request $request)
    {
        $guru = auth()->user()->guru;

        abort_unless($guru, 403, 'Akun guru belum terhubung.');

        $request->validate([
            'kelas_id' => 'required|integer|exists:kelas,id',
        ]);

        $kelas = Kelas::with([
            'kelasGrup.kelasMaster'
        ])->findOrFail($request->kelas_id);

        if (! $this->guruBolehAksesKelas($guru->id, $kelas->id)) {
            return back()->with('error', 'Anda tidak memiliki akses ke kelas ini.');
        }

        $published = PengajuanJadwal::where('kelas_id', $kelas->id)
            ->where('tahun_ajaran_id', current_tahun_id())
            ->where('status', 'published')
            ->exists();

        if ($published) {
            return back()->with('error', 'Jadwal sudah dipublish');
        }

        $drafts = PengajuanJadwal::where('guru_id', $guru->id)
            ->where('kelas_id', $kelas->id)
            ->where('tahun_ajaran_id', current_tahun_id())
            ->whereIn('status', [
                'draft',
                'rejected'
            ])
            ->get();

        if ($drafts->count() === 0) {
            return back()->with('error', 'Tidak ada jadwal untuk disubmit');
        }

        foreach ($drafts as $item) {
            $item->update([
                'status' => 'submitted'
            ]);
        }

        return back()->with('success', 'Jadwal berhasil disubmit');
    }

    private function buildPengampuOptions($guru)
    {
        $options = collect();

        $pengampuRows = PengampuMapel::with([
                'kelas.kelasGrup.kelasMaster',
                'mapel'
            ])
            ->where('guru_id', $guru->id)
            ->where('tahun_ajaran_id', current_tahun_id())
            ->get();

        foreach ($pengampuRows as $row) {
            $options->put($row->kelas_id . '-' . $row->mata_pelajaran_id, $row);
        }

        $waliKelas = WaliKelas::with([
                'kelas.kelasGrup.kelasMaster'
            ])
            ->where('guru_id', $guru->id)
            ->where('tahun_ajaran_id', current_tahun_id())
            ->first();

        if ($waliKelas?->kelas) {
            $mapelInti = MataPelajaran::whereIn(
                'nama_mapel',
                [
                    'Bahasa Indonesia',
                    'Matematika',
                    'Pendidikan Pancasila',
                    'Ilmu Pengetahuan Alam dan Sosial',
                ]
            )->get();

            foreach ($mapelInti as $mapel) {
                $fakePengampu = new PengampuMapel();

                $fakePengampu->kelas_id = $waliKelas->kelas->id;
                $fakePengampu->mata_pelajaran_id = $mapel->id;

                $fakePengampu->setRelation('kelas', $waliKelas->kelas);
                $fakePengampu->setRelation('mapel', $mapel);

                $options->put(
                    $waliKelas->kelas->id . '-' . $mapel->id,
                    $fakePengampu
                );
            }
        }

        return $options->values();
    }

    private function guruBolehAksesKelas(int $guruId, int $kelasId): bool
    {
        $waliKelasId = WaliKelas::where('guru_id', $guruId)
            ->where('tahun_ajaran_id', current_tahun_id())
            ->value('kelas_id');

        if ((int) $waliKelasId === (int) $kelasId) {
            return true;
        }

        return PengampuMapel::where('guru_id', $guruId)
            ->where('kelas_id', $kelasId)
            ->where('tahun_ajaran_id', current_tahun_id())
            ->exists();
    }

    private function guruBolehAksesMapelUntukKelas(int $guruId, int $kelasId, int $mapelId): bool
    {
        $waliKelasId = WaliKelas::where('guru_id', $guruId)
            ->where('tahun_ajaran_id', current_tahun_id())
            ->value('kelas_id');

        if ((int) $waliKelasId === (int) $kelasId) {
            return \App\Models\Guru::findOrFail($guruId)
                ->mapels()
                ->whereKey($mapelId)
                ->exists();
        }

        return PengampuMapel::where('guru_id', $guruId)
            ->where('kelas_id', $kelasId)
            ->where('mata_pelajaran_id', $mapelId)
            ->where('tahun_ajaran_id', current_tahun_id())
            ->exists();
    }
}