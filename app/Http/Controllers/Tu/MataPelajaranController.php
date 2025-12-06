<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class MataPelajaranController extends Controller
{
    /**
     * INDEX — Sorting + Search + Pagination + Deleted Filter
     */
    public function index(Request $request)
    {
        $deleted = $request->deleted ?? 'active';

        if ($deleted === 'only') {
            $query = MataPelajaran::onlyTrashed();
        } elseif ($deleted === 'all') {
            $query = MataPelajaran::withTrashed();
        } else {
            $query = MataPelajaran::query();
        }

        // SEARCH
        if ($request->q) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_mapel', 'like', "%{$request->q}%")
                  ->orWhere('kode_mapel', 'like', "%{$request->q}%")
                  ->orWhere('kelompok', 'like', "%{$request->q}%");
            });
        }

        // SORT
        $sort  = $request->sort ?? 'id';
        $order = $request->order ?? 'asc';

        $allowedSort = ['id', 'nama_mapel', 'kode_mapel', 'kelompok'];
        if (!in_array($sort, $allowedSort)) $sort = 'id';

        $query->orderBy($sort, $order);

        // PAGINATION
        $perPage = $request->per_page ?? 15;

        $mapel = $query->paginate($perPage)->withQueryString();

        return view('tu.mapel.index', compact('mapel'));
    }

    /**
     * CREATE PAGE
     */
    public function create()
    {
        return view('tu.mapel.create');
    }

    /**
     * STORE
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_mapel' => 'required',
            'kode_mapel' => 'nullable',
            'kelompok'   => 'nullable'
        ]);

        MataPelajaran::create($data);

        return redirect()->route('tu.mapel.index')
            ->with('success', 'Mata pelajaran berhasil ditambahkan!');
    }

    /**
     * DETAIL PAGE
     */
    public function show($id)
    {
        $mapel = MataPelajaran::withTrashed()->findOrFail($id);
        return view('tu.mapel.detail', compact('mapel'));
    }

    /**
     * EDIT PAGE
     */
    public function edit($id)
    {
        $mapel = MataPelajaran::withTrashed()->findOrFail($id);
        return view('tu.mapel.edit', compact('mapel'));
    }

    /**
     * UPDATE
     */
    public function update(Request $request, $id)
    {
        $mapel = MataPelajaran::withTrashed()->findOrFail($id);

        $data = $request->validate([
            'nama_mapel' => 'required',
            'kode_mapel' => 'nullable',
            'kelompok'   => 'nullable'
        ]);

        $mapel->update($data);

        return redirect()->route('tu.mapel.index')
            ->with('success', 'Data mapel berhasil diperbarui!');
    }

    /**
     * SOFT DELETE
     */
    public function destroy($id)
    {
        MataPelajaran::findOrFail($id)->delete();

        return redirect()->route('tu.mapel.index')
            ->with('success', 'Mapel dipindahkan ke trash.');
    }

    /**
     * RESTORE
     */
    public function restore($id)
    {
        MataPelajaran::withTrashed()->findOrFail($id)->restore();

        return back()->with('success', 'Mapel berhasil direstore.');
    }

    /**
     * FORCE DELETE
     */
    public function forceDelete($id)
    {
        MataPelajaran::withTrashed()->findOrFail($id)->forceDelete();

        return back()->with('success', 'Mapel berhasil dihapus permanen.');
    }

    /**
     * BULK ACTION — delete | restore | forceDelete
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,restore,forceDelete',
            'ids'    => 'required|array'
        ]);

        $ids = $request->ids;

        switch ($request->action) {
            case 'delete':
                MataPelajaran::whereIn('id', $ids)->delete();
                return response()->json(['message' => 'Mapel berhasil dihapus (soft delete).']);

            case 'restore':
                MataPelajaran::onlyTrashed()->whereIn('id', $ids)->restore();
                return response()->json(['message' => 'Mapel berhasil direstore.']);

            case 'forceDelete':
                MataPelajaran::onlyTrashed()->whereIn('id', $ids)->forceDelete();
                return response()->json(['message' => 'Mapel berhasil dihapus permanen.']);
        }
    }

    /**
     * AJAX SEARCH (tanpa reload)
     */
    public function ajaxSearch(Request $request)
    {
        $keyword = $request->q;

        $data = MataPelajaran::where('nama_mapel', 'like', "%$keyword%")
            ->orWhere('kode_mapel', 'like', "%$keyword%")
            ->orWhere('kelompok', 'like', "%$keyword%")
            ->orderBy('nama_mapel')
            ->get();

        return response()->json($data);
    }
}
