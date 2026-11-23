<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\HariSekolah;
use App\Models\JamPelajaran;
use App\Models\Kelas;
use App\Models\PengajuanJadwal;
use App\Models\TahunAjaran;
use App\Services\BusinessNotificationService;
use App\Services\JadwalDraftService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JadwalSayaController extends Controller
{
    public function index(Request $request, JadwalDraftService $drafts)
    {
        $guru = Auth::user()->guru;

        abort_unless($guru, 403, 'Akun guru belum terhubung.');

        $pengampu = $drafts->optionsFor($guru);

        $selectedKelas = $request->integer('kelas_id');

        if (! $selectedKelas) {
            $selectedKelas = $pengampu->firstWhere('sumber', 'wali_kelas')?->kelas_id
                ?? $pengampu->first()?->kelas_id;
        }

        if (
            $selectedKelas &&
            ! $pengampu->contains(fn ($item) => (int) $item->kelas_id === $selectedKelas)
        ) {
            abort(403, 'Anda tidak memiliki akses ke kelas ini.');
        }

        $selectedMapel = $request->integer('mapel_id');

        if (
            $selectedKelas &&
            $selectedMapel &&
            ! $pengampu->contains(fn ($item) =>
                (int) $item->kelas_id === $selectedKelas &&
                (int) $item->mata_pelajaran_id === $selectedMapel
            )
        ) {
            abort(403, 'Anda tidak memiliki assignment mapel ini.');
        }

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
        $guruId = $guru->id;

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
            'guruId',
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

    public function pilihSlot(Request $request, JadwalDraftService $drafts)
    {
        $guru = Auth::user()->guru;

        abort_unless($guru, 403, 'Akun guru belum terhubung.');

        $request->validate([
            'kelas_id' => 'required|integer|exists:kelas,id',
            'mapel_id' => 'required|integer|exists:mata_pelajaran,id',
            'hari_id' => 'required|integer|exists:hari_sekolah,id',
            'jam_id' => 'required|integer|exists:jam_pelajaran,id',
        ]);

        $error = $drafts->createDraft($guru, [
            'kelas_id' => $request->integer('kelas_id'),
            'mapel_id' => $request->integer('mapel_id'),
            'hari_id' => $request->integer('hari_id'),
            'jam_pelajaran_id' => $request->integer('jam_id'),
        ]);

        if ($error) {
            return back()->with('error', $error);
        }

        return back()->with('success', 'Slot berhasil dipilih');
    }

    public function hapusSlot($id)
    {
        $guru = Auth::user()->guru;

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

    public function submitJadwal(Request $request, JadwalDraftService $drafts)
    {
        $guru = Auth::user()->guru;

        abort_unless($guru, 403, 'Akun guru belum terhubung.');

        $request->validate([
            'kelas_id' => 'required|integer|exists:kelas,id',
        ]);

        $kelas = Kelas::with([
            'kelasGrup.kelasMaster'
        ])->findOrFail($request->kelas_id);

        $tahunAjaranId = (int) current_tahun_id();

        if (! $tahunAjaranId) {
            return back()->with('error', 'Tahun ajaran aktif belum tersedia.');
        }

        if (! $drafts->teacherCanAccessClass($guru->id, $kelas->id, $tahunAjaranId)) {
            return back()->with('error', 'Anda tidak memiliki akses ke kelas ini.');
        }

        $published = PengajuanJadwal::where('kelas_id', $kelas->id)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->where('status', 'published')
            ->exists();

        if ($published) {
            return back()->with('error', 'Jadwal sudah dipublish');
        }

        $result = DB::transaction(function () use ($guru, $kelas, $tahunAjaranId, $drafts) {
            $items = PengajuanJadwal::with('mapel')
                ->where('guru_id', $guru->id)
                ->where('kelas_id', $kelas->id)
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->whereIn('status', ['draft', 'rejected'])
                ->lockForUpdate()
                ->get();

            if ($items->isEmpty()) {
                return ['count' => 0, 'error' => null];
            }

            $error = $drafts->validateSubmission($guru, $kelas, $items, $tahunAjaranId);

            if ($error) {
                return ['count' => 0, 'error' => $error];
            }

            foreach ($items as $item) {
                $item->update(['status' => 'submitted']);
            }

            $academicYear = TahunAjaran::findOrFail($tahunAjaranId)->tahun;

            app(BusinessNotificationService::class)->notifyScheduleSubmitted(
                $kelas,
                $academicYear,
                $items->count(),
            );

            return ['count' => $items->count(), 'error' => null];
        });

        if ($result['error']) {
            return back()->with('error', $result['error']);
        }

        if ($result['count'] === 0) {
            return back()->with('error', 'Tidak ada jadwal untuk disubmit');
        }

        return back()->with('success', 'Jadwal berhasil disubmit');
    }
}