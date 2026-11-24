<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class MataPelajaranController extends Controller
{
    /**
     * Menampilkan daftar mata pelajaran dengan pencarian
     * dan pagination setelah pengelompokan berdasarkan kode_mapel.
     */
    public function index(Request $request)
    {
        $query = MataPelajaran::query();

        // SEARCH: nama mata pelajaran atau kode mata pelajaran.
        if ($request->filled('q')) {
            $keyword = trim($request->input('q'));

            $query->where(function ($q) use ($keyword) {
                $q->where('nama_mapel', 'like', '%' . $keyword . '%')
                    ->orWhere('kode_mapel', 'like', '%' . $keyword . '%');
            });
        }

        /*
         * Pengelompokan tetap dilakukan sebelum pagination.
         * Tujuannya agar satu kode_mapel tetap tampil sebagai satu baris.
         */
        $grouped = $query->get()->groupBy('kode_mapel');

        $mataPelajaran = $grouped->map(function ($items) {
            $first = $items->first();

            return (object) [
                'id' => $first->id,
                'nama_mapel' => $first->nama_mapel,
                'kode_mapel' => $first->kode_mapel,
                'kkm' => $first->kkm,
                'fase' => $items->pluck('fase')
                    ->filter()
                    ->unique()
                    ->implode(', '),
                'count_fase' => $items->count(),
            ];
        })->values();

        // PAGINATION: diterapkan setelah data selesai dikelompokkan.
        $perPage = 20;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $mata_pelajaran = new LengthAwarePaginator(
            $mataPelajaran->forPage($currentPage, $perPage)->values(),
            $mataPelajaran->count(),
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        return view('superadmin.mapel.index', compact('mata_pelajaran'));
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
            'kkm' => 'nullable|integer|min:0|max:100',
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
            'kkm' => 'nullable|integer|min:0|max:100',
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
        return back()->with(
            'error',
            'Mata pelajaran yang telah dihapus permanen tidak dapat dipulihkan.'
        );
    }

    /**
     * Delete a subject permanently.
     */
    public function forceDelete($id)
    {
        MataPelajaran::findOrFail($id)->delete();

        return back()->with(
            'success',
            'Mata pelajaran berhasil dihapus permanen.'
        );
    }

    /**
     * Bulk permanently delete subjects.
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,restore,forceDelete',
            'ids' => 'required|array',
            'ids.*' => 'integer',
        ]);

        $ids = $request->ids;

        if ($request->action === 'restore') {
            return response()->json([
                'message' => 'Mata pelajaran yang telah dihapus permanen tidak dapat dipulihkan.',
            ], 422);
        }

        MataPelajaran::whereIn('id', $ids)->delete();

        return response()->json([
            'message' => 'Mata pelajaran berhasil dihapus permanen.',
        ]);
    }

    /**
     * AJAX SEARCH (tanpa reload).
     */
    public function ajaxSearch(Request $request)
    {
        $keyword = $request->input('q');

        $data = MataPelajaran::query()
            ->where(function ($query) use ($keyword) {
                $query->where('nama_mapel', 'like', '%' . $keyword . '%')
                    ->orWhere('kode_mapel', 'like', '%' . $keyword . '%')
                    ->orWhere('kelompok', 'like', '%' . $keyword . '%');
            })
            ->orderBy('nama_mapel')
            ->get();

        return response()->json($data);
    }
}