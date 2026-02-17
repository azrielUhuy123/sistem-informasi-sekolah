<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\KelasGrup;
use App\Models\KelasMaster;
use App\Models\Angkatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            ->join('angkatan', 'angkatan.id', '=', 'kelas_grup.angkatan_id')
            ->join('kelas_master', 'kelas_master.id', '=', 'kelas_grup.kelas_master_id')
            ->leftJoin('kelas', function ($join) {
                $join->on('kelas.kelas_grup_id', '=', 'kelas_grup.id')
                     ->whereNull('kelas.deleted_at');
            })
            ->select(
                'kelas_grup.id',
                'kelas_master.tingkat as tingkat',
                'angkatan.tahun',
                'angkatan.keterangan',
                DB::raw('COUNT(kelas.id) as jumlah_kelas')
            )
            ->groupBy(
                'kelas_grup.id',
                'kelas_master.tingkat',
                'angkatan.tahun',
                'angkatan.keterangan'
            )
            ->orderBy('kelas_master.tingkat')
            ->orderBy('angkatan.tahun', 'desc')
            ->get();

        return view('tu.kelas.index', compact('grupKelas'));
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

        $rombels = Kelas::withCount('siswa')
            ->where('kelas_grup_id', $kelasGrupId)
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
        $rombel = \App\Models\Kelas::with('kelasGrup.angkatan')
            ->withCount('siswa')
            ->findOrFail($id);

        $siswa = \App\Models\Siswa::where('kelas_id', $id)
            ->latest()
            ->paginate(15);

        $totalSiswa = \App\Models\Siswa::where('kelas_id', $id)->count();
        $totalLaki  = \App\Models\Siswa::where('kelas_id', $id)
                        ->where('jenis_kelamin', 'L')->count();
        $totalPerempuan = \App\Models\Siswa::where('kelas_id', $id)
                        ->where('jenis_kelamin', 'P')->count();

        $persenLaki = $totalSiswa > 0 ? round(($totalLaki / $totalSiswa) * 100) : 0;
        $persenPerempuan = $totalSiswa > 0 ? round(($totalPerempuan / $totalSiswa) * 100) : 0;

        return view('tu.kelas.rombel.show', compact(
            'rombel',
            'siswa',
            'totalSiswa',
            'totalLaki',
            'totalPerempuan',
            'persenLaki',
            'persenPerempuan'
        ));
    }


}
