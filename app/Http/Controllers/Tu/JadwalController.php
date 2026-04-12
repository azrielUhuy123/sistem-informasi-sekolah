<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\HariSekolah;
use App\Models\JamPelajaran;
use App\Models\MataPelajaran;
use App\Models\Guru;
use App\Actions\Jadwal\GetJPStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $kelasId = $request->kelas_id;

        $kelasList = Kelas::with('kelasGrup.kelasMaster')->get();
        $haris = HariSekolah::orderBy('urutan')->get();
        $jams = JamPelajaran::orderBy('jam_ke')->get();

        $jadwals = [];

        if ($kelasId) {
            $data = Jadwal::with(['mapel', 'guru'])
                ->where('kelas_id', $kelasId)
                ->get();

            foreach ($data as $j) {
                $jadwals[$j->hari_id][$j->jam_pelajaran_id] = $j;
            }
        }

        // setelah ambil $data (jadwal kelas ini)
        $occupiedGuru = [];

        // ambil semua jadwal (SEMUA kelas) untuk cek bentrok global guru
        $all = Jadwal::select('guru_id','hari_id','jam_pelajaran_id','kelas_id')->get();

        foreach ($all as $row) {
            $occupiedGuru[$row->hari_id][$row->jam_pelajaran_id][] = $row->guru_id;
        }

        $jpStatus = [];

        if ($kelasId) {
            $jpStatus = app(GetJPStatus::class)->execute($kelasId);
        }

        return view('tu.jadwal.index', compact(
            'kelasList',
            'kelasId',
            'haris',
            'jams',
            'jadwals',
            'occupiedGuru',
            'jpStatus'
        ));
    }

    public function edit(Request $request, $kelas, $hari, $jam)
    {
        $guruJam = collect();
        $waliKelasId = null;
        $kodeMapel = $request->get('mapel_id');

        $current = Jadwal::where([
            'kelas_id' => $kelas,
            'hari_id' => $hari,
            'jam_pelajaran_id' => $jam,
        ])->first();

        // fallback
        if (!$kodeMapel && $current) {
            $kodeMapel = optional($current->mapel)->kode_mapel;
        }

        $gurus = [];

        if ($kodeMapel) {
            $gurus = Guru::whereHas('mapels', function ($q) use ($kodeMapel) {
                $q->where('mata_pelajaran.kode_mapel', $kodeMapel);
            })->get();

            $guruJam = Jadwal::select('guru_id', DB::raw('COUNT(*) as total'))
                ->groupBy('guru_id')
                ->pluck('total', 'guru_id');

            $waliKelasId = optional(
                \App\Models\WaliKelas::where('kelas_id', $kelas)->first()
            )->guru_id;

            $gurus = $gurus->sortBy(function ($g) use ($guruJam, $waliKelasId) {
                if ($g->id == $waliKelasId) return 0;

                return ($guruJam[$g->id] ?? 0) + 1;
            });
        }

        $currentGuruId = $current?->guru_id;

        $occupiedGuru = Jadwal::where('hari_id', $hari)
            ->where('jam_pelajaran_id', $jam)
            ->where('guru_id', '!=', $currentGuruId)
            ->pluck('guru_id')
            ->toArray();

        $occupiedGuruMap = Jadwal::where('hari_id', $hari)
            ->where('jam_pelajaran_id', $jam)
            ->pluck('guru_id')
            ->toArray();

        $kelasData = Kelas::findOrFail($kelas);
        $fase = $this->getFaseFromKelas($kelasData->nama_kelas);

        $faseList = match ($fase) {
            'A' => ['A'],
            'B' => ['A', 'B'],
            'C' => ['A', 'B', 'C'],
        };

        $mapels = MataPelajaran::whereIn('fase', $faseList)
            ->get()
            ->unique('kode_mapel')
            ->values();

        $jpStatus = app(GetJPStatus::class)->execute($kelas);


        return view('tu.jadwal.edit', [
            'kelasId' => $kelas,
            'hariId' => $hari,
            'jamId' => $jam,
            'gurus' => $gurus,
            'mapelId' => $kodeMapel,
            'occupiedGuru' => $occupiedGuru,
            'guruJam' => $guruJam,
            'fase' => $fase,
            'mapels' => $mapels,
            'jpStatus' => $jpStatus,
            'occupiedGuruMap' => $occupiedGuruMap,
            'waliKelasId' => $waliKelasId,
        ]);
    }

    public function update(Request $request, $kelas, $hari, $jam)
    {
        $request->validate([
            'mapel_id' => 'required|exists:mata_pelajaran,kode_mapel',
            'guru_id' => 'required|exists:guru,id',
        ]);

        $kodeMapel = $request->mapel_id;

        $kelasData = Kelas::findOrFail($kelas);
        $fase = $this->getFaseFromKelas($kelasData->nama_kelas);

        // ambil mapel sesuai fase
        $mapel = MataPelajaran::where('kode_mapel', $kodeMapel)
            ->where('fase', $fase)
            ->firstOrFail();

        $guru = Guru::findOrFail($request->guru_id);

        // VALIDASI guru ↔ mapel
        if (!$guru->mapels()->where('mata_pelajaran.id', $mapel->id)->exists()) {
            return back()->withErrors([
                'guru_id' => 'Guru tidak mengampu mata pelajaran ini'
            ])->withInput();
        }

        // CEK BENTROK
        $bentrokData = Jadwal::with('kelas')
            ->where('guru_id', $request->guru_id)
            ->where('hari_id', $hari)
            ->where('jam_pelajaran_id', $jam)
            ->where('kelas_id', '!=', $kelas)
            ->first();

        if ($bentrokData) {
            return back()->withErrors([
                'guru_id' => 'Bentrok dengan kelas: ' . $bentrokData->kelas->nama_kelas
            ]);
        }

        // VALIDASI JP (pakai ID hasil mapping)
        
        $count = Jadwal::where('kelas_id', $kelas)
            ->whereHas('mapel', function ($q) use ($kodeMapel) {
                $q->where('kode_mapel', $kodeMapel);
            })
            ->count();

        $existing = Jadwal::where([
            'kelas_id' => $kelas,
            'hari_id' => $hari,
            'jam_pelajaran_id' => $jam,
        ])->first();

        $jpTarget = MataPelajaran::where('kode_mapel', $kodeMapel)
            ->orderBy('fase') // A dulu
            ->value('jp_per_minggu');

        if (
            !$existing ||
            optional($existing->mapel)->kode_mapel != $kodeMapel
        ) 
        {
            if ($count >= $jpTarget) {
                return back()->withErrors([
                    'mapel_id' => 'JP sudah terpenuhi'
                ]);
            }
        }
        // VALIDASI WALI KELAS (PRIORITAS - HARDENED)
        $waliKelasList = \App\Models\WaliKelas::where('guru_id', $request->guru_id)
            ->pluck('kelas_id');

        if ($waliKelasList->isNotEmpty() && !$waliKelasList->contains($kelas)) {

            $jadwalWali = Jadwal::whereIn('kelas_id', $waliKelasList)
                ->where('hari_id', $hari)
                ->where('jam_pelajaran_id', $jam)
                ->exists();

            if ($jadwalWali) {
                return back()->withErrors([
                    'guru_id' => 'Wali kelas harus mengajar di kelasnya sendiri pada jam ini'
                ]);
            }
        }

        Jadwal::updateOrCreate(
            [
                'kelas_id' => $kelas,
                'hari_id' => $hari,
                'jam_pelajaran_id' => $jam,
            ],
            [
                'mapel_id' => $mapel->id, // ✔ FIX
                'guru_id' => $request->guru_id,
            ]
        );

        return redirect()
            ->route('tu.jadwal.index', ['kelas_id' => $kelas])
            ->with('success', 'Jadwal berhasil disimpan');
    }
    public function jadwalGuru(Request $request)
{
    $guruId = $request->guru_id;
    $kelasId = $request->kelas_id;

    $gurus = Guru::all();
    $kelasList = Kelas::orderBy('nama_kelas')->get();

    $haris = HariSekolah::orderBy('urutan')->get();
    $jams = JamPelajaran::orderBy('jam_ke')->get();

    $jadwals = [];
    $totalJam = 0;
    $jpStatus = [];

    if ($guruId && $kelasId) {

        // ambil jadwal berdasarkan guru + kelas
        $data = Jadwal::with(['kelas', 'mapel'])
            ->where('guru_id', $guruId)
            ->where('kelas_id', $kelasId)
            ->get();

        foreach ($data as $j) {
            $jadwals[$j->hari_id][$j->jam_pelajaran_id] = $j;
        }

        // total jam khusus guru + kelas
        $totalJam = $data->count();

        // status JP (optional tapi bagus tetap ada)
        $jpStatus = app(GetJPStatus::class)->execute($kelasId);
    }

    return view('tu.jadwal.guru', compact(
        'gurus',
        'kelasList',
        'guruId',
        'kelasId',
        'haris',
        'jams',
        'jadwals',
        'totalJam',
        'jpStatus'
    ));
}

    private function getFaseFromKelas($namaKelas)
    {
        $tingkat = (int) substr($namaKelas, 0, 1);

        if (in_array($tingkat, [1, 2])) return 'A';
        if (in_array($tingkat, [3, 4])) return 'B';
        if (in_array($tingkat, [5, 6])) return 'C';

        return null;
    }
}
