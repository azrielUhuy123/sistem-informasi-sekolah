<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                $q->where('tahun', 'like', "%{$request->q}%")
                  ->orWhere('status', 'like', "%{$request->q}%");
            });
        }

        // SORTING
        $sort  = $request->sort ?? 'id';
        $order = $request->order ?? 'asc';

        $allowed = ['id', 'tahun', 'status'];
        if (!in_array($sort, $allowed)) {
            $sort = 'id';
        }

        $query->orderBy($sort, $order);

        // PAGINATION
        $perPage = $request->per_page ?? 15;
        $tahun = $query->paginate($perPage)->withQueryString();
        $tahunAktif = TahunAjaran::aktif();
        
        return view('tu.tahun.index', compact('tahun', 'tahunAktif'));
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
    $data = $request->validate([
        'tahun'    => 'required',
        'semester' => 'required|in:Ganjil,Genap',
        'aktif'    => 'nullable',
    ]);

    if ($request->has('aktif')) {
        TahunAjaran::query()->update(['status' => 'selesai']);
    }

    TahunAjaran::create([
        'tahun'    => $data['tahun'],
        'semester' => $data['semester'],
        'status'   => $request->has('aktif') ? 'aktif' : 'selesai',
    ]);

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
    $tahun = TahunAjaran::findOrFail($id);

    $data = $request->validate([
        'tahun'    => 'required',
        'semester' => 'required|in:Ganjil,Genap',
        'aktif'    => 'nullable',
    ]);

    if ($request->has('aktif')) {
        TahunAjaran::query()->update(['status' => 'selesai']);
    }

    $tahun->update([
        'tahun'    => $data['tahun'],
        'semester' => $data['semester'],
        'status' => $request->has('aktif') ? 'aktif' : 'selesai',
    ]);

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
     * BULK ACTION
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,restore,forceDelete',
            'ids'    => 'required|array',
        ]);

        switch ($request->action) {
            case 'delete':
                TahunAjaran::whereIn('id', $request->ids)->delete();
                break;

            case 'restore':
                TahunAjaran::onlyTrashed()->whereIn('id', $request->ids)->restore();
                break;

            case 'forceDelete':
                TahunAjaran::onlyTrashed()->whereIn('id', $request->ids)->forceDelete();
                break;
        }

        return response()->json(['message' => 'Aksi berhasil dilakukan.']);
    }

    /**
     * AJAX SEARCH
     */
    public function ajaxSearch(Request $request)
    {
        $keyword = $request->q;

        $data = TahunAjaran::where('tahun', 'like', "%$keyword%")
            ->orderBy('tahun')
            ->get();

        return response()->json($data);
    }

    public function detail($id)
{
    $tahun = TahunAjaran::withTrashed()->findOrFail($id);
    return view('tu.tahun.detail', compact('tahun'));
}
    public function masukTransisi()
    {
        $tahun = TahunAjaran::aktif();

        if (!$tahun) {
            abort(500, 'Tidak ada tahun aktif');
        }

        if ($tahun->status === 'transisi') {
            return back()->with('info', 'Sudah dalam masa transisi');
        }

        $tahun->update([
            'status' => 'transisi'
        ]);

        return back()->with('success', 'Berhasil masuk masa kenaikan kelas');
    }
    public function tutupTahun()
    {
        DB::transaction(function () {

            $tahunLama = TahunAjaran::aktif();

            if (!$tahunLama) {
                abort(500, 'Tidak ada tahun aktif');
            }

            if ($tahunLama->status !== 'transisi') {
                abort(403, 'Tahun harus dalam status transisi');
            }

            // tutup tahun lama
            $tahunLama->update([
                'status' => 'selesai'
            ]);

            // generate tahun baru
            $tahunArr = explode('/', $tahunLama->tahun);

            $start = (int)$tahunArr[0] + 1;
            $end   = (int)$tahunArr[1] + 1;

            TahunAjaran::create([
                'tahun' => "{$start}/{$end}",
                'semester' => 'Ganjil',
                'status' => 'aktif'
            ]);
        });

        return back()->with('success', 'Tahun ajaran baru berhasil dibuat');
    }

}
