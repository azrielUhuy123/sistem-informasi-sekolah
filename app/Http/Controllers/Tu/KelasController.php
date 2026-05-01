<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\KelasGrup;
use App\Models\KelasMaster;
use App\Models\Angkatan;
use App\Models\PenempatanSiswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\WaliKelas;
use App\Models\Guru;

class KelasController extends Controller
{
    /**
     * =====================================================
     * INDEX — LIST GRUP KELAS (PER TINGKAT & ANGKATAN)
     * =====================================================
     */
    public function index()
    {
        $grupKelas = KelasGrup::query()
            ->with(['angkatan', 'kelasMaster'])
            ->withCount([
                'kelas as jumlah_kelas' => function ($q) {
                    $q->whereNull('deleted_at');
                }
            ])
            ->orderBy('kelas_master_id')
            ->get();

        $tahunAktif = TahunAjaran::current()->first();

        return view('tu.kelas.index', compact(
            'grupKelas',
            'tahunAktif'
        ));
    }

    /**
     * =====================================================
     * ROMBEL INDEX — LIST ROMBEL DALAM 1 GRUP KELAS
     * =====================================================
     */
  public function rombelIndex($kelasGrupId)
{
    $kelasGrup = KelasGrup::with(['angkatan', 'kelasMaster'])
        ->findOrFail($kelasGrupId);

    $tahunAktif = TahunAjaran::current();

    if (!$tahunAktif) {
        abort(500, 'Tahun ajaran aktif belum diset');
    }

    $rombels = Kelas::where('kelas_grup_id', $kelasGrupId)
        ->with([
            'waliKelas.guru' // 🔥 TAMBAHAN PENTING
        ])
        ->withCount([
            'penempatan as siswa_count' => function ($q) use ($tahunAktif) {
                $q->where('tahun_ajaran_id', $tahunAktif->id)
                  ->whereNull('tanggal_keluar');
            }
        ])
        ->orderBy('nama_kelas')
        ->get();

    return view('tu.kelas.rombel.index', compact(
        'kelasGrup',
        'rombels'
    ));
}

    /**
     * =====================================================
     * CREATE ROMBEL
     * =====================================================
     */
    public function createRombel($kelasGrupId)
    {
        $kelasGrup = KelasGrup::with(['angkatan', 'kelasMaster'])
            ->findOrFail($kelasGrupId);

        return view('tu.kelas.rombel.create', compact('kelasGrup'));
    }

    /**
     * =====================================================
     * STORE ROMBEL
     * =====================================================
     */
    public function storeRombel(Request $request, $kelasGrupId)
    {
        $request->validate([
            'kode' => 'required|string|max:1|alpha'
        ]);

        $kelasGrup = KelasGrup::with('kelasMaster')->findOrFail($kelasGrupId);

        // contoh: tingkat 1 + A = 1A
        $namaKelas = $kelasGrup->kelasMaster->tingkat . strtoupper($request->kode);

        // cegah duplikat rombel
        if (
            Kelas::where('kelas_grup_id', $kelasGrupId)
                ->where('nama_kelas', $namaKelas)
                ->exists()
        ) {
            return back()->with('error', 'Rombel sudah ada');
        }

        Kelas::create([
            'kelas_grup_id' => $kelasGrupId,
            'nama_kelas'    => $namaKelas,
        ]);

        return redirect()
            ->route('tu.kelas.rombel.index', $kelasGrupId)
            ->with('success', 'Rombel berhasil ditambahkan');
    }

    /**
     * =====================================================
     * CREATE GRUP KELAS
     * =====================================================
     */
    public function createGrup()
    {
        return view('tu.kelas.grup-create', [
            'angkatan'     => Angkatan::orderByDesc('tahun')->get(),
            'kelasMaster' => KelasMaster::orderBy('tingkat')->get(),
        ]);
    }

    /**
     * =====================================================
     * STORE GRUP KELAS
     * =====================================================
     */
    public function storeGrup(Request $request)
    {
        $request->validate([
            'kelas_master_id' => 'required|exists:kelas_master,id',
            'angkatan_id'     => 'required|exists:angkatan,id',
        ]);

        // cegah duplikat grup
        $exists = KelasGrup::where('kelas_master_id', $request->kelas_master_id)
            ->where('angkatan_id', $request->angkatan_id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Grup kelas sudah ada');
        }

        KelasGrup::create([
            'kelas_master_id' => $request->kelas_master_id,
            'angkatan_id'     => $request->angkatan_id,
        ]);

        return redirect()
            ->route('tu.kelas.index')
            ->with('success', 'Grup kelas berhasil dibuat');
    }

    /**
     * =====================================================
     * DELETE / RESTORE / FORCE DELETE ROMBEL
     * =====================================================
     */
    public function destroy($id)
    {
        Kelas::findOrFail($id)->delete();
        return back()->with('success', 'Rombel dipindahkan ke trash');
    }

    public function restore($id)
    {
        Kelas::onlyTrashed()->findOrFail($id)->restore();
        return back()->with('success', 'Rombel berhasil direstore');
    }

    public function forceDelete($id)
    {
        Kelas::onlyTrashed()->findOrFail($id)->forceDelete();
        return back()->with('success', 'Rombel dihapus permanen');
    }
    public function showRombel($id)
    {
        $tahunAktif = TahunAjaran::current();

        if (!$tahunAktif) {
            abort(500, 'Tahun ajaran aktif belum diset');
        }

        $rombel = Kelas::with('kelasGrup.angkatan')
            ->findOrFail($id);
        $baseQuery = PenempatanSiswa::where('kelas_id', $id)
            ->where('tahun_ajaran_id', $tahunAktif->id)
            ->whereNull('tanggal_keluar');
        $penempatan = $baseQuery->with('siswa')
            ->latest()
            ->paginate(15);

        $totalSiswa = (clone $baseQuery)->count();

        $totalLaki = (clone $baseQuery)
            ->whereHas('siswa', fn($q) => $q->where('jenis_kelamin', 'L'))
            ->count();

        $totalPerempuan = (clone $baseQuery)
            ->whereHas('siswa', fn($q) => $q->where('jenis_kelamin', 'P'))
            ->count();
        $persenLaki = $totalSiswa > 0
            ? round(($totalLaki / $totalSiswa) * 100)
            : 0;

        $persenPerempuan = $totalSiswa > 0
            ? round(($totalPerempuan / $totalSiswa) * 100)
            : 0;

        return view('tu.kelas.rombel.show', [
            'rombel' => $rombel,
            'siswa' => $penempatan,
            'totalSiswa' => $totalSiswa,
            'totalLaki' => $totalLaki,
            'totalPerempuan' => $totalPerempuan,
            'persenLaki' => $persenLaki,
            'persenPerempuan' => $persenPerempuan,
        ]);
    }

    public function wali($id)
    {
        $kelas = Kelas::with('waliKelas')->findOrFail($id);
        $gurus = Guru::all();

        $waliGuruIds = \App\Models\WaliKelas::pluck('guru_id')->toArray();

        return view('tu.kelas.wali', compact(
            'kelas',
            'gurus',
            'waliGuruIds'
        ));
    }


public function storeWali(Request $request, $id)
{
    $request->validate([
        'guru_id' => 'required|exists:guru,id'
    ]);

    
    $exists = \App\Models\WaliKelas::where('guru_id', $request->guru_id)
        ->where('kelas_id', '!=', $id)
        ->exists();

    if ($exists) {
        return back()->withErrors([
            'guru_id' => 'Guru sudah menjadi wali kelas lain'
        ])->withInput();
    }

    // hapus lama
    \App\Models\WaliKelas::where('kelas_id', $id)->delete();

    // simpan baru
    \App\Models\WaliKelas::create([
        'kelas_id' => $id,
        'guru_id' => $request->guru_id,
    ]);

    return redirect()
        ->route('tu.kelas.rombel.index', $request->kelas_grup_id)
        ->with('success', 'Wali kelas berhasil disimpan');
    
}

}
