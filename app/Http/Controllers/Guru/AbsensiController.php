<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\AbsensiSiswa;
use App\Models\WaliKelas;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
class AbsensiController extends Controller
{
    public function index()
    {
        $guru = auth()->user()->guru;

        $waliKelas = WaliKelas::with([
            'kelas'
        ])
        ->where(
            'guru_id',
            $guru->id
        )
        ->where(
            'tahun_ajaran_id',
            current_tahun_id()
        )
        ->first();

        if (!$waliKelas) {

            abort(403);

        }

        $kelas = $waliKelas->kelas;

        $penempatan = $kelas
            ->siswaAktif()
            ->with('siswa')
            ->get();

        return view(
            'guru.absensi.index',
            compact(
                'kelas',
                'penempatan'
            )
        );
    }

    public function store(Request $request)
    {
        $guru = auth()->user()->guru;

        $waliKelas = WaliKelas::where(
            'guru_id',
            $guru->id
        )
        ->where(
            'tahun_ajaran_id',
            current_tahun_id()
        )
        ->firstOrFail();

        foreach ($request->absensi as $siswaId => $data) {

            AbsensiSiswa::updateOrCreate(

                [
                    'siswa_id' => $siswaId,
                    'tanggal' => $request->tanggal,
                ],

                [
                    'kelas_id' => $waliKelas->kelas_id,

                    'tahun_ajaran_id' => current_tahun_id(),

                    'created_by' => auth()->id(),

                    'status' => $data['status'],

                    'keterangan' => $data['keterangan'] ?? null,
                ]
            );
        }

        return back()->with(
            'success',
            'Absensi berhasil disimpan'
        );
    }
    public function rekap(Request $request)
    {
        $guru = auth()->user()->guru;

        $waliKelas = WaliKelas::with([
            'kelas'
        ])
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

        $mode = $request->mode ?? 'semester';

        $bulan = $request->bulan ?? date('m');

        $tahun = $request->tahun ?? date('Y');

        $penempatan = $kelas
            ->siswaAktif()
            ->with('siswa')
            ->get();

        $rekap = [];

        foreach ($penempatan as $p) {

            $siswa = $p->siswa;

            $query = AbsensiSiswa::where(
                'siswa_id',
                $siswa->id
            );

            /*
            |--------------------------------------------------------------------------
            | MODE BULANAN
            |--------------------------------------------------------------------------
            */

            if ($mode == 'bulanan') {

                $query
                    ->whereMonth(
                        'tanggal',
                        $bulan
                    )
                    ->whereYear(
                        'tanggal',
                        $tahun
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | MODE SEMESTER
            |--------------------------------------------------------------------------
            */

            elseif ($mode == 'semester') {

                $query->where(
                    'tahun_ajaran_id',
                    current_tahun_id()
                );
            }

            /*
            |--------------------------------------------------------------------------
            | MODE TAHUNAN
            |--------------------------------------------------------------------------
            */

            elseif ($mode == 'tahunan') {

                $query->whereYear(
                    'tanggal',
                    $tahun
                );
            }

            $absensi = $query->get();

            $rekap[] = [

                'siswa' => $siswa,

                'hadir' => $absensi
                    ->where('status', 'hadir')
                    ->count(),

                'izin' => $absensi
                    ->where('status', 'izin')
                    ->count(),

                'sakit' => $absensi
                    ->where('status', 'sakit')
                    ->count(),

                'alpha' => $absensi
                    ->where('status', 'alpha')
                    ->count(),

            ];
        }

        return view(
            'guru.absensi.rekap',
            compact(
                'kelas',
                'rekap',
                'bulan',
                'tahun',
                'mode'
            )
        );
    }
    public function exportPdf(Request $request)
    {
        $guru = auth()->user()->guru;

        $waliKelas = WaliKelas::with([
            'kelas',
            'tahunAjaran'
        ])
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

        $mode = $request->mode ?? 'semester';

        $bulan = $request->bulan ?? date('m');

        $tahun = $request->tahun ?? date('Y');

        $penempatan = $kelas
            ->siswaAktif()
            ->with('siswa')
            ->get();

        $rekap = [];

        foreach ($penempatan as $p) {

            $siswa = $p->siswa;

            $query = AbsensiSiswa::where(
                'siswa_id',
                $siswa->id
            );

            /*
            |--------------------------------------------------------------------------
            | MODE
            |--------------------------------------------------------------------------
            */

            if ($mode == 'bulanan') {

                $query
                    ->whereMonth(
                        'tanggal',
                        $bulan
                    )
                    ->whereYear(
                        'tanggal',
                        $tahun
                    );
            }

            elseif ($mode == 'semester') {

                $query->where(
                    'tahun_ajaran_id',
                    current_tahun_id()
                );
            }

            elseif ($mode == 'tahunan') {

                $query->whereYear(
                    'tanggal',
                    $tahun
                );
            }

            $absensi = $query->get();

            $rekap[] = [

                'siswa' => $siswa,

                'hadir' => $absensi
                    ->where('status', 'hadir')
                    ->count(),

                'izin' => $absensi
                    ->where('status', 'izin')
                    ->count(),

                'sakit' => $absensi
                    ->where('status', 'sakit')
                    ->count(),

                'alpha' => $absensi
                    ->where('status', 'alpha')
                    ->count(),

            ];
        }

        $pdf = Pdf::loadView(
            'guru.absensi.pdf',
            compact(
                'kelas',
                'rekap',
                'mode',
                'bulan',
                'tahun',
                'waliKelas'
            )
        );

        return $pdf->download(
            'rekap-absensi-'.$kelas->nama_kelas.'.pdf'
        );
    }
}