<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    /**
     * INDEX — Sorting + Search + Filter Deleted + Pagination
     */
    public function index(Request $request)
    {
        $deleted = $request->deleted ?? 'active'; // active | only | all

        if ($deleted === 'only') {
            $query = Siswa::onlyTrashed()->with('kelas');
        } elseif ($deleted === 'all') {
            $query = Siswa::withTrashed()->with('kelas');
        } else {
            $query = Siswa::query()->with('kelas');
        }

        // SEARCH
        if ($request->q) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', "%{$request->q}%")
                  ->orWhere('nis', 'like', "%{$request->q}%")
                  ->orWhere('nisn', 'like', "%{$request->q}%")
                  ->orWhere('jenis_kelamin', 'like', "%{$request->q}%")
                  ->orWhere('alamat', 'like', "%{$request->q}%");
            });
        }

        // SORTING
        $sort = $request->sort ?? 'id';
        $order = $request->order ?? 'asc';

        $allowed = ['id', 'nama', 'nis', 'nisn', 'jenis_kelamin'];
        if (!in_array($sort, $allowed)) $sort = 'id';

        $query->orderBy($sort, $order);

        // PAGINATION
        $perPage = $request->per_page ?? 15;
        $siswa = $query->paginate($perPage)->withQueryString();

        return view('tu.siswa.index', compact('siswa'));
    }

    /**
     * CREATE
     */
    public function create()
    {
        $kelas = Kelas::all();
        return view('tu.siswa.create', compact('kelas'));
    }

    /**
     * STORE
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'           => 'required',
            'nis'            => 'required|unique:siswas',
            'nisn'           => 'nullable',
            'jenis_kelamin'  => 'required',
            'kelas_id'       => 'required|exists:kelas,id',
            'alamat'         => 'nullable',
        ]);

        Siswa::create($request->all());

        return redirect()->route('tu.siswa.index')
            ->with('success', 'Siswa berhasil ditambahkan!');
    }

    /**
     * DETAIL PAGE
     */
    public function detail($id)
    {
        $siswa = Siswa::withTrashed()->with('kelas')->findOrFail($id);
        return view('tu.siswa.detail', compact('siswa'));
    }

    /**
     * EDIT
     */
    public function edit($id)
    {
        $siswa = Siswa::withTrashed()->findOrFail($id);
        $kelas = Kelas::all();
        return view('tu.siswa.edit', compact('siswa', 'kelas'));
    }

    /**
     * UPDATE
     */
    public function update(Request $request, $id)
    {
        $siswa = Siswa::withTrashed()->findOrFail($id);

        $request->validate([
            'nama'           => 'required',
            'nis'            => 'required|unique:siswas,nis,' . $siswa->id,
            'nisn'           => 'nullable',
            'jenis_kelamin'  => 'required',
            'kelas_id'       => 'required|exists:kelas,id',
            'alamat'         => 'nullable',
        ]);

        $siswa->update($request->all());

        return redirect()->route('tu.siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui!');
    }

    /**
     * DELETE (Soft Delete)
     */
    public function destroy($id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->delete();

        return redirect()->route('tu.siswa.index')
            ->with('success', 'Siswa dipindahkan ke trash.');
    }

    /**
     * RESTORE
     */
    public function restore($id)
    {
        Siswa::withTrashed()->findOrFail($id)->restore();

        return back()->with('success', 'Data siswa berhasil direstore.');
    }

    /**
     * FORCE DELETE
     */
    public function forceDelete($id)
    {
        Siswa::withTrashed()->findOrFail($id)->forceDelete();

        return back()->with('success', 'Data siswa berhasil dihapus permanen.');
    }

    /**
     * BULK ACTION
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,restore,forceDelete',
            'ids' => 'required|array',
        ]);

        $ids = $request->ids;

        switch ($request->action) {
            case 'delete':
                Siswa::whereIn('id', $ids)->delete();
                return response()->json(['message' => 'Berhasil menghapus.']);

            case 'restore':
                Siswa::onlyTrashed()->whereIn('id', $ids)->restore();
                return response()->json(['message' => 'Berhasil restore.']);

            case 'forceDelete':
                Siswa::onlyTrashed()->whereIn('id', $ids)->forceDelete();
                return response()->json(['message' => 'Berhasil hapus permanen.']);
        }
    }

    /**
     * AJAX LIVE SEARCH
     */
    public function ajaxSearch(Request $request)
    {
        $keyword = $request->q;

        $data = Siswa::where('nama', 'like', "%$keyword%")
            ->orWhere('nis', 'like', "%$keyword%")
            ->orWhere('nisn', 'like', "%$keyword%")
            ->orWhere('jenis_kelamin', 'like', "%$keyword%")
            ->orWhere('alamat', 'like', "%$keyword%")
            ->orderBy('nama')
            ->get();

        return response()->json($data);
    }
}
