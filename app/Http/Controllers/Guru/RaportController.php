<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\AbsensiSiswa;
use App\Models\NilaiSiswa;
use App\Models\PengampuMapel;
use App\Models\PerkembanganSiswa;
use App\Models\Siswa;
use App\Models\WaliKelas;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class RaportController extends Controller
{
    public function show(Siswa $siswa)
    {
        return view(
            'raport.show',
            $this->reportData($siswa)
        );
    }

    public function pdf(Siswa $siswa)
    {
        $data = $this->reportData($siswa);

        return Pdf::loadView(
            'raport.pdf',
            $data
        )
            ->setPaper('a4', 'portrait')
            ->download('raport-' . $siswa->nis . '.pdf');
    }

    private function reportData(Siswa $siswa): array
    {
        $this->authorizeStudent($siswa);

        /*
        |--------------------------------------------------------------------------
        | NILAI
        |--------------------------------------------------------------------------
        */

        $nilai = NilaiSiswa::with('mapel')
            ->where(
                'siswa_id',
                $siswa->id
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->get();

        /*
        |--------------------------------------------------------------------------
        | RATA-RATA
        |--------------------------------------------------------------------------
        */

        $rataRata = round(
            $nilai->avg('nilai_akhir') ?? 0,
            2
        );

        /*
        |--------------------------------------------------------------------------
        | ABSENSI
        |--------------------------------------------------------------------------
        */

        $hadir = AbsensiSiswa::where(
                'siswa_id',
                $siswa->id
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->where(
                'status',
                'hadir'
            )
            ->count();

        $izin = AbsensiSiswa::where(
                'siswa_id',
                $siswa->id
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->where(
                'status',
                'izin'
            )
            ->count();

        $sakit = AbsensiSiswa::where(
                'siswa_id',
                $siswa->id
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->where(
                'status',
                'sakit'
            )
            ->count();

        $alpha = AbsensiSiswa::where(
                'siswa_id',
                $siswa->id
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->where(
                'status',
                'alpha'
            )
            ->count();

        /*
        |--------------------------------------------------------------------------
        | PERKEMBANGAN
        |--------------------------------------------------------------------------
        */

        $perkembangan = PerkembanganSiswa::where(
                'siswa_id',
                $siswa->id
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->latest()
            ->first();

        return compact(
            'siswa',
            'nilai',
            'rataRata',
            'hadir',
            'izin',
            'sakit',
            'alpha',
            'perkembangan'
        );
    }

    private function authorizeStudent(Siswa $siswa): void
    {
        $guruId = Auth::user()?->guru?->id;

        if (!$guruId) {
            abort(403);
        }

        $tahunAjaranId = current_tahun_id();

        $hasActivePlacement = function ($query) use ($siswa, $tahunAjaranId) {
            $query->where('siswa_id', $siswa->id)
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->whereNull('tanggal_keluar');
        };

        $isWaliKelas = WaliKelas::where('guru_id', $guruId)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->whereHas('kelas.penempatan', $hasActivePlacement)
            ->exists();

        $isGuruPengampu = PengampuMapel::where('guru_id', $guruId)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->whereHas('kelas.penempatan', $hasActivePlacement)
            ->exists();

        abort_unless($isWaliKelas || $isGuruPengampu, 403);
    }
}