<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    /**
     * INDEX — Sorting + Search + Filter Deleted + Pagination
     */
    public function index(Request $request)
    {
        $deleted = $request->deleted ?? 'active';

        if ($deleted === 'only') {
            $query = Jadwal::onlyTrashed()->with(['kelas', 'guru', 'ruangan']);
        } elseif ($deleted === 'all') {
            $query = Jadwal::withTrashed()->with(['kelas', 'guru', 'ruangan']);
        } else {
            $query = Jadwal::with(['kelas', 'guru', 'ruangan']);
        }

        // SEARCH
        if ($request->q) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('kelas', function ($x) use ($request) {
                        $x->where('nama_kelas', 'like', "%{$request->q}%");
                    })
                  ->orWhereHas('guru', function ($x) use ($request) {
                        $x->where('nama', 'like', "%{$request->q}%")
                          ->orWhere('nip', 'like', "%{$request->q}%");
                  })
                  ->orWhere('hari', 'like', "%{$request->q}%")
                  ->orWhere('jam_mulai', 'like', "%{$request->q}%")
                  ->orWhere('jam_selesai', 'like', "%{$request->q}%");
            });
        }

        // SORTING
        $sort = $request->sort ?? 'id';
        $order = $request->order ?? 'asc';

        $allowed = ['id', 'hari', 'jam_mulai', 'jam_selesai'];
        if (!in_array($sort, $allowed)) $sort = 'id';

        $query->orderBy($sort, $order);

        // PAGINATION
        $perPage = $request->per_page ?? 15;

        $jadwal = $query->paginate($perPage)->withQueryString();

        return view('tu.jadwal.index', compact('jadwal'));
    }

    /**
     * CREATE PAGE
     */
    public function create()
    {
        return view('tu.jadwal.create', [
            'kelas'   => Kelas::all(),
            'guru'    => Guru::all(),
            'ruangan' => Ruangan::all(),
        ]);
    }

    /**
     * STORE
     */
    public function store(Request $request)
    {
        $request->validate([
            'kelas_id'    => 'required|exists:kelas,id',
            'guru_id'     => 'required|exists:guru,id',
            'hari'        => 'required',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required',
            'ruangan_id'  => 'nullable|exists:ruangan,id',
        ]);

        Jadwal::create($request->all());

        return redirect()->route('tu.jadwal.index')
            ->with('success', 'Jadwal berhasil ditambahkan!');
    }

    /**
     * DETAIL PAGE
     */
    public function detail($id)
    {
        $jadwal = Jadwal::with(['kelas', 'guru', 'ruangan', 'mapel'])->findOrFail($id);
        return view('tu.jadwal.detail', compact('jadwal'));
    }

    /**
     * EDIT PAGE
     */
    public function edit($id)
    {
        return view('tu.jadwal.edit', [
            'jadwal'  => Jadwal::withTrashed()->findOrFail($id),
            'kelas'   => Kelas::all(),
            'guru'    => Guru::all(),
            'ruangan' => Ruangan::all(),
        ]);
    }

    /**
     * UPDATE
     */
    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::withTrashed()->findOrFail($id);

        $request->validate([
            'kelas_id'    => 'required|exists:kelas,id',
            'guru_id'     => 'required|exists:guru,id',
            'hari'        => 'required',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required',
            'ruangan_id'  => 'nullable|exists:ruangan,id',
        ]);

        $jadwal->update($request->all());

        return redirect()->route('tu.jadwal.index')
            ->with('success', 'Jadwal berhasil diperbarui!');
    }

    /**
     * DELETE — Soft Delete
     */
    public function destroy($id)
    {
        Jadwal::findOrFail($id)->delete();

        return redirect()->route('tu.jadwal.index')
            ->with('success', 'Jadwal dipindahkan ke trash.');
    }

    /**
     * RESTORE
     */
    public function restore($id)
    {
        Jadwal::withTrashed()->findOrFail($id)->restore();

        return back()->with('success', 'Jadwal berhasil direstore.');
    }

    /**
     * FORCE DELETE
     */
    public function forceDelete($id)
    {
        Jadwal::withTrashed()->findOrFail($id)->forceDelete();

        return back()->with('success', 'Jadwal berhasil dihapus permanen.');
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

        $ids = $request->ids;

        switch ($request->action) {
            case 'delete':
                Jadwal::whereIn('id', $ids)->delete();
                return response()->json(['message' => 'Berhasil menghapus jadwal.']);

            case 'restore':
                Jadwal::onlyTrashed()->whereIn('id', $ids)->restore();
                return response()->json(['message' => 'Berhasil merestore jadwal.']);

            case 'forceDelete':
                Jadwal::onlyTrashed()->whereIn('id', $ids)->forceDelete();
                return response()->json(['message' => 'Berhasil menghapus permanen.']);
        }
    }

    /**
     * AJAX LIVE SEARCH (tanpa reload halaman)
     */
    public function ajaxSearch(Request $request)
    {
        $keyword = $request->q;

        $data = Jadwal::with(['kelas', 'guru', 'ruangan'])
            ->whereHas('kelas', fn ($q) => $q->where('nama_kelas', 'like', "%$keyword%"))
            ->orWhereHas('guru', fn ($q) => $q->where('nama', 'like', "%$keyword%"))
            ->orWhere('hari', 'like', "%$keyword%")
            ->get();

        return response()->json($data);
    }
}
