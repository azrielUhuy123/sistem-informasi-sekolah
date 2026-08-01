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
        $tahunAktif = TahunAjaran::current();

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

        $tahunAktif = TahunAjaran::current();

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
                
        if ($exists) {
            return back()->withErrors([
                'siswa_id' => 'Siswa sudah punya penempatan aktif di tahun ini'
            ]);
        }

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
            'siswa' => 'required|array'
        ]);

        $tahunAktif = TahunAjaran::current();

        $tahunSebelumnya = TahunAjaran::where('status', 'selesai')
            ->latest('id')
            ->first();

        if (!$tahunAktif || !$tahunSebelumnya) {
            abort(500, 'Tahun ajaran tidak valid');
        }

        DB::transaction(function () use ($request, $tahunAktif, $tahunSebelumnya) {

            foreach ($request->siswa as $penempatanId => $value) {

                $item = PenempatanSiswa::with('kelas.kelasGrup.kelasMaster')
                    ->where('id', $penempatanId)
                    ->where('tahun_ajaran_id', $tahunSebelumnya->id)
                    ->whereNull('tanggal_keluar')
                    ->first();

                if (!$item) continue;

                // 🔒 tutup penempatan lama
                $item->update([
                    'tanggal_keluar' => now()
                ]);

                // 🎯 AUTO MAPPING
                $tingkatSekarang = $item->kelas->kelasGrup->kelasMaster->tingkat;
                $tingkatTujuan = $tingkatSekarang + 1;

                $kelasTujuan = Kelas::whereHas('kelasGrup.kelasMaster', function ($q) use ($tingkatTujuan) {
                    $q->where('tingkat', $tingkatTujuan);
                })
                ->where('nama_kelas', str_replace($tingkatSekarang, $tingkatTujuan, $item->kelas->nama_kelas))
                ->first();

                // ❗ kalau tidak ada (misal lulus)
                if (!$kelasTujuan) continue;

                // 🆕 buat penempatan baru
                PenempatanSiswa::create([
                    'siswa_id'        => $item->siswa_id,
                    'kelas_id'        => $kelasTujuan->id,
                    'tahun_ajaran_id' => $tahunAktif->id,
                    'tanggal_masuk'   => now(),
                ]);
            }
        });

        return redirect()
            ->route('tu.penempatan-siswa.naik-kelas.index')
            ->with('success', 'Naik kelas berhasil diproses otomatis');
    }
    public function indexNaikKelas(Request $request)
    {
        $tahunSebelumnya = TahunAjaran::where('status', 'selesai')
            ->latest('id')
            ->first();

        if (!$tahunSebelumnya) {
            return redirect()->route('tu.tahun.index')
                ->with('error', 'Harus ada tahun ajaran selesai dulu.');
        }

        $tahunAktif = TahunAjaran::current();

        if (!$tahunAktif) {
            return redirect()->route('tu.tahun.index')
                ->with('error', 'Tidak ada tahun ajaran aktif.');
        }

        $tingkatList = \App\Models\KelasMaster::orderBy('tingkat')
            ->pluck('tingkat');

        $rombels = null;

        if ($request->filled('tingkat')) {

            $rombels = Kelas::with('kelasGrup.kelasMaster')
                ->whereHas('kelasGrup.kelasMaster', function ($q) use ($request) {
                    $q->where('tingkat', $request->tingkat);
                })
                ->whereHas('penempatan', function ($q) use ($tahunSebelumnya) {
                    $q->where('tahun_ajaran_id', $tahunSebelumnya->id)
                    ->whereNull('tanggal_keluar');
                })
                ->withCount([
                    'penempatan as siswa_count' => function ($q) use ($tahunSebelumnya) {
                        $q->where('tahun_ajaran_id', $tahunSebelumnya->id)
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
        $tahunSebelumnya = TahunAjaran::where('status', 'selesai')
            ->latest('id')
            ->first();

        if (!$tahunSebelumnya) {
            return redirect()->route('tu.tahun.index')
                ->with('error', 'Harus ada tahun ajaran selesai dulu.');
        }

        $request->validate([
            'tingkat' => 'required|integer'
        ]);

        $tahunAktif = TahunAjaran::current();

        if (!$tahunAktif) {
            return redirect()->route('tu.tahun.index')
                ->with('error', 'Tidak ada tahun ajaran aktif.');
        }

        $tingkatAsal = $request->tingkat;
        $tingkatTujuan = $tingkatAsal + 1;

        $rombelsAsal = Kelas::whereHas('kelasGrup.kelasMaster', function ($q) use ($tingkatAsal) {
                $q->where('tingkat', $tingkatAsal);
            })
            ->orderBy('nama_kelas')
            ->get();

        foreach ($rombelsAsal as $rombel) {
            $rombel->siswaAktif = PenempatanSiswa::with('siswa')
                ->where('kelas_id', $rombel->id)
                ->where('tahun_ajaran_id', $tahunSebelumnya->id)
                ->whereNull('tanggal_keluar')
                ->get();
        }

        $rombelsTujuan = Kelas::whereHas('kelasGrup.kelasMaster', function ($q) use ($tingkatTujuan) {
            $q->where('tingkat', $tingkatTujuan);
        })
        ->orderBy('nama_kelas')
        ->get();

        return view('tu.penempatan-siswa.naik-kelas-mapping', compact(
            'rombelsAsal',
            'rombelsTujuan',
            'tingkatAsal',
            'tingkatTujuan',
            'tahunAktif'
        ));
    }
    public function edit(PenempatanSiswa $penempatan)
    {
        $tahunAktif = TahunAjaran::current();

        if (!$tahunAktif || $tahunAktif->status !== 'aktif') {
            return back()->with('error', 'Mutasi hanya dapat dilakukan pada tahun ajaran aktif.');
        }

        $penempatan->load([
            'siswa',
            'kelas.kelasGrup.angkatan'
        ]);

        $kelas = Kelas::orderBy('nama_kelas')->get();

        return view('tu.penempatan-siswa.edit', compact(
            'penempatan',
            'kelas',
            'tahunAktif'
        ));
    }
    public function update(Request $request, PenempatanSiswa $penempatan)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $tahunAktif = TahunAjaran::current();

        if (!$tahunAktif || $tahunAktif->status !== 'aktif') {
            return back()->with('error', 'Mutasi hanya dapat dilakukan pada tahun ajaran aktif.');
        }

        if ($penempatan->kelas_id == $request->kelas_id) {
            return back()->with('error', 'Siswa sudah berada di kelas tersebut.');
        }

        DB::transaction(function () use ($request, $penempatan) {

            // tutup penempatan lama
            $penempatan->update([
                'tanggal_keluar' => now()
            ]);

            // buat penempatan baru
            PenempatanSiswa::create([
                'siswa_id'        => $penempatan->siswa_id,
                'kelas_id'        => $request->kelas_id,
                'tahun_ajaran_id' => $penempatan->tahun_ajaran_id,
                'tanggal_masuk'   => now(),
                'tanggal_keluar'  => null,
            ]);
        });

        return redirect()
            ->route('tu.siswa.index', $penempatan->siswa_id)
            ->with('success', 'Siswa berhasil dipindahkan ke kelas baru.');
    }

    public function keluarkan(PenempatanSiswa $penempatan)
    {
        $tahunAktif = TahunAjaran::current();

        if ($tahunAktif->status !== 'aktif') {
            abort(403, 'Tidak bisa mengeluarkan siswa di fase ini.');
        }

        if ($penempatan->tahun_ajaran_id != $tahunAktif->id) {
            abort(404);
        }

        if ($penempatan->tanggal_keluar) {
            return back()->with('warning', 'Penempatan sudah tidak aktif.');
        }

        $penempatan->update([
            'tanggal_keluar' => now(),
        ]);

        return back()->with('success', 'Siswa berhasil dikeluarkan dari rombel.');
    }


}
