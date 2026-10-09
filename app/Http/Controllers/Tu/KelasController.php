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
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KelasExport;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;


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

        $tahunAktif = TahunAjaran::current();

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
        $kelasGrup = KelasGrup::with('kelasMaster')->findOrFail($kelasGrupId);
        $validated = $this->validateNamaRombel($request, $kelasGrup);

        Kelas::create([
            'kelas_grup_id' => $kelasGrup->id,
            'nama_kelas' => $validated['nama_kelas'],
        ]);

        return redirect()
            ->route('tu.kelas.rombel.index', $kelasGrupId)
            ->with('success', 'Rombel berhasil ditambahkan');
    }

    public function editRombel($id)
    {
        $rombel = Kelas::with('kelasGrup.angkatan', 'kelasGrup.kelasMaster')
            ->findOrFail($id);

        abort_unless($rombel->kelasGrup?->kelasMaster, 404);

        return view('tu.kelas.rombel.edit', compact('rombel'));
    }

    public function updateRombel(Request $request, $id)
    {
        $rombel = Kelas::with('kelasGrup.kelasMaster')->findOrFail($id);
        $kelasGrup = $rombel->kelasGrup;

        abort_unless($kelasGrup?->kelasMaster, 404);

        $validated = $this->validateNamaRombel($request, $kelasGrup, $rombel);

        if (
            $rombel->penempatan()->exists() &&
            $validated['nama_kelas'] !== $rombel->nama_kelas
        ) {
            return back()->withErrors([
                'nama_kelas' => 'Nama rombel tidak dapat diubah karena sudah digunakan oleh penempatan siswa.',
            ])->withInput();
        }

        $rombel->update([
            'nama_kelas' => $validated['nama_kelas'],
        ]);

        return redirect()
            ->route('tu.kelas.rombel.index', $rombel->kelas_grup_id)
            ->with('success', 'Rombel berhasil diperbarui.');
    }

    private function validateNamaRombel(
        Request $request,
        KelasGrup $kelasGrup,
        ?Kelas $rombel = null
    ): array {
        $tingkat = strtoupper((string) $kelasGrup->kelasMaster?->tingkat);

        if ($tingkat === '') {
            throw ValidationException::withMessages([
                'kelas_grup_id' => 'KelasGrup harus memiliki tingkat kelas yang valid.',
            ]);
        }

        $submittedName = strtoupper(trim((string) $request->input('nama_kelas')));
        if (preg_match('/^' . preg_quote($tingkat, '/') . '-?([A-Z])$/', $submittedName, $matches)) {
            $request->merge([
                'nama_kelas' => $tingkat . $matches[1],
            ]);
        }

        $uniqueName = Rule::unique('kelas', 'nama_kelas')
            ->where(fn ($query) => $query->where('kelas_grup_id', $kelasGrup->id));

        if ($rombel) {
            $uniqueName->ignore($rombel->id);
        }

        $rules = [
            'nama_kelas' => [
                'required',
                'string',
                'max:50',
                'regex:/^' . preg_quote($tingkat, '/') . '[A-Z]$/',
                $uniqueName,
            ],
        ];

        if ($rombel) {
            $rules['kelas_grup_id'] = 'missing';
        }

        return $request->validate($rules, [
            'nama_kelas.regex' => "Nama rombel harus menggunakan tingkat {$tingkat}, misalnya {$tingkat}-A.",
            'nama_kelas.unique' => 'Nama rombel sudah digunakan dalam KelasGrup ini.',
            'kelas_grup_id.missing' => 'Rombel tidak dapat dipindahkan ke KelasGrup lain.',
        ]);
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
        $rombel = Kelas::findOrFail($id);

        abort_unless($rombel->kelas_grup_id, 404);

        if ($rombel->penempatan()->exists()) {
            return redirect()
                ->route('tu.kelas.rombel.index', $rombel->kelas_grup_id)
                ->with('error', 'Rombel tidak dapat dihapus karena sudah digunakan oleh penempatan siswa.');
        }

        $rombel->delete();

        return redirect()
            ->route('tu.kelas.rombel.index', $rombel->kelas_grup_id)
            ->with('success', 'Rombel berhasil dihapus.');
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

public function exportExcel(KelasGrup $kelasGrup)
{
    return Excel::download(
        new KelasExport($kelasGrup),
        'DATA SISWA KELAS '.$kelasGrup->kelasMaster->tingkat.
        ' ANGKATAN '.$kelasGrup->angkatan->tahun.'.xlsx'
    );
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
