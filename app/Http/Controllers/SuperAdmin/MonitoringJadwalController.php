<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\PengajuanJadwal;
use App\Models\HariSekolah;
use App\Models\JamPelajaran;
use App\Models\TahunAjaran;
use App\Services\BusinessNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

            $conflicts[$jadwal->id] = $this->hasConflict($jadwal);
        }

        $totalConflict = collect($conflicts)->filter(function ($isConflict) {
            return $isConflict === true;
        })->count();

        $totalAman = collect($conflicts)->filter(function ($isConflict) {
            return $isConflict === false;
        })->count();

        return view(
            'superadmin.monitoring-jadwal.show',
            compact(
                'kelas',
                'haris',
                'jams',
                'formattedJadwal',
                'jadwals',
                'conflicts',
                'view',
                'totalConflict', // <-- Kirim ke Blade
                'totalAman'
            )
        );
    }

    public function approve(Kelas $kelas)
    {
        $result = DB::transaction(function () use ($kelas) {
            $tahunAjaranId = current_tahun_id();
            $jadwals = PengajuanJadwal::with(['guru.user'])
                ->where('kelas_id', $kelas->id)
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->where('status', 'submitted')
                ->lockForUpdate()
                ->get();

            if ($jadwals->isEmpty()) {
                return 'empty';
            }

            foreach ($jadwals as $jadwal) {
                if ($this->hasConflict($jadwal)) {
                    return 'conflict';
                }
            }

            foreach ($jadwals as $jadwal) {
                $jadwal->update(['status' => 'approved']);
            }

            $teacherUsers = $jadwals
                ->map(fn ($jadwal) => $jadwal->guru?->user)
                ->filter()
                ->unique('id')
                ->values();
            $academicYear = TahunAjaran::findOrFail($tahunAjaranId)->tahun;

            app(BusinessNotificationService::class)->notifyScheduleApproved(
                $kelas,
                $academicYear,
                $teacherUsers,
            );

            return 'approved';
        });

        if ($result === 'empty') {
            return back()->with('error', 'Tidak ada jadwal submitted untuk di-approve.');
        }

        if ($result === 'conflict') {
            return back()->with('error', 'Jadwal bentrok tidak dapat di-approve.');
        }

        return redirect()->back()->with('success', 'Jadwal berhasil di-approve.');
    }

    public function reject(Request $request, Kelas $kelas)
    {
        $request->validate([
            'alasan' => 'required|string',
            'hari' => 'required|integer|exists:hari_sekolah,id',
            'jam' => 'required|integer|exists:jam_pelajaran,id',
            'catatan_tambahan' => 'nullable|string',
        ]);

        $result = DB::transaction(function () use ($request, $kelas) {
            $jadwals = PengajuanJadwal::with(['guru.user'])
                ->where('kelas_id', $kelas->id)
                ->where('tahun_ajaran_id', current_tahun_id())
                ->where('hari_id', $request->integer('hari'))
                ->where('jam_pelajaran_id', $request->integer('jam'))
                ->where('status', 'submitted')
                ->lockForUpdate()
                ->get();

            if ($jadwals->isEmpty()) {
                return 'empty';
            }

            if ($jadwals->count() > 1) {
                return 'ambiguous';
            }

            $jadwal = $jadwals->first();
            $hari = HariSekolah::findOrFail($request->integer('hari'));
            $jam = JamPelajaran::findOrFail($request->integer('jam'));
            $rejectionDetails = "Alasan: {$request->string('alasan')}\n"
                .'Hari: '.($hari->nama ?? $hari->nama_hari)."\n"
                .'Jam: Jam '.$jam->jam_ke.' ('.substr($jam->mulai, 0, 5)
                .' - '.substr($jam->selesai, 0, 5).')'
                .($request->filled('catatan_tambahan')
                    ? "\nCatatan: {$request->string('catatan_tambahan')}"
                    : '');

            $jadwal->update([
                'status' => 'rejected',
                'catatan' => $rejectionDetails,
            ]);

            $teacherUser = $jadwal->guru?->user;

            if ($teacherUser) {
                app(BusinessNotificationService::class)->notifyScheduleRejected(
                    $kelas,
                    $teacherUser,
                    $jadwal->getKey(),
                    $rejectionDetails,
                );
            }

            return 'rejected';
        });

        if ($result === 'empty') {
            return back()->with('error', 'Jadwal submitted pada slot tersebut tidak ditemukan.');
        }

        if ($result === 'ambiguous') {
            return back()->with('error', 'Slot reject memiliki lebih dari satu jadwal. Data tidak diubah.');
        }

        return redirect()
            ->back()
            ->with('success', 'Jadwal berhasil direject.');
    }

    public function publish(Kelas $kelas)
    {
        $query = PengajuanJadwal::where('kelas_id', $kelas->id)
            ->where('tahun_ajaran_id', current_tahun_id());

        $jadwalCount = (clone $query)->count();
        $approvedCount = (clone $query)->where('status', 'approved')->count();

        if ($jadwalCount === 0 || $approvedCount !== $jadwalCount) {

            return back()->with(
                'error',
                'Semua jadwal harus berstatus approved sebelum dipublish.'
            );

        }

        $approved = (clone $query)->where('status', 'approved')->get();

        if ($approved->isEmpty()) {
            return back()->with('error', 'Tidak ada jadwal approved untuk dipublish.');
        }

        foreach ($approved as $jadwal) {
            if ($this->hasConflict($jadwal)) {
                return back()->with('error', 'Jadwal bentrok tidak dapat dipublish.');
            }
        }

        $updated = (clone $query)
            ->where('status', 'approved')
            ->update(['status' => 'published']);

        if ($updated === 0) {
            return back()->with('error', 'Tidak ada jadwal yang berhasil dipublish.');
        }

        return back()->with(
            'success',
            'Jadwal berhasil dipublish.'
        );
    }

    private function hasConflict(PengajuanJadwal $jadwal): bool
    {
        return PengajuanJadwal::where('guru_id', $jadwal->guru_id)
            ->where('hari_id', $jadwal->hari_id)
            ->where('jam_pelajaran_id', $jadwal->jam_pelajaran_id)
            ->where('id', '!=', $jadwal->id)
            ->where('tahun_ajaran_id', current_tahun_id())
            ->exists();
    }
}