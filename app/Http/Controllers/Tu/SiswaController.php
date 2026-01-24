<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;
use App\Imports\SiswaImport;
use Maatwebsite\Excel\Facades\Excel;


class SiswaController extends Controller
{
    /**
     * INDEX — List Siswa
     * (Search, Sort, Filter Deleted, Pagination)
     */
    public function index(Request $request)
    {
        $deleted = $request->deleted ?? 'active'; // active | only | all

        // BASE QUERY
        $query = match ($deleted) {
            'only' => Siswa::onlyTrashed(),
            'all'  => Siswa::withTrashed(),
            default => Siswa::query(),
        };

        // WAJIB: load penempatan aktif
        $query->with([
            'penempatanAktif.kelas',
            'penempatanAktif.tahunAjaran'
        ]);

        // SEARCH
        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', "%{$request->q}%")
                  ->orWhere('nis', 'like', "%{$request->q}%")
                  ->orWhere('nisn', 'like', "%{$request->q}%")
                  ->orWhere('jenis_kelamin', 'like', "%{$request->q}%")
                  ->orWhere('alamat', 'like', "%{$request->q}%");
            });
        }

        // SORTING (whitelist)
        $sort  = $request->sort ?? 'id';
        $order = $request->order ?? 'asc';

        $allowedSort = ['id', 'nama', 'nis', 'nisn', 'jenis_kelamin'];
        if (!in_array($sort, $allowedSort)) {
            $sort = 'id';
        }

        $query->orderBy($sort, $order);

        // PAGINATION
        $perPage = $request->per_page ?? 15;
        $siswa = $query->paginate($perPage)->withQueryString();

        return view('tu.siswa.index', [
    'siswa' => $siswa,
    'title' => 'Manajemen Data Siswa',
]);

    }

    /**
     * CREATE — Form Tambah Siswa
     */
    public function create()
    {
        return view('tu.siswa.create');
    }

    /**
     * STORE — Simpan Siswa
     * (TANPA kelas_id)
     */
    public function store(Request $request)
    {
        $request->validate([
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
            ->route('tu.siswa.index')
            ->with('success', 'Siswa berhasil ditambahkan');
    }

    /**
     * DETAIL — Detail Siswa + Kelas Aktif
     */
    public function detail($id)
    {
        $siswa = Siswa::withTrashed()
            ->with([
                'penempatanAktif.kelas',
                'penempatanAktif.tahunAjaran'
            ])
            ->findOrFail($id);

        return view('tu.siswa.detail', compact('siswa'));
    }

    /**
     * EDIT — Form Edit Siswa
     */
    public function edit($id)
    {
        $siswa = Siswa::withTrashed()->findOrFail($id);
        return view('tu.siswa.edit', compact('siswa'));
    }

    /**
     * UPDATE — Update Data Siswa
     * (TANPA kelas_id)
     */
    public function update(Request $request, $id)
    {
        $siswa = Siswa::withTrashed()->findOrFail($id);

        $request->validate([
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

        return redirect()
            ->route('tu.siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui');
    }

    /**
     * DELETE — Soft Delete
     */
    public function destroy($id)
    {
        Siswa::findOrFail($id)->delete();

        return redirect()
            ->route('tu.siswa.index')
            ->with('success', 'Siswa dipindahkan ke trash');
    }

    //RESTORE — Restore Soft Deleted
    public function restore($id)
    {
        Siswa::withTrashed()->findOrFail($id)->restore();

        return back()->with('success', 'Data siswa berhasil direstore');
    }

    //FORCE DELETE — Hapus Permanen
    public function forceDelete($id)
    {
        Siswa::withTrashed()->findOrFail($id)->forceDelete();

        return back()->with('success', 'Data siswa berhasil dihapus permanen');
    }

    /**
     * BULK ACTION — delete | restore | forceDelete
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,restore,forceDelete',
            'ids'    => 'required|array',
        ]);

        match ($request->action) {
            'delete' => Siswa::whereIn('id', $request->ids)->delete(),
            'restore' => Siswa::onlyTrashed()->whereIn('id', $request->ids)->restore(),
            'forceDelete' => Siswa::onlyTrashed()->whereIn('id', $request->ids)->forceDelete(),
        };

        return response()->json(['message' => 'Aksi berhasil dijalankan']);
    }

    /**
     * AJAX LIVE SEARCH (GLOBAL SEARCH)
     */
    public function ajaxSearch(Request $request)
    {
        $keyword = $request->q;

        $data = Siswa::query()
            ->where('nama', 'like', "%{$keyword}%")
            ->orWhere('nis', 'like', "%{$keyword}%")
            ->orWhere('nisn', 'like', "%{$keyword}%")
            ->orderBy('nama')
            ->limit(10)
            ->get();

        return response()->json($data);
    }

    /**
 * IMPORT SISWA DARI EXCEL
 */
public function importExcel(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv|max:2048',
    ]);

    Excel::import(new SiswaImport, $request->file('file'));

    return redirect()
        ->route('tu.siswa.index')
        ->with('success', 'Data siswa berhasil di-import dari Excel');
}
}
