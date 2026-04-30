<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\PenempatanSiswa;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenempatanSiswaController extends Controller
{
    /**
     * FORM ASSIGN SISWA KE KELAS
     * (dipanggil dari tombol "Assign Kelas" di menu SISWA)
     */
    public function create(Request $request)
    {
        $tahunAktif = TahunAjaran::aktif();

        if (!$tahunAktif) {
            return redirect()->route('tu.tahun.index')
                ->with('error', 'Silakan aktifkan tahun ajaran terlebih dahulu');
        }

        // Ambil siswa yang BELUM punya penempatan aktif
        $siswa = Siswa::whereDoesntHave('penempatan', function ($q) use ($tahunAktif) {
            $q->where('tahun_ajaran_id', $tahunAktif->id)
            ->whereNull('tanggal_keluar');
        })->orderBy('nama')->get();

        $kelas = Kelas::orderBy('nama_kelas')->get();

        return view('tu.penempatan-siswa.create', compact(
            'siswa',
            'kelas',
            'tahunAktif'
        ));
    }

    /**
     * SIMPAN PENEMPATAN SISWA
     */
    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $tahunAktif = TahunAjaran::aktif();

        if (!$tahunAktif) {
            abort(500, 'Tahun ajaran aktif belum diset');
        }

        if ($tahunAktif->status !== 'aktif') {
            abort(403, 'Assign hanya boleh saat tahun aktif.');
        }
        
        $exists = PenempatanSiswa::where('siswa_id', $request->siswa_id)
            ->where('tahun_ajaran_id', $tahunAktif->id)
            ->whereNull('tanggal_keluar')
            ->exists();

        return back()->withErrors([
            'siswa_id' => 'Siswa sudah punya penempatan aktif di tahun ini'
        ]);

        DB::transaction(function () use ($request, $tahunAktif) {

            // Buat penempatan baru
            PenempatanSiswa::create([
                'siswa_id'        => $request->siswa_id,
                'kelas_id'        => $request->kelas_id,
                'tahun_ajaran_id' => $tahunAktif->id,
                'tanggal_masuk'   => now(),
            ]);
        });

        return redirect()
            ->route('tu.kelas.rombel.show', $request->kelas_id)
            ->with('success', 'Siswa berhasil ditempatkan ke rombel');
    }
    public function naikKelas(Request $request)
    {
        $request->validate([
            'mapping' => 'required|array',
            'siswa'   => 'required|array'
        ]);

        $tahun = TahunAjaran::aktif();

        if (!$tahun) {
            abort(500, 'Tidak ada tahun ajaran aktif');
        }

        if ($tahun->status !== 'transisi') {
            abort(403, 'Naik kelas hanya boleh saat masa transisi.');
        }

        DB::transaction(function () use ($request, $tahun) {

            foreach ($request->siswa as $penempatanId => $value) {

                $item = PenempatanSiswa::where('id', $penempatanId)
                    ->where('tahun_ajaran_id', $tahun->id)
                    ->whereNull('tanggal_keluar')
                    ->first();

                if (!$item || $item->tanggal_keluar) {
                    continue;
                }

                // tutup lama
                $item->update([
                    'tanggal_keluar' => now()
                ]);

                $tujuan = $request->mapping[$item->kelas_id] ?? null;

                // kalau tidak ada tujuan → LULUS
                if (!$tujuan) {
                    continue;
                }

                // buat penempatan baru
                PenempatanSiswa::create([
                    'siswa_id'        => $item->siswa_id,
                    'kelas_id'        => $tujuan,
                    'tahun_ajaran_id' => $tahun->id,
                    'tanggal_masuk'   => now(),
                ]);
            }
        });

        return redirect()
            ->route('tu.penempatan-siswa.naik-kelas.index')
            ->with('success', 'Naik kelas berhasil diproses');
    }
    public function indexNaikKelas(Request $request)
    {
        $tahunAktif = TahunAjaran::aktif();

        return redirect()->route('tu.tahun.index')
                ->with('error', 'Silakan aktifkan tahun ajaran terlebih dahulu');
        }

        $tingkatList = \App\Models\KelasMaster::orderBy('tingkat')
            ->pluck('tingkat');

        $rombels = null;

        if ($request->filled('tingkat')) {

            $rombels = Kelas::with('kelasGrup.kelasMaster')
                ->whereHas('kelasGrup.kelasMaster', function ($q) use ($request) {
                    $q->where('tingkat', $request->tingkat);
                })
                ->whereHas('penempatan', function ($q) use ($tahunAktif) {
                    $q->where('tahun_ajaran_id', $tahunAktif->id)
                    ->whereNull('tanggal_keluar');
                })
                ->withCount([
                    'penempatan as siswa_count' => function ($q) use ($tahunAktif) {
                        $q->where('tahun_ajaran_id', $tahunAktif->id)
                        ->whereNull('tanggal_keluar');
                    }
                ])
                ->orderBy('nama_kelas')
                ->get();
        }

        return view('tu.penempatan-siswa.naik-kelas-index', compact(
            'tahunAktif',
            'tingkatList',
            'rombels'
        ));
    }
    public function mappingNaikKelas(Request $request)
    {
        $request->validate([
            'tingkat' => 'required|integer'
        ]);

        $tahunAktif = TahunAjaran::aktif();

        if (!$tahunAktif) {
            return redirect()->route('tu.tahun.index')
                ->with('error', 'Silakan aktifkan tahun ajaran terlebih dahulu');
        }

        $tingkatAsal = $request->tingkat;
        $tingkatTujuan = $tingkatAsal + 1;

        // Rombel asal
        $rombelsAsal = Kelas::withCount([
            'penempatan as siswa_count' => function ($q) use ($tahunAktif) {
                $q->where('tahun_ajaran_id', $tahunAktif->id)
                ->whereNull('tanggal_keluar');
            }
        ])
        ->whereHas('kelasGrup.kelasMaster', function ($q) use ($tingkatAsal) {
            $q->where('tingkat', $tingkatAsal);
        })
        ->orderBy('nama_kelas')
        ->get();

        // Rombel tujuan (tingkat berikutnya)
        $rombelsTujuan = Kelas::whereHas('kelasGrup.kelasMaster', function ($q) use ($tingkatTujuan) {
            $q->where('tingkat', $tingkatTujuan);
        })
        ->orderBy('nama_kelas')
        ->get();

        return view('tu.penempatan-siswa.naik-kelas-mapping', compact(
            'rombelsAsal',
            'rombelsTujuan',
            'tingkatAsal',
            'tingkatTujuan'
        ));
    }

    public function keluarkan($id)
    {
        $tahunAktif = TahunAjaran::aktif();
        
        if ($tahunAktif->status !== 'aktif') {
            abort(403, 'Tidak bisa mengeluarkan siswa di fase ini.');
        }

        $penempatan = PenempatanSiswa::where('tahun_ajaran_id', $tahunAktif->id)
            ->whereNull('tanggal_keluar')
            ->findOrFail($id);

        $penempatan->update([
            'tanggal_keluar' => now()
        ]);

        return back()->with('success', 'Siswa berhasil dikeluarkan dari rombel');
    }
}
