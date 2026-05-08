<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\PengajuanJadwal;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\HariSekolah;
use App\Models\JamPelajaran;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class PengajuanJadwalController extends Controller
{
    public function index()
    {
        $items = PengajuanJadwal::latest()->get();

        return view(
            'guru.pengajuan-jadwal.index',
            compact('items')
        );
    }

    public function create()
    {
        $selectedKelas = request('kelas_id');
        $selectedHari = request('hari_id');
        $selectedJam = request('jam_id');
        
        return view('guru.pengajuan-jadwal.create', [
            'kelas' => Kelas::all(),
            'mapel' => MataPelajaran::all(),
            'hari' => HariSekolah::all(),
            'jam' => JamPelajaran::orderBy('jam_ke')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required',
            'mapel_id' => 'required',
            'hari_id' => 'required',
            'jam_pelajaran_id' => 'required',
        ]);

        PengajuanJadwal::create([
            'guru_id' => auth()->user()->guru_id,
            'kelas_id' => $request->kelas_id,
            'mapel_id' => $request->mapel_id,
            'hari_id' => $request->hari_id,
            'jam_pelajaran_id' => $request->jam_pelajaran_id,
            'tahun_ajaran_id' => TahunAjaran::aktif()->id,
            'status' => 'draft',
        ]);

        return redirect()
            ->route('guru.pengajuan-jadwal.index')
            ->with('success', 'Pengajuan jadwal berhasil dibuat');
    }
}