<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\PengampuMapel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PengampuMapelController extends Controller
{
    /**
     * LIST
     */
    public function index(Request $request)
    {
        $guru = Guru::with('pegawai')
            ->get()
            ->sortBy('pegawai.nama');

        $kelas = Kelas::orderBy('nama_kelas')->get();

        $mapel = MataPelajaran::orderBy('nama_mapel')->get();

        $guruTerpilih = null;
        $pengampuGuru = collect();

        if ($request->filled('guru_id')) {

            $guruTerpilih = Guru::with('pegawai')
                ->findOrFail($request->guru_id);

            $pengampuGuru = PengampuMapel::where(
                    'guru_id',
                    $guruTerpilih->id
                )
                ->where(
                    'tahun_ajaran_id',
                    current_tahun_id()
                )
                ->get();
        }

        $pengampu = PengampuMapel::with([
                'guru',
                'kelas',
                'mapel'
            ])
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->latest()
            ->get()
            ->groupBy('guru_id');

        return view(
            'superadmin.pengampu-mapel.index',
            compact(
                'guru',
                'kelas',
                'mapel',
                'pengampu',
                'guruTerpilih',
                'pengampuGuru'
            )
        );
    }

    /**
     * STORE
     */
    public function store(Request $request)
    {
        $request->validate([
            'guru_id' => 'required|exists:guru,id',
            'pengampu' => 'nullable|array',
            'pengampu.*' => 'array',
            'pengampu.*.kelas_id' => 'nullable|array',
            'pengampu.*.kelas_id.*' => 'exists:kelas,id',
        ]);

        $tahunAjaranId = current_tahun_id();
        $guruId = $request->guru_id;

        $selected = collect($request->input('pengampu', []))
            ->flatMap(function ($data, $mapelId) {
                return collect($data['kelas_id'] ?? [])
                    ->map(fn ($kelasId) => [
                        'mata_pelajaran_id' => (int) $mapelId,
                        'kelas_id' => (int) $kelasId,
                    ]);
            })
            ->unique(fn ($assignment) => $assignment['mata_pelajaran_id'] . '-' . $assignment['kelas_id'])
            ->values();

        $validMapelIds = MataPelajaran::whereIn(
            'id',
            $selected->pluck('mata_pelajaran_id')
        )->pluck('id');
        abort_unless($validMapelIds->count() === $selected->pluck('mata_pelajaran_id')->unique()->count(), 422);

        DB::transaction(function () use ($guruId, $tahunAjaranId, $selected) {
            $existing = PengampuMapel::where('guru_id', $guruId)
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->get();

            $selectedKeys = $selected
                ->map(fn ($assignment) => $assignment['mata_pelajaran_id'] . '-' . $assignment['kelas_id'])
                ->all();

            $existing
                ->filter(fn ($assignment) => ! in_array(
                    $assignment->mata_pelajaran_id . '-' . $assignment->kelas_id,
                    $selectedKeys,
                    true
                ))
                ->each
                ->delete();

            $existingKeys = $existing
                ->map(fn ($assignment) => $assignment->mata_pelajaran_id . '-' . $assignment->kelas_id)
                ->all();

            foreach ($selected as $assignment) {
                $key = $assignment['mata_pelajaran_id'] . '-' . $assignment['kelas_id'];

                if (! in_array($key, $existingKeys, true)) {
                    PengampuMapel::create([
                        'guru_id' => $guruId,
                        'mata_pelajaran_id' => $assignment['mata_pelajaran_id'],
                        'kelas_id' => $assignment['kelas_id'],
                        'tahun_ajaran_id' => $tahunAjaranId,
                    ]);
                }
            }
        });

        return redirect()
            ->route('superadmin.pengampu-mapel.index')
            ->with(
                'success',
                'Penugasan guru berhasil disimpan.'
            );
    }

    /**
     * DELETE
     */
    public function destroy(PengampuMapel $pengampuMapel)
    {
        PengampuMapel::where('guru_id', $pengampuMapel->guru_id)
            ->where('tahun_ajaran_id', current_tahun_id())
            ->delete();

        return back()->with(
            'success',
            'Pengampu mapel berhasil dihapus.'
        );
    }
}