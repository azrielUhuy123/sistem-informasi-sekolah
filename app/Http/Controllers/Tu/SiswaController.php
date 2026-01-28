<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;
use App\Imports\SiswaImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Angkatan;

class SiswaController extends Controller
{
    /**
     * INDEX
     * - WAJIB pilih angkatan
     * - Support soft delete filter
     * - Aman walau belum pilih angkatan
     */
    public function index(Request $request)
{
    $angkatan = $request->get('angkatan');
    $kelasId  = $request->get('kelas_id');
    $deleted  = $request->deleted ?? 'active';

    // ===============================
    // LIST ANGKATAN (DROPDOWN)
    // ===============================
    $listAngkatan = Angkatan::query()
    ->where('aktif', true)
    ->orderBy('tahun', 'desc')
    ->get(); 

    // ===============================
    // LIST KELAS (KOSONG DEFAULT)
    // ===============================
    $listKelas = collect();

    // ===============================
    // BASE QUERY (DEFAULT KOSONG)
    // ===============================
    $query = Siswa::query()->whereRaw('1 = 0');

    // ===============================
    // JIKA ANGKATAN DIPILIH
    // ===============================
    if ($angkatan) {

        $query = match ($deleted) {
            'only' => Siswa::onlyTrashed(),
            'all'  => Siswa::withTrashed(),
            default => Siswa::query(),
        };

        // filter angkatan
        $query->where('angkatan', $angkatan);

        // load relasi
        $query->with([
            'penempatanAktif.kelas',
            'penempatanAktif.tahunAjaran'
        ]);

        // ===============================
        // LIST KELAS DARI PENEMPATAN
        // ===============================
        $listKelas = \App\Models\Kelas::whereHas('penempatanSiswa', function ($q) use ($angkatan) {
            $q->whereHas('siswa', function ($s) use ($angkatan) {
                $s->where('angkatan', $angkatan);
            })->where('status', 'aktif');
        })->orderBy('nama_kelas')->get();

        // ===============================
        // FILTER KELAS (JIKA DIPILIH)
        // ===============================
        if ($kelasId) {
            $query->whereHas('penempatanAktif', function ($q) use ($kelasId) {
                $q->where('kelas_id', $kelasId);
            });
        }

        // ===============================
        // SEARCH
        // ===============================
        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', "%{$request->q}%")
                  ->orWhere('nis', 'like', "%{$request->q}%")
                  ->orWhere('nisn', 'like', "%{$request->q}%");
            });
        }

        // ===============================
        // SORTING
        // ===============================
        $sort  = $request->sort ?? 'nama';
        $order = $request->order ?? 'asc';

        $allowedSort = ['id', 'nama', 'nis', 'nisn'];
        if (!in_array($sort, $allowedSort)) {
            $sort = 'nama';
        }

        $query->orderBy($sort, $order);
    }

    // ===============================
    // PAGINATION
    // ===============================
    $siswa = $query->paginate(15)->withQueryString();

    return view('tu.siswa.index', [
        'siswa'        => $siswa,
        'listAngkatan' => $listAngkatan,
        'listKelas'    => $listKelas,
        'angkatan'     => $angkatan,
        'kelasId'      => $kelasId,
        'title'        => 'Manajemen Data Siswa',
    ]);
}


    /**
     * CREATE
     * - WAJIB membawa parameter angkatan
     */
    public function create(Request $request)
    {
        $angkatan = $request->get('angkatan');

        if (!$angkatan) {
            return redirect()
                ->route('tu.siswa.index')
                ->with('warning', 'Silakan pilih angkatan terlebih dahulu');
        }

        return view('tu.siswa.create', compact('angkatan'));
    }

    /**
     * STORE
     * - ANGKATAN WAJIB
     */
    public function store(Request $request)
    {
        $request->validate([
            'angkatan'       => 'required|digits:4',
            'nama'           => 'required|string|max:255',
            'nis'            => 'required|numeric|unique:siswa,nis',
            'nisn'           => 'nullable|numeric|unique:siswa,nisn',
            'jenis_kelamin'  => 'required|in:L,P',
            'tanggal_lahir'  => 'nullable|date',
            'alamat'         => 'nullable|string',
            'nama_wali'      => 'nullable|string|max:255',
            'telepon_wali'   => 'nullable|string|max:20',
        ]);

        Siswa::create($request->only([
            'angkatan',
            'nama',
            'nis',
            'nisn',
            'jenis_kelamin',
            'tanggal_lahir',
            'alamat',
            'nama_wali',
            'telepon_wali',
        ]));

        return redirect()
            ->route('tu.siswa.index', ['angkatan' => $request->angkatan])
            ->with('success', 'Siswa berhasil ditambahkan');
    }

    /**
     * DETAIL
     */
    public function detail($id)
    {
        $siswa = Siswa::withTrashed()
            ->with([
                'penempatanAktif.kelas',
                'penempatanAktif.tahunAjaran',
            ])
            ->findOrFail($id);

        return view('tu.siswa.detail', compact('siswa'));
    }

    /**
     * EDIT
     */
    public function edit($id)
    {
        $siswa = Siswa::withTrashed()->findOrFail($id);
        return view('tu.siswa.edit', compact('siswa'));
    }

    /**
     * UPDATE
     */
    public function update(Request $request, $id)
    {
        $siswa = Siswa::withTrashed()->findOrFail($id);

        $request->validate([
            'angkatan'       => 'required|digits:4',
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
            'angkatan',
            'nama',
            'nis',
            'nisn',
            'jenis_kelamin',
            'tanggal_lahir',
            'alamat',
            'nama_wali',
            'telepon_wali',
        ]));

        return redirect()
            ->route('tu.siswa.index', ['angkatan' => $request->angkatan])
            ->with('success', 'Data siswa berhasil diperbarui');
    }

    /**
     * SOFT DELETE
     */
    public function destroy($id)
    {
        Siswa::findOrFail($id)->delete();
        return back()->with('success', 'Siswa dipindahkan ke trash');
    }

    /**
     * RESTORE
     */
    public function restore($id)
    {
        Siswa::withTrashed()->findOrFail($id)->restore();
        return back()->with('success', 'Data siswa berhasil direstore');
    }

    /**
     * FORCE DELETE
     */
    public function forceDelete($id)
    {
        Siswa::withTrashed()->findOrFail($id)->forceDelete();
        return back()->with('success', 'Data siswa dihapus permanen');
    }

    /**
     * BULK ACTION
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,restore,forceDelete',
            'ids'    => 'required|array',
        ]);

        match ($request->action) {
            'delete'      => Siswa::whereIn('id', $request->ids)->delete(),
            'restore'     => Siswa::onlyTrashed()->whereIn('id', $request->ids)->restore(),
            'forceDelete' => Siswa::onlyTrashed()->whereIn('id', $request->ids)->forceDelete(),
        };

        return response()->json(['message' => 'Aksi berhasil dijalankan']);
    }

    /**
     * AJAX SEARCH
     */
    public function ajaxSearch(Request $request)
    {
        return Siswa::query()
            ->where('nama', 'like', "%{$request->q}%")
            ->orWhere('nis', 'like', "%{$request->q}%")
            ->orWhere('nisn', 'like', "%{$request->q}%")
            ->orderBy('nama')
            ->limit(10)
            ->get();
    }

    /**
     * IMPORT EXCEL
     * - ANGKATAN WAJIB ADA DI FILE
     */
    public function importExcel(Request $request)
{
    $request->validate([
        'file'      => 'required|mimes:xlsx,xls,csv|max:2048',
        'angkatan'  => 'required|digits:4',
        'kelas_id'  => 'required|exists:kelas,id',
    ]);

    Excel::import(
        new SiswaImport(
            angkatan: $request->angkatan,
            kelasId: $request->kelas_id
        ),
        $request->file('file')
    );

    return back()->with('success', 'Data siswa berhasil di-import dan ditempatkan ke kelas');
}

}
