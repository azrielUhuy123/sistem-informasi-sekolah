<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\HariSekolah;
use App\Models\JamPelajaran;
use App\Models\PengajuanJadwal;
use App\Services\JadwalDraftService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PengajuanJadwalController extends Controller
{
    public function index()
    {
        $guru = Auth::user()->guru;

        abort_unless($guru, 403, 'Akun guru belum terhubung.');

        $items = PengajuanJadwal::with([
            'kelas',
            'mapel',
            'hari',
            'jamPelajaran',
        ])
            ->where('guru_id', $guru->id)
            ->latest()
            ->get();

        return view(
            'guru.pengajuan-jadwal.index',
            compact('items')
        );
    }

    public function create(JadwalDraftService $drafts)
    {
        $guru = Auth::user()->guru;

        abort_unless($guru, 403, 'Akun guru belum terhubung.');

        $options = $drafts->optionsFor($guru);
        $kelas = $options->pluck('kelas')->filter()->unique('id')->values();
        $mapel = $options->pluck('mapel')->filter()->unique('id')->values();

        return view('guru.pengajuan-jadwal.create', [
            'kelas' => $kelas,
            'mapel' => $mapel,
            'hari' => HariSekolah::all(),
            'jam' => JamPelajaran::orderBy('jam_ke')->get(),
        ]);
    }

    public function store(Request $request, JadwalDraftService $drafts)
    {
        $attributes = $request->validate([
            'kelas_id' => 'required|integer|exists:kelas,id',
            'mapel_id' => 'required|integer|exists:mata_pelajaran,id',
            'hari_id' => 'required|integer|exists:hari_sekolah,id',
            'jam_pelajaran_id' => 'required|integer|exists:jam_pelajaran,id',
        ]);

        $guru = Auth::user()->guru;

        abort_unless($guru, 403, 'Akun guru belum terhubung.');

        $error = $drafts->createDraft($guru, $attributes);

        if ($error) {
            return back()->withInput()->with('error', $error);
        }

        return redirect()
            ->route('guru.pengajuan-jadwal.index')
            ->with('success', 'Pengajuan jadwal berhasil dibuat');
    }
}