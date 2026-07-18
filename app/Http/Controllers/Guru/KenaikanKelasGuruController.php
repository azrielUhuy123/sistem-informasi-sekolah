<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\KeputusanKenaikan;
use App\Models\WaliKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        foreach ($request->keputusan as $siswaId => $k) {

            $keputusan = KeputusanKenaikan::firstOrNew([
                'siswa_id' => $siswaId,
                'tahun_ajaran_id' => current_tahun_id(),
            ]);

            // Jika sudah final approve, tidak boleh diubah lagi
            if ($keputusan->exists && $keputusan->isApproved()) {
                continue;
            }

            $keputusan->kelas_id = $waliKelas->kelas_id;
            $keputusan->guru_id = $guru->id;
            $keputusan->status = $k['status'];
            $keputusan->catatan = $k['catatan'] ?? null;

            // Setiap simpan kembali menjadi Draft
            $keputusan->approval_status = 'draft';
            $keputusan->approved_by = null;
            $keputusan->approved_at = null;
            $keputusan->catatan_kepsek = null;
            $keputusan->submitted_at = null;

            $keputusan->save();
        }

        return back()->with(
            'success',
            'Keputusan kenaikan berhasil disimpan'
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

        foreach ($keputusan as $item) {

            if ($item->isApproved()) {
                continue;
            }

            $item->update([
                'approval_status' => 'pending',
                'submitted_at' => now(),
            ]);
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