<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    /**
     * INDEX — Sorting + Search + Filter Deleted + Pagination
     */
    public function index(Request $request)
    {
        $deleted = $request->deleted ?? 'active'; // active | only | all

        if ($deleted === 'only') {
            $query = Guru::onlyTrashed();
        } elseif ($deleted === 'all') {
            $query = Guru::withTrashed();
        } else {
            $query = Guru::query();
        }

        // SEARCH
        if ($request->q) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', "%{$request->q}%")
                  ->orWhere('nip', 'like', "%{$request->q}%")
                  ->orWhere('jenis_kelamin', 'like', "%{$request->q}%")
                  ->orWhere('mapel', 'like', "%{$request->q}%")
                  ->orWhere('alamat', 'like', "%{$request->q}%");
            });
        }

        // SORTING
        $sort = $request->sort ?? 'id';
        $order = $request->order ?? 'asc';

        $allowed = ['id', 'nama', 'nip', 'mapel'];
        if (!in_array($sort, $allowed)) $sort = 'id';

        $query->orderBy($sort, $order);

        // PAGINATION
        $perPage = $request->per_page ?? 15;
        $guru = $query->paginate($perPage)->withQueryString();

        return view('tu.guru.index', compact('guru'));
    }

    /**
     * CREATE PAGE
     */
    public function create()
    {
        return view('tu.guru.create');
    }

    /**
     * STORE
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'          => 'required',
            'nip'           => 'nullable|unique:guru',
            'jenis_kelamin' => 'required',
            'mapel'         => 'nullable',
            'alamat'        => 'nullable',
        ]);

        Guru::create($request->all());

        return redirect()->route('tu.guru.index')
            ->with('success', 'Guru berhasil ditambahkan!');
    }

    /**
     * DETAIL PAGE
     */
    public function detail($id)
    {
        $guru = Guru::withTrashed()->findOrFail($id);
        return view('tu.guru.detail', compact('guru'));
    }

    /**
     * EDIT PAGE
     */
    public function edit($id)
    {
        $guru = Guru::withTrashed()->findOrFail($id);
        return view('tu.guru.edit', compact('guru'));
    }

    /**
     * UPDATE
     */
    public function update(Request $request, $id)
    {
        $guru = Guru::withTrashed()->findOrFail($id);

        $request->validate([
            'nama'          => 'required',
            'nip'           => 'nullable|unique:guru,nip,' . $guru->id,
            'jenis_kelamin' => 'required',
            'mapel'         => 'nullable',
            'alamat'        => 'nullable',
        ]);

        $guru->update($request->all());

        return redirect()->route('tu.guru.index')
            ->with('success', 'Data guru berhasil diperbarui!');
    }

    /**
     * DELETE (Soft Delete)
     */
    public function destroy($id)
    {
        Guru::findOrFail($id)->delete();

        return redirect()->route('tu.guru.index')
            ->with('success', 'Guru dipindahkan ke trash.');
    }

    /**
     * RESTORE
     */
    public function restore($id)
    {
        Guru::withTrashed()->findOrFail($id)->restore();

        return back()->with('success', 'Guru berhasil direstore.');
    }

    /**
     * FORCE DELETE
     */
    public function forceDelete($id)
    {
        Guru::withTrashed()->findOrFail($id)->forceDelete();

        return back()->with('success', 'Guru berhasil dihapus permanen.');
    }

    /**
     * BULK ACTION (delete | restore | forceDelete)
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,restore,forceDelete',
            'ids'    => 'required|array',
        ]);

        $ids = $request->ids;

        switch ($request->action) {
            case 'delete':
                Guru::whereIn('id', $ids)->delete();
                return response()->json(['message' => 'Berhasil menghapus.']);

            case 'restore':
                Guru::onlyTrashed()->whereIn('id', $ids)->restore();
                return response()->json(['message' => 'Berhasil restore.']);

            case 'forceDelete':
                Guru::onlyTrashed()->whereIn('id', $ids)->forceDelete();
                return response()->json(['message' => 'Berhasil hapus permanen.']);
        }
    }

    /**
     * AJAX LIVE SEARCH
     */
    public function ajaxSearch(Request $request)
    {
        $keyword = $request->q;

        $data = Guru::where('nama', 'like', "%$keyword%")
            ->orWhere('nip', 'like', "%$keyword%")
            ->orWhere('mapel', 'like', "%$keyword%")
            ->orWhere('jenis_kelamin', 'like', "%$keyword%")
            ->orderBy('nama')
            ->get();

        return response()->json($data);
    }
}
