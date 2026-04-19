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
            abort(500, 'Tahun ajaran aktif belum diset');
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

        DB::transaction(function () use ($request, $tahunAktif) {

            // Tutup penempatan aktif lama (jika ada)
            PenempatanSiswa::where('siswa_id', $request->siswa_id)
                ->whereNull('tanggal_keluar')
                ->update([
                    'tanggal_keluar' => now()
                ]);

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
            'tingkat_asal' => 'required|integer',
            'mapping'      => 'required|array'
        ]);

        $tahunAktif = TahunAjaran::aktif();

        if (!$tahunAktif) {
            abort(500, 'Tahun ajaran aktif belum diset');
        }

        DB::transaction(function () use ($request, $tahunAktif) {

            foreach ($request->mapping as $rombelAsalId => $rombelTujuanId) {

                // Ambil semua penempatan aktif di rombel asal
                $penempatanAktif = PenempatanSiswa::where('kelas_id', $rombelAsalId)
                    ->where('tahun_ajaran_id', $tahunAktif->id)
                    ->whereNull('tanggal_keluar')
                    ->get();

                foreach ($penempatanAktif as $item) {

                    // Tutup penempatan lama
                    $item->update([
                        'tanggal_keluar' => now()
                    ]);

                    // Buat penempatan baru
                    PenempatanSiswa::create([
                        'siswa_id'        => $item->siswa_id,
                        'kelas_id'        => $rombelTujuanId,
                        'tahun_ajaran_id' => $tahunAktif->id,
                        'tanggal_masuk'   => now(),
                    ]);
                }
            }
        });

        return redirect()
            ->route('tu.penempatan-siswa.naik-kelas.index')
            ->with('success', 'Naik kelas berhasil diproses');
    }
    public function indexNaikKelas(Request $request)
    {
        $tahunAktif = TahunAjaran::aktif();

        if (!$tahunAktif) {
            abort(500, 'Tahun ajaran aktif belum diset');
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
            abort(500, 'Tahun ajaran aktif belum diset');
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
        $penempatan = PenempatanSiswa::whereNull('tanggal_keluar')
            ->findOrFail($id);

        $penempatan->update([
            'tanggal_keluar' => now()
        ]);

        return back()->with('success', 'Siswa berhasil dikeluarkan dari rombel');
    }
}
