<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\KelasMaster;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\KelasGrup;

class KelasController extends Controller
{
    /**
     * INDEX — Grup Kelas berdasarkan Kelas Master
     */
   public function index()
{
    $grupKelas = DB::table('kelas_grup')
        ->join('kelas_master', 'kelas_master.id', '=', 'kelas_grup.kelas_master_id')
        ->join('tahun_ajaran', 'tahun_ajaran.id', '=', 'kelas_grup.tahun_ajaran_id')
        ->leftJoin('kelas', function ($join) {
            $join->on('kelas.kelas_master_id', '=', 'kelas_grup.kelas_master_id')
                 ->on('kelas.tahun_ajaran_id', '=', 'kelas_grup.tahun_ajaran_id')
                 ->where('kelas.nama_kelas', '!=', '-')
                 ->whereNull('kelas.deleted_at');
        })
        ->select(
            'kelas_grup.id',
            'kelas_master.tingkat',
            'kelas_grup.kelas_master_id',
            'kelas_grup.tahun_ajaran_id',
            'tahun_ajaran.tahun',
            DB::raw('COUNT(kelas.id) as jumlah_kelas')
        )
        ->groupBy(
            'kelas_grup.id',
            'kelas_master.tingkat',
            'kelas_grup.kelas_master_id',
            'kelas_grup.tahun_ajaran_id',
            'tahun_ajaran.tahun'
        )
        ->orderBy('kelas_master.tingkat')
        ->get();

    return view('tu.kelas.index', compact('grupKelas'));
}

    public function rombelIndex($kelasMasterId, $tahunAjaranId)
{
    $kelas = Kelas::with(['kelasMaster', 'tahunAjaran'])
        ->where('kelas_master_id', $kelasMasterId)
        ->where('tahun_ajaran_id', $tahunAjaranId)
        ->where('nama_kelas', '!=', '-') // ⬅️ ini penting
        ->orderBy('nama_kelas')
        ->get();

    return view('tu.kelas.rombel-index', compact('kelas'));
}


public function createRombel($kelasMasterId, $tahunAjaranId)
{
    $kelasMaster = KelasMaster::findOrFail($kelasMasterId);
    $tahunAjaran = TahunAjaran::findOrFail($tahunAjaranId);

    return view('tu.kelas.rombel-create', compact(
        'kelasMaster',
        'tahunAjaran'
    ));
}

public function storeRombel(Request $request, $kelasMasterId, $tahunAjaranId)
{
    $request->validate([
        'kode' => 'required|alpha|max:1'
    ]);

    $namaKelas = $kelasMasterId . strtoupper($request->kode);

    // cegah duplikat
    $exists = Kelas::where('kelas_master_id', $kelasMasterId)
        ->where('tahun_ajaran_id', $tahunAjaranId)
        ->where('nama_kelas', $namaKelas)
        ->exists();

    if ($exists) {
        return back()->with('error', 'Rombel sudah ada');
    }

    Kelas::create([
        'kelas_master_id' => $kelasMasterId,
        'tahun_ajaran_id' => $tahunAjaranId,
        'nama_kelas' => $namaKelas,
    ]);

    return redirect()->route('tu.kelas.rombel.index', [
        'kelas_master' => $kelasMasterId,
        'tahun_ajaran' => $tahunAjaranId
    ])->with('success', 'Rombel berhasil ditambahkan');
}


    /**
 * FORM CREATE GRUP KELAS
 */
public function createGrup()
{
    $kelasMaster = KelasMaster::orderBy('tingkat')->get();
    $tahunAjaran = TahunAjaran::orderBy('tahun', 'desc')->get();

    return view('tu.kelas.grup-create', compact('kelasMaster', 'tahunAjaran'));
}
/**
 * SIMPAN GRUP KELAS
 */
public function storeGrup(Request $request)
{
    $request->validate([
        'kelas_master_id' => 'required|exists:kelas_master,id',
        'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
    ]);

    $exists = KelasGrup::where('kelas_master_id', $request->kelas_master_id)
        ->where('tahun_ajaran_id', $request->tahun_ajaran_id)
        ->exists();

    if ($exists) {
        return back()->with('error', 'Grup kelas sudah ada');
    }

    KelasGrup::create([
        'kelas_master_id' => $request->kelas_master_id,
        'tahun_ajaran_id' => $request->tahun_ajaran_id,
    ]);

    return redirect()->route('tu.kelas.index')
        ->with('success', 'Grup kelas berhasil dibuat');
}


    /**
     * CREATE
     */
    public function create()
    {
        return view('tu.kelas.create', [
            'kelasMaster' => KelasMaster::orderBy('tingkat')->orderBy('kode')->get(),
            'tahunAjaran' => TahunAjaran::orderByDesc('tahun')->get(),
        ]);
    }

    /**
     * STORE
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kelas_master_id' => 'required|exists:kelas_master,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
        ]);

        Kelas::create($validated);

        return redirect()->route('tu.kelas.index')
            ->with('success', 'Kelas berhasil ditambahkan');
    }

    /**
     * EDIT
     */
    public function edit($id)
    {
        return view('tu.kelas.edit', [
            'kelas'        => Kelas::withTrashed()->findOrFail($id),
            'kelasMaster'  => KelasMaster::orderBy('tingkat')->get(),
            'tahunAjaran'  => TahunAjaran::orderByDesc('tahun')->get(),
        ]);
    }

    /**
     * UPDATE
     */
    public function update(Request $request, $id)
    {
        $kelas = Kelas::withTrashed()->findOrFail($id);

        $validated = $request->validate([
            'kelas_master_id' => 'required|exists:kelas_master,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
        ]);

        $kelas->update($validated);

        return redirect()->route('tu.kelas.index')
            ->with('success', 'Kelas berhasil diperbarui');
    }

    /**
     * SOFT DELETE
     */
    public function destroy($id)
    {
        Kelas::findOrFail($id)->delete();
        return back()->with('success', 'Data dipindahkan ke trash');
    }

    /**
     * RESTORE
     */
    public function restore($id)
    {
        Kelas::onlyTrashed()->findOrFail($id)->restore();
        return back()->with('success', 'Data berhasil direstore');
    }

    /**
     * FORCE DELETE
     */
    public function forceDelete($id)
    {
        Kelas::onlyTrashed()->findOrFail($id)->forceDelete();
        return back()->with('success', 'Data dihapus permanen');
    }

    /**
     * DETAIL (ROMBEL)
     */
    public function detail($id)
    {
        $kelas = Kelas::with(['kelasMaster', 'tahunAjaran', 'siswa'])
            ->withTrashed()
            ->findOrFail($id);

        return view('tu.kelas.detail', compact('kelas'));
    }
}
