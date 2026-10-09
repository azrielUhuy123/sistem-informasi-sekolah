<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\KeputusanKenaikan;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\DB;
use App\Models\PenempatanSiswa;
use App\Models\KelasMaster;
use App\Helpers\TingkatKelasHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

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
        $jumlahNaik = $keputusan->where('status', 'naik')->count();
        $jumlahTinggal = $keputusan->where('status', 'tinggal')->count();
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

        $approvalStatuses = $keputusan->pluck('approval_status')->unique()->values();

        if ($approvalStatuses->count() !== 1 || $approvalStatuses->first() !== 'pending') {
            return back()->with('error', 'Pengajuan harus berstatus pending dan seragam sebelum ditolak.');
        }

        DB::transaction(function () use ($kelas, $tahunAktif, $request) {
            $keputusan = KeputusanKenaikan::where('kelas_id', $kelas->id)
                ->where('tahun_ajaran_id', $tahunAktif->id)
                ->lockForUpdate()
                ->get();

            if (
                $keputusan->isEmpty() ||
                $keputusan->contains(fn ($item) => $item->approval_status !== 'pending')
            ) {
                throw ValidationException::withMessages([
                    'keputusan' => 'Status pengajuan berubah atau tidak lagi pending.',
                ]);
            }

            foreach ($keputusan as $item) {
                $item->update([
                    'approval_status' => 'rejected',
                    'catatan_kepsek' => $request->catatan_kepsek,
                    'approved_by' => Auth::id(),
                    'approved_at' => now(),
                ]);
            }
        });

        return redirect()
            ->route('superadmin.monitoring-kenaikan.show', $kelas)
            ->with('success', 'Pengajuan kenaikan kelas berhasil ditolak.');
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

        $approvalStatuses = $keputusan->pluck('approval_status')->unique()->values();

        if ($approvalStatuses->count() === 1 && $approvalStatuses->first() === 'approved') {
            return back()->with('success', 'Pengajuan ini sudah disetujui; tidak ada perubahan penempatan tambahan.');
        }

        if ($approvalStatuses->count() !== 1 || $approvalStatuses->first() !== 'pending') {
            return back()->with('error', 'Pengajuan harus berstatus pending dan seragam sebelum disetujui.');
        }

        DB::transaction(function () use ($kelas, $tahunAktif) {

            $keputusan = KeputusanKenaikan::with('siswa')
                ->where('kelas_id', $kelas->id)
                ->where('tahun_ajaran_id', $tahunAktif->id)
                ->lockForUpdate()
                ->get();

            if (
                $keputusan->isEmpty() ||
                $keputusan->contains(fn ($item) => $item->approval_status !== 'pending')
            ) {
                throw ValidationException::withMessages([
                    'keputusan' => 'Status pengajuan berubah atau tidak lagi pending.',
                ]);
            }

            if ($keputusan->pluck('siswa_id')->duplicates()->isNotEmpty()) {
                throw ValidationException::withMessages([
                    'keputusan' => 'Terdapat keputusan ganda untuk siswa pada kelas dan Tahun Ajaran ini.',
                ]);
            }

            $kelas->loadMissing('kelasGrup.kelasMaster');

            $angkatanId = $kelas->kelasGrup?->angkatan_id;
            $tingkatSekarang = $kelas->kelasGrup?->kelasMaster?->tingkat;

            if (!$angkatanId || !$tingkatSekarang) {
                throw ValidationException::withMessages([
                    'kelas' => 'Angkatan atau tingkat kelas asal belum lengkap.',
                ]);
            }

            if ($tingkatSekarang === 'VI' && $tahunAktif->semester !== 'Genap') {
                throw ValidationException::withMessages([
                    'tahun_ajaran' => 'Keputusan akhir kelas VI hanya dapat disetujui pada semester Genap.',
                ]);
            }

            $tingkatBaru = TingkatKelasHelper::berikutnya($tingkatSekarang);

            $rombel = str_starts_with($kelas->nama_kelas, $tingkatSekarang)
                ? substr($kelas->nama_kelas, strlen($tingkatSekarang))
                : '';

            if (!preg_match('/^[A-Za-z]+$/', $rombel)) {
                throw ValidationException::withMessages([
                    'kelas' => 'Kode rombel kelas asal tidak valid.',
                ]);
            }

            $kelasTujuan = null;
            $adaSiswaNaik = $keputusan->contains(
                fn ($item) => $item->status === 'naik'
            );

            if (!$tingkatBaru) {
                throw ValidationException::withMessages([
                    'kelas' => 'Tingkat tujuan tidak dapat ditentukan.',
                ]);
            }

            if ($tingkatBaru !== 'LULUS' && $adaSiswaNaik) {
                $kelasTujuan = Kelas::whereHas('kelasGrup', function ($q) use ($angkatanId, $tingkatBaru) {
                    $q->where('angkatan_id', $angkatanId)
                        ->whereHas('kelasMaster', function ($q) use ($tingkatBaru) {
                            $q->where('tingkat', $tingkatBaru);
                        });
                })
                ->where('nama_kelas', $tingkatBaru . $rombel)
                ->first();
            }

            if ($tingkatBaru !== 'LULUS' && $adaSiswaNaik && !$kelasTujuan) {
                throw ValidationException::withMessages([
                    'kelas' => "Kelas tujuan {$tingkatBaru}{$rombel} untuk Angkatan ini tidak ditemukan.",
                ]);
            }

            $penempatanPerSiswa = [];

            foreach ($keputusan as $item) {

                if (!$item->siswa || (int) $item->siswa->angkatan_id !== (int) $angkatanId) {
                    throw ValidationException::withMessages([
                        'siswa' => 'Angkatan siswa tidak sesuai dengan Angkatan kelas asal.',
                    ]);
                }

                $penempatanAktif = PenempatanSiswa::where('siswa_id', $item->siswa_id)
                    ->where('tahun_ajaran_id', $tahunAktif->id)
                    ->whereNull('tanggal_keluar')
                    ->lockForUpdate()
                    ->get();

                if ($penempatanAktif->count() !== 1) {
                    throw ValidationException::withMessages([
                        'siswa' => 'Siswa harus memiliki tepat satu penempatan aktif pada Tahun Ajaran ini.',
                    ]);
                }

                $penempatan = $penempatanAktif->first();

                if ((int) $penempatan->kelas_id !== (int) $item->kelas_id) {
                    throw ValidationException::withMessages([
                        'siswa' => 'Penempatan aktif siswa tidak sesuai dengan kelas keputusan.',
                    ]);
                }

                $penempatanPerSiswa[$item->siswa_id] = $penempatan;
            }

            foreach ($keputusan as $item) {
                $item->update([
                    'approval_status' => 'approved',
                    'approved_by' => Auth::id(),
                    'approved_at' => now(),
                ]);
            }

            foreach ($keputusan as $item) {
                if ($item->status !== 'naik') {
                    continue;
                }

                if ($tingkatSekarang === 'VI') {
                    continue;
                }

                $penempatanPerSiswa[$item->siswa_id]->update([
                    'tanggal_keluar' => now(),
                ]);

                if ($tingkatBaru === 'LULUS') {
                    continue;
                }

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