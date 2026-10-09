<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\KeputusanKenaikan;
use App\Models\PenempatanSiswa;
use App\Models\WaliKelas;
use App\Services\BusinessNotificationService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class KenaikanKelasGuruController extends Controller
{
    public function index()
    {
        $guru = Auth::user()->guru;

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
        $guru = Auth::user()->guru;

        $waliKelas = WaliKelas::where(
                'guru_id',
                $guru->id
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->firstOrFail();

        $decisionSiswaIds = collect(array_keys($request->keputusan ?? []));
        $approvedSiswaIds = KeputusanKenaikan::whereIn('siswa_id', $decisionSiswaIds)
            ->where('tahun_ajaran_id', current_tahun_id())
            ->where('approval_status', 'approved')
            ->pluck('siswa_id');
        $decisionSiswaIds = $decisionSiswaIds->diff($approvedSiswaIds)->values();
        $placementsBySiswa = PenempatanSiswa::whereIn('siswa_id', $decisionSiswaIds)
            ->where('tahun_ajaran_id', current_tahun_id())
            ->whereNull('tanggal_keluar')
            ->get()
            ->groupBy('siswa_id');

        foreach ($decisionSiswaIds as $siswaId) {
            $placements = $placementsBySiswa->get($siswaId, collect());

            if ($placements->count() !== 1) {
                return back()->withErrors([
                    'keputusan' => 'Setiap siswa harus memiliki tepat satu penempatan aktif pada Tahun Ajaran ini.',
                ]);
            }

            if ((int) $placements->first()->kelas_id !== (int) $waliKelas->kelas_id) {
                return back()->withErrors([
                    'keputusan' => 'Kelas penempatan siswa tidak sesuai dengan kelas Wali Kelas.',
                ]);
            }
        }

        foreach ($request->keputusan as $siswaId => $k) {
            $keputusan = KeputusanKenaikan::firstOrNew([
                'siswa_id' => $siswaId,
                'tahun_ajaran_id' => current_tahun_id(),
            ]);

            // Jika sudah final approve, tidak boleh diubah lagi
            if ($keputusan->exists && $keputusan->isApproved()) {
                continue;
            }

            $decisionData = [
                'kelas_id' => $waliKelas->kelas_id,
                'guru_id' => $guru->id,
                'status' => $k['status'],
                'catatan' => $k['catatan'] ?? null,
                'approval_status' => 'draft',
                'approved_by' => null,
                'approved_at' => null,
                'catatan_kepsek' => null,
                'submitted_at' => null,
            ];

            $keputusan->fill($decisionData);

            try {
                $keputusan->save();
            } catch (QueryException $exception) {
                if (!$this->isBusinessKeyDuplicate($exception)) {
                    throw $exception;
                }

                $keputusan = KeputusanKenaikan::where('siswa_id', $siswaId)
                    ->where('tahun_ajaran_id', current_tahun_id())
                    ->first();

                if (!$keputusan) {
                    throw $exception;
                }

                if ($keputusan->isApproved()) {
                    continue;
                }

                $keputusan->fill($decisionData)->save();
            }
        }

        return back()->with(
            'success',
            'Keputusan kenaikan berhasil disimpan'
        );
    }

    private function isBusinessKeyDuplicate(QueryException $exception): bool
    {
        $errorInfo = $exception->errorInfo ?? [];

        return (int) ($errorInfo[1] ?? 0) === 1062 &&
            str_contains(
                $exception->getMessage(),
                'keputusan_kenaikan_siswa_tahun_unique'
            );
    }

    public function submit()
    {
        $guru = Auth::user()->guru;

        $waliKelas = WaliKelas::where(
                'guru_id',
                $guru->id
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->firstOrFail();

        $keputusan = KeputusanKenaikan::where(
                'kelas_id',
                $waliKelas->kelas_id
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->get();

        if ($keputusan->isEmpty()) {

            return back()->with(
                'error',
                'Belum ada keputusan kenaikan kelas yang disimpan.'
            );
        }

        $notificationDecision = DB::transaction(function () use ($keputusan, $waliKelas) {
            $keputusan = KeputusanKenaikan::whereKey($keputusan->modelKeys())
                ->lockForUpdate()
                ->get();
            $siswaIds = $keputusan->pluck('siswa_id')->unique()->values();

            $placements = PenempatanSiswa::whereIn('siswa_id', $siswaIds)
                ->where('tahun_ajaran_id', current_tahun_id())
                ->whereNull('tanggal_keluar')
                ->lockForUpdate()
                ->get()
                ->groupBy('siswa_id');

            foreach ($keputusan as $item) {
                if ($item->isApproved()) {
                    continue;
                }

                $studentPlacements = $placements->get($item->siswa_id, collect());

                if (
                    $studentPlacements->count() !== 1 ||
                    (int) $studentPlacements->first()?->kelas_id !== (int) $item->kelas_id ||
                    (int) $item->kelas_id !== (int) $waliKelas->kelas_id
                ) {
                    throw ValidationException::withMessages([
                        'keputusan' => 'Seluruh keputusan harus cocok dengan tepat satu penempatan aktif pada kelas Wali Kelas.',
                    ]);
                }
            }

            $notificationDecision = $keputusan->first(
                fn (KeputusanKenaikan $item) => $item->isDraft() || $item->isRejected()
            );

            foreach ($keputusan as $item) {
                if ($item->isApproved()) {
                    continue;
                }

                $item->update([
                    'approval_status' => 'pending',
                    'submitted_at' => now(),
                ]);
            }

            return $notificationDecision;
        });

        if ($notificationDecision) {
            app(BusinessNotificationService::class)
                ->notifyPromotionSubmitted($notificationDecision);
        }

        return back()->with(
            'success',
            'Pengajuan berhasil dikirim ke Kepala Sekolah.'
        );
    }

    public function cancelSubmission()
    {
        $guru = Auth::user()->guru;

        $waliKelas = WaliKelas::where(
                'guru_id',
                $guru->id
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->firstOrFail();

        $keputusan = KeputusanKenaikan::where(
                'kelas_id',
                $waliKelas->kelas_id
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->where(
                'approval_status',
                'pending'
            )
            ->get();

        if ($keputusan->isEmpty()) {

            return back()->with(
                'error',
                'Tidak ada pengajuan yang dapat dibatalkan.'
            );
        }

        foreach ($keputusan as $item) {

            $item->update([
                'approval_status' => 'draft',
                'submitted_at' => null,
            ]);
        }

        return back()->with(
            'success',
            'Pengajuan berhasil dibatalkan.'
        );
    }
}