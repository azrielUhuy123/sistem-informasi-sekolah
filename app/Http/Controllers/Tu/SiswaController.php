<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\PenempatanSiswa;
use App\Models\TahunAjaran;
use App\Models\Siswa;
use App\Models\Angkatan;
use App\Models\Kelas;
use Illuminate\Http\Request;
use App\Imports\SiswaImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\KelasGrup;
use Illuminate\Support\Facades\DB;

class SiswaController extends Controller
{
    /**
     * INDEX
     * - WAJIB pilih angkatan
     * - Kelas = ROMBEL
     */
   public function index(Request $request)
{
    $query = Siswa::query()
        ->with([
            'penempatanAktif.kelas.kelasGrup.kelasMaster',
            'penempatanAktif.kelas.kelasGrup.angkatan'
        ]);

    // =====================
    // FILTER ANGKATAN
    // =====================
    if ($request->angkatan) {
        $query->whereHas('penempatanAktif.kelas.kelasGrup', function ($q) use ($request) {
            $q->where('angkatan_id', $request->angkatan);
        });
    }

    // =====================
    // FILTER TINGKAT
    // =====================
    if ($request->tingkat) {
        $query->whereHas('penempatanAktif.kelas.kelasGrup.kelasMaster', function ($q) use ($request) {
            $q->where('tingkat', $request->tingkat);
        });
    }

    // =====================
    // FILTER ROMBEL
    // =====================
    if ($request->kelas) {
        $query->whereHas('penempatanAktif', function ($q) use ($request) {
            $q->where('kelas_id', $request->kelas);
        });
    }

    // =====================
    // SEARCH
    // =====================
    if ($request->search) {
        $query->where(function ($q) use ($request) {
            $q->where('nama', 'like', "%{$request->search}%")
              ->orWhere('nis', 'like', "%{$request->search}%");
        });
    }

    $siswa = $query->paginate(20)->withQueryString();

    $angkatan = Angkatan::orderByDesc('tahun')->get();

    return view('tu.siswa.index', compact(
        'siswa',
        'angkatan'
    ));
}

    /**
     * CREATE
     * - WAJIB kelas_id (rombel)
     */
    public function create(Request $request)
    {
        $kelasId = $request->get('kelas_id');

        if (!$kelasId) {
            return redirect()
                ->route('tu.siswa.index')
                ->with('warning', 'Silakan pilih kelas (rombel) terlebih dahulu');
        }

        $kelas = Kelas::with('kelasGrup.angkatan')->findOrFail($kelasId);

        return view('tu.siswa.create', compact('kelas'));
    }

    /**
     * STORE
     * - SIMPAN KE ROMBEL
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kelas_id'       => 'required|exists:kelas,id',
            'nama'           => 'required|string|max:255',
            'nis'            => 'required|numeric|unique:siswa,nis',
            'nisn'           => 'nullable|numeric|unique:siswa,nisn',
            'jenis_kelamin'  => 'required|in:L,P',
            'tanggal_lahir'  => 'nullable|date',
            'alamat'         => 'nullable|string',
            'nama_wali'      => 'nullable|string|max:255',
            'telepon_wali'   => 'nullable|string|max:20',
        ]);
        DB::transaction(function () use ($validated, $request) {
            $siswa = Siswa::create([
                'nama'           => $validated['nama'],
                'nis'            => $validated['nis'],
                'nisn'           => $validated['nisn'] ?? null,
                'jenis_kelamin'  => $validated['jenis_kelamin'],
                'tanggal_lahir'  => $validated['tanggal_lahir'] ?? null,
                'alamat'         => $validated['alamat'] ?? null,
                'nama_wali'      => $validated['nama_wali'] ?? null,
                'telepon_wali'   => $validated['telepon_wali'] ?? null,
            ]);
            $tahunAjaranAktif = \App\Models\TahunAjaran::where('aktif', 1)->firstOrFail();
            // buat penempatan aktif
            PenempatanSiswa::create([
                'siswa_id'        => $siswa->id,
                'kelas_id'        => $validated['kelas_id'],
                'tahun_ajaran_id' => TahunAjaran::where('aktif', 1)->first()->id,
                'tanggal_masuk'   => now(),
                'tanggal_keluar'  => null,
            ]);
        });

        // 🔥 Kalau request AJAX → return JSON
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Siswa berhasil ditambahkan'
            ]);
        }

        // 🔥 Kalau form biasa → redirect
        return redirect()
            ->route('tu.siswa.index', [
                'angkatan' => Kelas::find($request->kelas_id)->kelasGrup->angkatan_id,
                'kelas_id' => $request->kelas_id
            ])
            ->with('success', 'Siswa berhasil ditambahkan');

    }


    /**
 * AJAX: Ambil Grup Kelas berdasarkan Angkatan
 */
public function ajaxGrup(Request $request)
{
    return KelasGrup::with('kelasMaster')
        ->where('angkatan_id', $request->angkatan_id)
        ->get()
        ->map(function ($g) {
            return [
                'id' => $g->id,
                'label' => 'Kelas ' . $g->kelasMaster->tingkat
            ];
        });
}

public function ajaxKelas(Request $request)
{
    return Kelas::where('kelas_grup_id', $request->kelas_grup_id)
        ->orderBy('nama_kelas')
        ->get(['id', 'nama_kelas']);
}

public function ajaxTable(Request $request)
{
    $rows = Siswa::whereHas('penempatanAktif', function ($q) use ($request) {
        $q->where('kelas_id', $request->kelas_id);
    })
    ->with([
        'penempatanAktif.kelas.kelasGrup.angkatan'
    ])
    ->paginate(10);

    $columns = [
        ['key' => 'nama', 'label' => 'Nama'],
        ['key' => 'nis', 'label' => 'NIS'],
        ['key' => 'nisn', 'label' => 'NISN'],
        [
            'key' => 'penempatanAktif.kelas.nama_kelas',
            'label' => 'Kelas',
        ],
    ];

    $actions = [
        'detail' => 'tu.siswa.show',
        'edit'   => 'tu.siswa.edit',
        'delete' => 'tu.siswa.destroy',
    ];

    return view('components.crud.table-wrapper', [
        'rows'     => $rows,
        'columns'  => $columns,
        'actions'  => $actions,
        'crudMode' => 'normal',
    ]);
}



    /**
     * show
     */
    public function show($id)
    {
        $siswa = Siswa::withTrashed()
            ->with([
                'penempatanAktif.kelas.kelasGrup.angkatan',
                'penempatan' => fn($q) => $q
                ->orderByDesc('tanggal_masuk')
                ->orderByDesc('id')
            ])
            ->findOrFail($id);

        return view('tu.siswa.detail', compact('siswa'));
    }

    /**
     * EDIT
     */
    public function edit($id)
    {
        $siswa = Siswa::withTrashed()
            ->with('penempatanAktif.kelas.kelasGrup.angkatan')
            ->findOrFail($id);

        $kelas = Kelas::orderBy('nama_kelas')->get();

        return view('tu.siswa.edit', compact('siswa','kelas'));
    }

    /**
     * UPDATE
     */
    public function update(Request $request, $id)
    {
        $siswa = Siswa::withTrashed()->findOrFail($id);

        $request->validate([
            'kelas_id'       => 'required|exists:kelas,id',
            'nama'           => 'required|string|max:255',
            'nis'            => 'required|numeric|unique:siswa,nis,' . $siswa->id,
            'nisn'           => 'nullable|numeric|unique:siswa,nisn,' . $siswa->id,
            'jenis_kelamin'  => 'required|in:L,P',
            'tanggal_lahir'  => 'nullable|date',
            'alamat'         => 'nullable|string',
            'nama_wali'      => 'nullable|string|max:255',
            'telepon_wali'   => 'nullable|string|max:20',
        ]);

        $siswa->update($request->only([
            'nama',
            'nis',
            'nisn',
            'jenis_kelamin',
            'tanggal_lahir',
            'alamat',
            'nama_wali',
            'telepon_wali',
        ]));
        $penempatanAktif = $siswa->penempatanAktif;

        if ($penempatanAktif && $penempatanAktif->kelas_id != $request->kelas_id) {

            $penempatanAktif->update([
                'tanggal_keluar' => now()
            ]);

            PenempatanSiswa::create([
                'siswa_id'        => $siswa->id,
                'kelas_id'        => $request->kelas_id,
                'tahun_ajaran_id' => $penempatanAktif->tahun_ajaran_id,
                'tanggal_masuk'   => now(),
                'tanggal_keluar'  => null,
            ]);
        }

        return back()->with('success', 'Data siswa berhasil diperbarui');
    }

    /**
     * SOFT DELETE / RESTORE / FORCE DELETE
     * (TIDAK DIUBAH)
     */
    public function destroy($id)
    {
        Siswa::findOrFail($id)->delete();
        return back()->with('success', 'Siswa dipindahkan ke trash');
    }

    public function restore($id)
    {
        Siswa::withTrashed()->findOrFail($id)->restore();
        return back()->with('success', 'Data siswa berhasil direstore');
    }

    public function forceDelete($id)
    {
        Siswa::withTrashed()->findOrFail($id)->forceDelete();
        return back()->with('success', 'Data siswa dihapus permanen');
    }

    /**
     * IMPORT EXCEL (SUDAH BENAR)
     */
    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {

            Excel::import(new SiswaImport, $request->file('file'));

            return back()->with('success', 'Import berhasil.');

        } catch (\Exception $e) {

            return back()->with('error', $e->getMessage());
        }
    }
    public function bulkAction(Request $request)
    {
        $ids = $request->ids;
        $action = $request->action;

        if (!$ids || count($ids) == 0) {
            return back()->with('error','Tidak ada siswa dipilih');
        }

        switch ($action) {

            case 'nonaktifkan':

                Siswa::whereIn('id',$ids)->update([
                    'deleted_at' => now()
                ]);

            break;

            case 'pindah_rombel':

                return back()->with('info','Fitur pindah rombel belum diimplementasikan');

            break;

        }

        return back()->with('success','Bulk action berhasil');
    }
}
