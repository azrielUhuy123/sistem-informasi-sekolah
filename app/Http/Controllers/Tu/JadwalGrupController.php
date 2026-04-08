<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalGrup;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\KelasMaster;
use App\Models\JadwalRombel;
use App\Models\Jadwal;

class JadwalGrupController extends Controller
{
    public function index()
{
    $grup = JadwalGrup::with('kelasMaster', 'tahunAjaran')->get();

    return view('tu.jadwal.index', compact('grup'));
}

    public function create()
    {
        $kelas = KelasMaster::all();
        $tahun = TahunAjaran::all();

        return view('tu.jadwal.create', compact('kelas', 'tahun'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelas_master_id' => 'required',
            'tahun_ajaran_id' => 'required',
        ]);

        JadwalGrup::create([
            'kelas_master_id' => $request->kelas_master_id,
            'tahun_ajaran_id' => $request->tahun_ajaran_id,
        ]);

        return redirect()
            ->route('tu.jadwal.index')
            ->with('success', 'Grup jadwal berhasil dibuat');
    }

    public function edit($id)
    {
        $grup = JadwalGrup::findOrFail($id);
        $kelas = KelasMaster::all();
        $tahun = TahunAjaran::all();

        return view('tu.jadwal.edit', compact('grup', 'kelas', 'tahun'));
    }

    public function update(Request $request, $id)
    {
        $grup = JadwalGrup::findOrFail($id);

        $request->validate([
            'kelas_master_id' => 'required',
            'tahun_ajaran_id' => 'required',
        ]);

        $grup->update([
            'kelas_master_id' => $request->kelas_master_id,
            'tahun_ajaran_id' => $request->tahun_ajaran_id,
        ]);

        return redirect()
            ->route('tu.jadwal.index')
            ->with('success', 'Grup berhasil diupdate');
    }

    public function destroy($id)
{
    $grup = JadwalGrup::findOrFail($id);
    $grup->delete();

    return redirect()
        ->route('tu.jadwal.index')
        ->with('success', 'Grup berhasil dihapus');
}

    
    public function detail($id)
    {
        $grup = JadwalGrup::with(['kelasMaster', 'tahunAjaran', 'rombel'])
                    ->findOrFail($id);

        $jadwal = Jadwal::where('jadwal_grup_id', $id)
                    ->with(['guru', 'mapel'])
                    ->get();

        return view('tu.jadwal.detail', compact('grup', 'jadwal'));
    }

    public function createRombel($id)
{
    $grup = JadwalGrup::with('kelasMaster', 'tahunAjaran')->findOrFail($id);
    $tingkat = $grup->kelasMaster->tingkat;

    $rombelOptions = \App\Models\Kelas::where('kelas_grup_id', $tingkat)->get();

    $mapel = \App\Models\MataPelajaran::all();
    $guru = \App\Models\Guru::all();

    return view('tu.jadwal.rombel.create', compact(
        'grup',
        'rombelOptions',
        'mapel',
        'guru'
    ));
}

    public function storeRombel(Request $request, $id)
    {
        $request->validate([
            'nama_rombel' => 'required'
        ]);

        JadwalRombel::create([
            'jadwal_grup_id' => $id,
            'nama_rombel' => $request->nama_rombel
        ]);

        return redirect()
            ->route('tu.jadwal.grup.detail', $id)
            ->with('success', 'Rombel berhasil ditambahkan');
    }

    public function simpanSemua(Request $request, $id)
    {
        
        $request->validate([
            'rombel_id' => 'required|array',
            'jam_mulai' => 'required|array',
            'jam_selesai' => 'required|array',
            'mapel_id' => 'required|array',
            'guru_id' => 'required|array',
        ]);

        foreach ($request->rombel as $index => $rombelId) {

    if (!$request->jam_mulai[$index]) continue;

    Jadwal::create([
        'jadwal_grup_id' => $id,
        'kelas_id' => $rombelId,
        'jam_mulai' => $request->jam_mulai[$index],
        'jam_selesai' => $request->jam_selesai[$index],
        'mapel_id' => $request->mapel_id[$index],
        'guru_id' => $request->guru_id[$index],
        'hari' => 'Senin',
    ]);
}

        return redirect()
            ->route('jadwal.detail', $id)
            ->with('success', 'Jadwal berhasil disimpan!');
    }
}