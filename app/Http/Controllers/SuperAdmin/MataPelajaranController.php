<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class MataPelajaranController extends Controller
{
    /**
     * Index with search and the legacy status query parameter.
     */
    public function index(Request $request)
    {
        $deletedValues = $request->input('deleted', []);
        $deletedValues = is_array($deletedValues) ? $deletedValues : ($request->filled('deleted') ? [$request->deleted] : []);
        $deleted = in_array('active', $deletedValues, true) ? 'active' : $request->query('deleted', 'active');

        if ($deleted === '') {
            $deleted = 'all';
        }

        if (! in_array($deleted, ['active', 'all'], true)) {
            $query = $request->query();
            $query['deleted'] = 'active';

            return redirect()->route('superadmin.mapel.index', $query);
        }

        $query = MataPelajaran::query();

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
                'kkm' => $first->kkm,
                'fase' => $items->pluck('fase')->join(', '),
                'count_fase' => $items->count(),
            ];
        })->values();

        return view('superadmin.mapel.index', compact('mata_pelajaran', 'deleted'));
    }
    /**
     * CREATE PAGE
     */
    public function create()
    {
        return view('superadmin.mapel.create');
    }

    /**
     * STORE
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_mapel' => 'required|string|max:255',
            'kode_mapel' => 'nullable|string|max:100',
            'kkm'        => 'nullable|integer|min:0|max:100',
        ]);

        MataPelajaran::create($data);

        return redirect()
            ->route('superadmin.mapel.index')
            ->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    /**
     * DETAIL PAGE
     */
    public function show($id)
    {
        $mapel = MataPelajaran::findOrFail($id);

        return view('superadmin.mapel.detail', compact('mapel'));
    }

    /**
     * EDIT PAGE
     */
    public function edit($id)
    {
        $mapel = MataPelajaran::findOrFail($id);

        return view('superadmin.mapel.edit', compact('mapel'));
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
            'kkm'        => 'nullable|integer|min:0|max:100',
        ]);

        $mapel->update($data);

        return redirect()
            ->route('superadmin.mapel.index')
            ->with('success', 'Data mata pelajaran berhasil diperbarui.');
    }

    /**
     * Delete a subject permanently.
     */
    public function destroy($id)
    {
        MataPelajaran::findOrFail($id)->delete();

        return redirect()
            ->route('superadmin.mapel.index')
            ->with('success', 'Mata pelajaran berhasil dihapus permanen.');
    }

    /**
     * Subjects are physically deleted and cannot be restored.
     */
    public function restore($id)
    {
        return back()->with('error', 'Mata pelajaran yang telah dihapus permanen tidak dapat dipulihkan.');
    }

    /**
     * Delete a subject permanently.
     */
    public function forceDelete($id)
    {
        MataPelajaran::findOrFail($id)->delete();

        return back()->with('success', 'Mata pelajaran berhasil dihapus permanen.');
    }

    /**
     * Bulk permanently delete subjects.
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,restore,forceDelete',
            'ids'    => 'required|array',
            'ids.*'  => 'integer',
        ]);

        $ids = $request->ids;

        if ($request->action === 'restore') {
            return response()->json([
                'message' => 'Mata pelajaran yang telah dihapus permanen tidak dapat dipulihkan.',
            ], 422);
        }

        MataPelajaran::whereIn('id', $ids)->delete();

        return response()->json(['message' => 'Mata pelajaran berhasil dihapus permanen.']);
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
