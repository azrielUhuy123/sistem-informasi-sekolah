<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    /**
     * INDEX — Sorting + Search + Filter Deleted + Pagination
     */
    public function index(Request $request)
    {
        $deleted = $request->deleted ?? 'active'; // active | only | all

        if ($deleted === 'only') {
            $query = TahunAjaran::onlyTrashed();
        } elseif ($deleted === 'all') {
            $query = TahunAjaran::withTrashed();
        } else {
            $query = TahunAjaran::query();
        }

        // SEARCH
        if ($request->q) {
            $query->where(function ($q) use ($request) {
                $q->where('tahun_ajaran', 'like', "%{$request->q}%")
                  ->orWhere('status', 'like', "%{$request->q}%");
            });
        }

        // SORTING
        $sort = $request->sort ?? 'id';
        $order = $request->order ?? 'asc';

        $allowed = ['id', 'tahun_ajaran', 'status'];
        if (!in_array($sort, $allowed)) $sort = 'id';

        $query->orderBy($sort, $order);

        // PAGINATION
        $perPage = $request->per_page ?? 15;
        $tahun = $query->paginate($perPage)->withQueryString();

        return view('tu.tahun.index', compact('tahun'));
    }

    /**
     * CREATE PAGE
     */
    public function create()
    {
        return view('tu.tahun.create');
    }

    /**
     * STORE
     */
    public function store(Request $request)
    {
        $request->validate([
            'tahun_ajaran' => 'required',
            'status'       => 'nullable'
        ]);

        TahunAjaran::create($request->only(['tahun_ajaran', 'status']));

        return redirect()->route('tu.tahun.index')
            ->with('success', 'Tahun ajaran berhasil ditambahkan!');
    }

    /**
     * EDIT PAGE
     */
    public function edit($id)
    {
        $tahun = TahunAjaran::withTrashed()->findOrFail($id);
        return view('tu.tahun.edit', compact('tahun'));
    }

    /**
     * UPDATE
     */
    public function update(Request $request, $id)
    {
        $tahun = TahunAjaran::withTrashed()->findOrFail($id);

        $tahun->update($request->validate([
            'tahun_ajaran' => 'required',
            'status'       => 'nullable'
        ]));

        return redirect()->route('tu.tahun.index')
            ->with('success', 'Tahun ajaran berhasil diperbarui!');
    }

    /**
     * DELETE (Soft Delete)
     */
    public function destroy($id)
    {
        $tahun = TahunAjaran::findOrFail($id);
        $tahun->delete();

        return redirect()->route('tu.tahun.index')
            ->with('success', 'Data dipindahkan ke trash.');
    }

    /**
     * RESTORE
     */
    public function restore($id)
    {
        $tahun = TahunAjaran::withTrashed()->findOrFail($id);
        $tahun->restore();

        return back()->with('success', 'Data berhasil direstore.');
    }

    /**
     * FORCE DELETE
     */
    public function forceDelete($id)
    {
        $tahun = TahunAjaran::withTrashed()->findOrFail($id);
        $tahun->forceDelete();

        return back()->with('success', 'Data berhasil dihapus permanen.');
    }

    /**
     * BULK ACTION: delete | restore | forceDelete
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
                TahunAjaran::whereIn('id', $ids)->delete();
                return response()->json(['message' => 'Berhasil menghapus.']);

            case 'restore':
                TahunAjaran::onlyTrashed()->whereIn('id', $ids)->restore();
                return response()->json(['message' => 'Berhasil restore.']);

            case 'forceDelete':
                TahunAjaran::onlyTrashed()->whereIn('id', $ids)->forceDelete();
                return response()->json(['message' => 'Berhasil hapus permanen.']);
        }
    }

    /**
     * AJAX LIVE SEARCH
     */
    public function ajaxSearch(Request $request)
    {
        $keyword = $request->q;

        $data = TahunAjaran::where('tahun_ajaran', 'like', "%$keyword%")
            ->orWhere('status', 'like', "%$keyword%")
            ->orderBy('tahun_ajaran')
            ->get();

        return response()->json($data);
    }
}
