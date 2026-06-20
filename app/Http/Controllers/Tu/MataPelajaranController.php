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
        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_mapel', 'like', '%' . $request->q . '%')
                ->orWhere('kode_mapel', 'like', '%' . $request->q . '%');
            });
        }

        // 🔥 AMBIL SEMUA DATA DULU
        $data = $query->get();

        // 🔥 GROUP BY kode_mapel (INI INTI FIX)
        $grouped = $data->groupBy('kode_mapel');

        // 🔥 FORMAT JADI 1 BARIS PER MAPEL
        $mata_pelajaran = $grouped->map(function ($items) {
            $first = $items->first();

            return (object) [
                'id' => $first->id,
                'nama_mapel' => $first->nama_mapel,
                'kode_mapel' => $first->kode_mapel,
                'fase' => $items->pluck('fase')->join(', '),
                'count_fase' => $items->count(),
            ];
        })->values();

        return view('tu.mapel.index', compact('mata_pelajaran'));
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
            'nama_mapel' => 'required|string|max:255',
            'kode_mapel' => 'nullable|string|max:100',
        ]);

        MataPelajaran::create($data);

        return redirect()
            ->route('tu.mapel.index')
            ->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    /**
     * DETAIL PAGE
     */
    public function show($id)
    {
        $mapel = MataPelajaran::findOrFail($id);

        return view('tu.mapel.detail', compact('mapel'));
    }

    /**
     * EDIT PAGE
     */
    public function edit($id)
    {
        $mapel = MataPelajaran::findOrFail($id);

        return view('tu.mapel.edit', compact('mapel'));
    }

    /**
     * UPDATE
     */
    
    public function update(Request $request, $id)
    {
        $mapel = MataPelajaran::findOrFail($id);

        $data = $request->validate([
            'nama_mapel' => 'required|string|max:255',
            'kode_mapel' => 'nullable|string|max:100',
        ]);

        $mapel->update($data);

        return redirect()
            ->route('tu.mapel.index')
            ->with('success', 'Data mata pelajaran berhasil diperbarui.');
    }

    /**
     * SOFT DELETE
     */
    public function destroy($id)
    {
        MataPelajaran::findOrFail($id)->delete();

        return redirect()
            ->route('tu.mapel.index')
            ->with('success', 'Mata pelajaran dipindahkan ke trash.');
    }

    /**
     * RESTORE
     */
    public function restore($id)
    {
        MataPelajaran::withTrashed()->findOrFail($id)->restore();

        return back()->with('success', 'Mata pelajaran berhasil direstore.');
    }

    /**
     * FORCE DELETE
     */
    public function forceDelete($id)
    {
        MataPelajaran::withTrashed()->findOrFail($id)->forceDelete();

        return back()->with('success', 'Mata pelajaran berhasil dihapus permanen.');
    }

    /**
     * BULK ACTION — delete | restore | forceDelete
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,restore,forceDelete',
            'ids'    => 'required|array',
            'ids.*'  => 'integer',
        ]);

        $ids = $request->ids;

        if ($request->action === 'delete') {
            MataPelajaran::whereIn('id', $ids)->delete();
            return response()->json(['message' => 'Mapel berhasil dihapus (soft delete).']);
        }

        if ($request->action === 'restore') {
            MataPelajaran::onlyTrashed()->whereIn('id', $ids)->restore();
            return response()->json(['message' => 'Mapel berhasil direstore.']);
        }

        if ($request->action === 'forceDelete') {
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

        $data = MataPelajaran::query()
            ->where('nama_mapel', 'like', '%' . $keyword . '%')
            ->orWhere('kode_mapel', 'like', '%' . $keyword . '%')
            ->orWhere('kelompok', 'like', '%' . $keyword . '%')
            ->orderBy('nama_mapel')
            ->get();

        return response()->json($data);
    }
}
