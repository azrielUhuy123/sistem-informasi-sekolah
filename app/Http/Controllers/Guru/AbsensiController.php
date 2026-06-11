<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\AbsensiSiswa;
use App\Models\PengajuanJadwal;
use App\Models\HariSekolah;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $guru = auth()->user()->guru;

        /*
        |--------------------------------------------------------------------------
        | HARI AKTIF
        |--------------------------------------------------------------------------
        */

        Carbon::setLocale('id');

        $namaHari = now()->translatedFormat('l');

        $hari = HariSekolah::where(
            'nama',
            $namaHari
        )->first();

        /*
        |--------------------------------------------------------------------------
        | JADWAL HARI INI
        |--------------------------------------------------------------------------
        */

        $jadwals = PengajuanJadwal::with([
                'kelas',
                'mapel',
                'hari',
                'jamPelajaran',
            ])
            ->where(
                'guru_id',
                $guru->id
            )
            ->where(
                'hari_id',
                $hari?->id
            )
            ->whereIn(
                'status',
                ['approved', 'published']
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->orderBy('jam_pelajaran_id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | JADWAL TERPILIH
        |--------------------------------------------------------------------------
        */

        $jadwalId = $request->jadwal_id
            ?? optional($jadwals->first())->id;

        $jadwal = PengajuanJadwal::with([
                'kelas',
                'mapel',
                'hari',
                'jamPelajaran',
            ])
            ->find($jadwalId);

        /*
        |--------------------------------------------------------------------------
        | SISWA
        |--------------------------------------------------------------------------
        */

        $penempatan = collect();

        if ($jadwal) {

            $penempatan = $jadwal
                ->kelas
                ->siswaAktif()
                ->with('siswa')
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | HISTORY ABSENSI
        |--------------------------------------------------------------------------
        */

        $history = AbsensiSiswa::with([
                'jadwal.kelas',
                'jadwal.mapel',
                'jadwal.jamPelajaran',
                'siswa',
            ])
            ->whereHas(
                'jadwal',
                function ($q) use ($guru) {

                    $q->where(
                        'guru_id',
                        $guru->id
                    );
                }
            )
            ->latest('tanggal')
            ->latest()
            ->take(20)
            ->get();

        return view(
            'guru.absensi.index',
            compact(
                'jadwals',
                'jadwal',
                'jadwalId',
                'penempatan',
                'history'
            )
        );
    }

    public function store(Request $request)
    {
        $request->validate([

            'jadwal_id' => 'required|exists:pengajuan_jadwal,id',

            'tanggal' => 'required|date',

            'absensi' => 'required|array',
        ]);

        $guru = auth()->user()->guru;

        /*
        |--------------------------------------------------------------------------
        | VALIDASI JADWAL MILIK GURU
        |--------------------------------------------------------------------------
        */

        $jadwal = PengajuanJadwal::where(
                'id',
                $request->jadwal_id
            )
            ->where(
                'guru_id',
                $guru->id
            )
            ->firstOrFail();

        foreach ($request->absensi as $siswaId => $a) {

            AbsensiSiswa::updateOrCreate(

                [
                    'jadwal_id' => $jadwal->id,

                    'siswa_id' => $siswaId,

                    'tanggal' => $request->tanggal,
                ],

                [
                    'kelas_id' => $jadwal->kelas_id,

                    'tahun_ajaran_id' => current_tahun_id(),

                    'status' => $a['status'],

                    'keterangan' => $a['keterangan'] ?? null,

                    'created_by' => auth()->id(),
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

        /*
        |--------------------------------------------------------------------------
        | KELAS DIAMPU
        |--------------------------------------------------------------------------
        */

        $kelasDiampu = PengajuanJadwal::with([
                'kelas',
                'mapel',
                'hari',
                'jamPelajaran',
            ])
            ->where(
                'guru_id',
                $guru->id
            )
            ->whereIn(
                'status',
                ['approved', 'published']
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->get()
            ->unique(function ($item) {

                return $item->kelas_id . '-' . $item->mapel_id;
            })
            ->values();

        $kelasId = $request->kelas_id
            ?? optional($kelasDiampu->first())->kelas_id;

        $mapelId = $request->mapel_id
            ?? optional($kelasDiampu->first())->mapel_id;

        $mode = $request->mode ?? 'semester';

        $bulan = $request->bulan ?? date('m');

        $tahun = $request->tahun ?? date('Y');

        $selected = $kelasDiampu->first(function ($item)
            use ($kelasId, $mapelId) {

            return
                $item->kelas_id == $kelasId
                &&
                $item->mapel_id == $mapelId;
        });

        $rekap = [];

        if ($selected) {

            $penempatan = $selected
                ->kelas
                ->siswaAktif()
                ->with('siswa')
                ->get();

            foreach ($penempatan as $p) {

                $siswa = $p->siswa;

                $query = AbsensiSiswa::where(
                        'siswa_id',
                        $siswa->id
                    )
                    ->where(
                        'kelas_id',
                        $kelasId
                    )
                    ->whereHas(
                        'jadwal',
                        function ($q) use ($mapelId) {

                            $q->where(
                                'mapel_id',
                                $mapelId
                            );

                        }
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
        }

        return view(
            'guru.absensi.rekap',
            compact(
                'kelasDiampu',
                'kelasId',
                'mapelId',
                'selected',
                'rekap',
                'bulan',
                'tahun',
                'mode'
            )
        );
    }

    public function edit(AbsensiSiswa $absensi)
    {
        return view(
            'guru.absensi.edit',
            compact('absensi')
        );
    }

    public function update(
        Request $request,
        AbsensiSiswa $absensi
    ) {

        $absensi->update([

            'status' => $request->status,

            'keterangan' => $request->keterangan,
        ]);

        return redirect()
            ->route('guru.absensi.index')
            ->with(
                'success',
                'Absensi berhasil diperbarui'
            );
    }
    
    public function exportPdf(Request $request)
    {
        $guru = auth()->user()->guru;

        /*
        |--------------------------------------------------------------------------
        | FILTER
        |--------------------------------------------------------------------------
        */

        $kelasId = $request->kelas_id;

        $mapelId = $request->mapel_id;

        $mode = $request->mode ?? 'semester';

        $bulan = $request->bulan ?? date('m');

        $tahun = $request->tahun ?? date('Y');

        /*
        |--------------------------------------------------------------------------
        | JADWAL
        |--------------------------------------------------------------------------
        */

        $selected = PengajuanJadwal::with([
                'kelas',
                'mapel',
                'hari',
                'jamPelajaran',
            ])
            ->where(
                'guru_id',
                $guru->id
            )
            ->where(
                'kelas_id',
                $kelasId
            )
            ->where(
                'mapel_id',
                $mapelId
            )
            ->whereIn(
                'status',
                ['approved', 'published']
            )
            ->firstOrFail();

        $kelas = $selected->kelas;

        $mapel = $selected->mapel;

        /*
        |--------------------------------------------------------------------------
        | SISWA
        |--------------------------------------------------------------------------
        */

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
                )
                ->where(
                    'kelas_id',
                    $kelasId
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
                'mapel',
                'rekap',
                'mode',
                'bulan',
                'tahun'
            )
        );

        return $pdf->download(
            'rekap-absensi-'.$kelas->nama_kelas.'-'.$mapel->nama.'.pdf'
        );
    }
    private function waliKelas()
    {
        return WaliKelas::with('kelas')
            ->where(
                'guru_id',
                auth()->user()->guru->id
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->firstOrFail();
    }

    public function rekapBulanan(Request $request)
    {
        $guru = auth()->user()->guru;

        $kelasDiampu = PengajuanJadwal::with([
                'kelas',
                'mapel'
            ])
            ->where('guru_id', $guru->id)
            ->whereIn('status', ['approved', 'published'])
            ->where('tahun_ajaran_id', current_tahun_id())
            ->get()
            ->unique(fn ($j) =>
                $j->kelas_id . '-' . $j->mapel_id
            )
            ->values();

        $kelasId =
            $request->kelas_id
            ?? optional($kelasDiampu->first())->kelas_id;

        $mapelId =
            $request->mapel_id
            ?? optional($kelasDiampu->first())->mapel_id;

        $bulan =
            $request->bulan
            ?? now()->month;

        $tahun =
            $request->tahun
            ?? now()->year;

        $selected = $kelasDiampu->first(function ($item)
            use ($kelasId, $mapelId) {

            return
                $item->kelas_id == $kelasId
                &&
                $item->mapel_id == $mapelId;
        });

        $jumlahHari =
            cal_days_in_month(
                CAL_GREGORIAN,
                $bulan,
                $tahun
            );

        $rekap = [];

        if ($selected) {

            $penempatan = $selected
                ->kelas
                ->siswaAktif()
                ->get();

            foreach ($penempatan as $p) {

                $siswa = $p->siswa;

                $absensi = AbsensiSiswa::where(
                        'siswa_id',
                        $siswa->id
                    )
                    ->where(
                        'kelas_id',
                        $kelasId
                    )
                    ->whereMonth(
                        'tanggal',
                        $bulan
                    )
                    ->whereYear(
                        'tanggal',
                        $tahun
                    )
                    ->whereHas(
                        'jadwal',
                        function ($q) use ($mapelId) {

                            $q->where(
                                'mapel_id',
                                $mapelId
                            );
                        }
                    )
                    ->get()
                    ->keyBy(function ($item) {

                        return date(
                            'j',
                            strtotime($item->tanggal)
                        );
                    });

                $rekap[] = [

                    'siswa' => $siswa,

                    'absensi' => $absensi,

                    'hadir' =>
                        $absensi
                            ->where('status', 'hadir')
                            ->count(),

                    'izin' =>
                        $absensi
                            ->where('status', 'izin')
                            ->count(),

                    'sakit' =>
                        $absensi
                            ->where('status', 'sakit')
                            ->count(),

                    'alpha' =>
                        $absensi
                            ->where('status', 'alpha')
                            ->count(),
                ];
            }
        }

        return view(
            'guru.absensi.rekap-bulanan',
            compact(
                'kelasDiampu',
                'kelasId',
                'mapelId',
                'bulan',
                'tahun',
                'selected',
                'rekap',
                'jumlahHari'
            )
        );
    }

    public function rekapSemester(Request $request)
    {
        $guru = auth()->user()->guru;

        $kelasDiampu = PengajuanJadwal::with([
                'kelas',
                'mapel'
            ])
            ->where('guru_id', $guru->id)
            ->whereIn('status', ['approved', 'published'])
            ->where('tahun_ajaran_id', current_tahun_id())
            ->get()
            ->unique(fn($j) =>
                $j->kelas_id.'-'.$j->mapel_id
            )
            ->values();

        $kelasId =
            $request->kelas_id
            ?? optional($kelasDiampu->first())->kelas_id;

        $mapelId =
            $request->mapel_id
            ?? optional($kelasDiampu->first())->mapel_id;

        $selected = $kelasDiampu->first(function ($item)
            use ($kelasId, $mapelId) {

            return
                $item->kelas_id == $kelasId
                &&
                $item->mapel_id == $mapelId;
        });

        $rekap = [];

        if ($selected) {

            $penempatan = $selected
                ->kelas
                ->siswaAktif()
                ->get();

            foreach ($penempatan as $p) {

                $siswa = $p->siswa;

                $bulanData = [];

                for ($bulan = 1; $bulan <= 12; $bulan++) {

                    $bulanData[$bulan] =
                        AbsensiSiswa::where(
                            'siswa_id',
                            $siswa->id
                        )
                        ->where(
                            'kelas_id',
                            $kelasId
                        )
                        ->whereMonth(
                            'tanggal',
                            $bulan
                        )
                        ->whereHas(
                            'jadwal',
                            function ($q) use ($mapelId) {

                                $q->where(
                                    'mapel_id',
                                    $mapelId
                                );
                            }
                        )
                        ->where(
                            'status',
                            'hadir'
                        )
                        ->count();
                }

                $rekap[] = [

                    'siswa' => $siswa,

                    'bulan' => $bulanData,

                    'total' => array_sum(
                        $bulanData
                    ),

                ];
            }
        }

        return view(
            'guru.absensi.rekap-semester',
            compact(
                'kelasDiampu',
                'kelasId',
                'mapelId',
                'selected',
                'rekap'
            )
        );
    }
}