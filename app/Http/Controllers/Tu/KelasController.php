<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    /**
     * INDEX — Sorting + Search + Filter Deleted + Pagination
     */
    public function index(Request $request)
    {
        // query dasar
        $deleted = $request->deleted ?? 'active';  // active | only | all

        if ($deleted === 'only') {
            $query = Kelas::onlyTrashed();
        } elseif ($deleted === 'all') {
            $query = Kelas::withTrashed();
        } else {
            $query = Kelas::query();
        }

        // FILTER SEARCH
        if ($request->q) {
            $query->where(function($q) use ($request) {
                $q->where('nama_kelas', 'like', "%{$request->q}%")
                  ->orWhere('tingkat', 'like', "%{$request->q}%")
                  ->orWhere('tahun_ajaran', 'like', "%{$request->q}%");
            });
        }

        // SORTING
        $sort = $request->sort ?? 'id';
        $order = $request->order ?? 'asc';

        $allowed = ['id', 'nama_kelas', 'tingkat', 'tahun_ajaran'];
        if (!in_array($sort, $allowed)) $sort = 'id';

        $query->orderBy($sort, $order);

        // PAGINATION
        $perPage = $request->per_page ?? 15;
        $kelas = $query->paginate($perPage)->withQueryString();

        return view('tu.kelas.index', compact('kelas'));
    }

    /**
     * CREATE PAGE
     */
    public function create()
    {
        return view('tu.kelas.create');
    }

    /**
     * STORE
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required',
            'tingkat' => 'required|integer',
            'tahun_ajaran' => 'required'
        ]);

        Kelas::create($request->only(['nama_kelas','tingkat','tahun_ajaran']));

        return redirect()->route('tu.kelas.index')
            ->with('success','Kelas berhasil ditambahkan');
    }

    /**
     * EDIT PAGE
     */
    public function edit($id)
    {
        $kelas = Kelas::withTrashed()->findOrFail($id);
        return view('tu.kelas.edit', compact('kelas'));
    }

    /**
     * UPDATE
     */
    public function update(Request $request, $id)
    {
        $kelas = Kelas::withTrashed()->findOrFail($id);

        $kelas->update($request->validate([
            'nama_kelas' => 'required',
            'tingkat' => 'required|integer',
            'tahun_ajaran' => 'required'
        ]));

        return redirect()->route('tu.kelas.index')
            ->with('success','Kelas berhasil diperbarui');
    }

    /**
     * DELETE (Soft Delete)
     */
    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();

        return redirect()->route('tu.kelas.index')
            ->with('success','Data dipindahkan ke trash');
    }

    /**
     * RESTORE
     */
    public function restore($id)
    {
        $kelas = Kelas::withTrashed()->findOrFail($id);
        $kelas->restore();

        return back()->with('success','Data berhasil direstore');
    }

    /**
     * FORCE DELETE (Permanent)
     */
    public function forceDelete($id)
    {
        $kelas = Kelas::withTrashed()->findOrFail($id);
        $kelas->forceDelete();

        return back()->with('success','Data berhasil dihapus permanen');
    }

    /**
     * BULK ACTION (delete | restore | forceDelete)
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
                Kelas::whereIn('id', $ids)->delete();
                return response()->json(['message' => 'Berhasil menghapus (soft delete).']);
            
            case 'restore':
                Kelas::onlyTrashed()->whereIn('id', $ids)->restore();
                return response()->json(['message' => 'Berhasil merestore data.']);

            case 'forceDelete':
                Kelas::onlyTrashed()->whereIn('id', $ids)->forceDelete();
                return response()->json(['message' => 'Berhasil menghapus permanen.']);
        }
    }

    /**
     * DETAIL
     */
    public function detail($id)
    {
        $kelas = Kelas::withTrashed()->with('siswa')->findOrFail($id);
        return view('tu.kelas.detail', compact('kelas'));
    }
    public function storeMultiple(Request $request)
{
    $validated = $request->validate([
        'kelas' => 'required|array|min:1',
        'kelas.*.nama_kelas' => 'required',
        'kelas.*.tingkat' => 'required|integer',
        'kelas.*.tahun_ajaran' => 'required'
    ]);

    foreach ($validated['kelas'] as $k) {
        Kelas::create([
            'nama_kelas' => $k['nama_kelas'],
            'tingkat' => $k['tingkat'],
            'tahun_ajaran' => $k['tahun_ajaran'],
        ]);
    }

    return redirect()->route('tu.kelas.index')
        ->with('success', 'Kelas berhasil ditambahkan!');
}


public function ajaxSearch(Request $request)
{
    $keyword = $request->q;

    $data = Kelas::where('nama_kelas', 'like', "%$keyword%")
                ->orWhere('tingkat', 'like', "%$keyword%")
                ->orWhere('tahun_ajaran', 'like', "%$keyword%")
                ->orderBy('nama_kelas')
                ->get();

    return response()->json($data);
}



}
