<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\KeputusanKenaikan;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\DB;
use App\Models\PenempatanSiswa;
use App\Models\KelasMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KenaikanKelasController extends Controller
{
    /**
     * Monitoring seluruh pengajuan kenaikan kelas.
     */
    public function index()
    {
        $tahunAktif = TahunAjaran::aktif();

        abort_if(!$tahunAktif, 404, 'Tahun ajaran aktif tidak ditemukan.');

        // Statistik
        $draftCount = KeputusanKenaikan::where('tahun_ajaran_id', $tahunAktif->id)
            ->where('approval_status', 'draft')
            ->count();

        $pendingCount = KeputusanKenaikan::where('tahun_ajaran_id', $tahunAktif->id)
            ->where('approval_status', 'pending')
            ->count();

        $rejectedCount = KeputusanKenaikan::where('tahun_ajaran_id', $tahunAktif->id)
            ->where('approval_status', 'rejected')
            ->count();

        $approvedCount = KeputusanKenaikan::where('tahun_ajaran_id', $tahunAktif->id)
            ->where('approval_status', 'approved')
            ->count();

        // Data kelas
        $kelasPerTingkat = Kelas::with([
            'kelasGrup.kelasMaster',
            'waliKelas.guru.pegawai',
            'penempatan' => function ($q) use ($tahunAktif) {
                $q->where('tahun_ajaran_id', $tahunAktif->id)
                    ->whereNull('tanggal_keluar');
            },
            'keputusanKenaikan' => function ($q) use ($tahunAktif) {
                $q->where('tahun_ajaran_id', $tahunAktif->id);
            }
        ])
        ->get()
        ->sortBy(function ($kelas) {
            return [
                $kelas->kelasGrup?->kelasMaster?->tingkat ?? 99,
                $kelas->nama_kelas
            ];
        })
        ->groupBy(function ($kelas) {
            return $kelas->kelasGrup?->kelasMaster?->tingkat ?? 'Lainnya';
        });

        return view(
            'superadmin.monitoring-kenaikan.index',
            compact(
                'tahunAktif',
                'kelasPerTingkat',
                'draftCount',
                'pendingCount',
                'rejectedCount',
                'approvedCount'
            )
        );
    }

    /**
     * Detail pengajuan kenaikan kelas per kelas.
     */
    public function show(Kelas $kelas)
    {
        $tahunAktif = TahunAjaran::aktif();

        abort_if(!$tahunAktif, 404, 'Tahun ajaran aktif tidak ditemukan.');

        $kelas->load([
            'kelasGrup.kelasMaster',
            'waliKelas.guru.pegawai',
        ]);

        $keputusan = KeputusanKenaikan::with([
            'siswa',
            'guru.pegawai',
            'approver',
        ])
            ->where('kelas_id', $kelas->id)
            ->where('tahun_ajaran_id', $tahunAktif->id)
            ->get()
            ->sortBy(function ($item) {
                return $item->siswa?->nama;
            })
            ->values();

        $totalSiswa = $keputusan->count();

        $jumlahNaik = $keputusan
            ->where('status', 'naik')
            ->count();

        $jumlahTinggal = $keputusan
            ->where('status', 'tinggal')
            ->count();

        $approvalStatus = $keputusan->first()?->approval_status ?? 'draft';

        return view(
            'superadmin.monitoring-kenaikan.show',
            compact(
                'tahunAktif',
                'kelas',
                'keputusan',
                'totalSiswa',
                'jumlahNaik',
                'jumlahTinggal',
                'approvalStatus'
            )
        );
    }

    /**
     * Reject pengajuan kenaikan kelas.
     */
    public function reject(Request $request, Kelas $kelas)
    {
        $request->validate([
            'catatan_kepsek' => ['required', 'string', 'max:1000'],
        ]);

        $tahunAktif = TahunAjaran::aktif();

        abort_if(!$tahunAktif, 404, 'Tahun ajaran aktif tidak ditemukan.');

        $keputusan = KeputusanKenaikan::where('kelas_id', $kelas->id)
            ->where('tahun_ajaran_id', $tahunAktif->id)
            ->get();

        abort_if($keputusan->isEmpty(), 404, 'Data pengajuan tidak ditemukan.');

        if ($keputusan->first()->approval_status !== 'pending') {
            return back()->with(
                'error',
                'Pengajuan ini sudah diproses.'
            );
        }

        foreach ($keputusan as $item) {
            $item->update([
                'approval_status' => 'rejected',
                'catatan_kepsek' => $request->catatan_kepsek,
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);
        }

        return redirect()
            ->route(
                'superadmin.monitoring-kenaikan.show',
                $kelas
            )
            ->with(
                'success',
                'Pengajuan kenaikan kelas berhasil ditolak.'
            );
    }
    public function approve(Request $request, Kelas $kelas)
    {
        $request->validate([
            'confirmation' => ['required', 'in:NAIK KELAS'],
        ]);

        $tahunAktif = TahunAjaran::aktif();

        abort_if(!$tahunAktif, 404, 'Tahun ajaran aktif tidak ditemukan.');

        $keputusan = KeputusanKenaikan::with('siswa')
            ->where('kelas_id', $kelas->id)
            ->where('tahun_ajaran_id', $tahunAktif->id)
            ->get();

        abort_if($keputusan->isEmpty(), 404, 'Data pengajuan tidak ditemukan.');

        if ($keputusan->first()->approval_status !== 'pending') {
            return back()->with(
                'error',
                'Pengajuan ini sudah diproses.'
            );
        }

        DB::transaction(function () use ($kelas, $keputusan, $tahunAktif) {

            // ============================
            // APPROVAL
            // ============================

            foreach ($keputusan as $item) {

                $item->update([
                    'approval_status' => 'approved',
                    'approved_by' => Auth::id(),
                    'approved_at' => now(),
                ]);

            }

            // ============================
            // KENAIKAN KELAS
            // ============================

            $tingkatSekarang = $kelas->kelasGrup->kelasMaster->tingkat;
            $tingkatBaru = $tingkatSekarang + 1;

            preg_match('/[A-Za-z]+$/', $kelas->nama_kelas, $match);

            $rombel = $match[0] ?? '';

            $kelasTujuan = Kelas::whereHas('kelasGrup.kelasMaster', function ($q) use ($tingkatBaru) {
                $q->where('tingkat', $tingkatBaru);
            })
            ->where('nama_kelas', $tingkatBaru . $rombel)
            ->first();

            if (!$kelasTujuan) {
                throw new \Exception('Kelas tujuan tidak ditemukan.');
            }

            foreach ($keputusan as $item) {

                if ($item->status !== 'naik') {
                    continue;
                }

                $penempatan = PenempatanSiswa::where('siswa_id', $item->siswa_id)
                    ->where('tahun_ajaran_id', $tahunAktif->id)
                    ->whereNull('tanggal_keluar')
                    ->first();

                if (!$penempatan) {
                    continue;
                }

                $penempatan->update([
                    'tanggal_keluar' => now(),
                ]);

                PenempatanSiswa::create([
                    'siswa_id' => $item->siswa_id,
                    'kelas_id' => $kelasTujuan->id,
                    'tahun_ajaran_id' => $tahunAktif->id,
                    'tanggal_masuk' => now(),
                    'tanggal_keluar' => null,
                ]);
            }

        });

        return redirect()
            ->route(
                'superadmin.monitoring-kenaikan.show',
                $kelas
            )
            ->with(
                'success',
                'Kenaikan kelas berhasil diproses.'
            );
    }
}