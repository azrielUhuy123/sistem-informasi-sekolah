<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Ruangan;
use App\Models\MataPelajaran;
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
            $query = Jadwal::onlyTrashed()
                ->with(['kelas', 'guru.mapel', 'ruangan']);
        } elseif ($deleted === 'all') {
            $query = Jadwal::withTrashed()
                ->with(['kelas', 'guru.mapel', 'ruangan']);
        } else {
            $query = Jadwal::with(['kelas', 'guru.mapel', 'ruangan']);
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
        $sort  = $request->sort ?? 'id';
        $order = $request->order ?? 'asc';

        $allowed = ['id', 'hari', 'jam_mulai', 'jam_selesai'];
        if (!in_array($sort, $allowed)) {
            $sort = 'id';
        }

        $query->orderBy($sort, $order);

        // PAGINATION
        $perPage = $request->per_page ?? 15;
        $jadwal  = $query->paginate($perPage)->withQueryString();

        return view('tu.jadwal.index', compact('jadwal'));
    }

    /**
     * CREATE PAGE
     */
    public function create()
    {
        $kelas   = Kelas::whereNull('deleted_at')->get();
        $guru    = Guru::with('mapel')->whereNull('deleted_at')->get();
        $ruangan = Ruangan::all();
        $mapel   = MataPelajaran::orderBy('nama_mapel')->get();

        return view('tu.jadwal.create', compact(
            'kelas',
            'guru',
            'ruangan',
            'mapel'
        ));
    }

    /**
     * STORE
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'kelas_id'    => 'required|exists:kelas,id',
            'guru_id'     => 'required|exists:guru,id',
            'mapel_id'    => 'required|exists:mata_pelajaran,id',
            'hari'        => 'required|string|max:20',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required',
            'ruangan_id'  => 'nullable|exists:ruangan,id',
        ]);

        Jadwal::create($data);

        return redirect()
            ->route('tu.jadwal.index')
            ->with('success', 'Jadwal berhasil ditambahkan!');
    }

    /**
     * DETAIL PAGE
     */
    public function detail($id)
    {
        $jadwal = Jadwal::with([
            'kelas',
            'guru.mapel',
            'ruangan'
        ])->findOrFail($id);

        return view('tu.jadwal.detail', compact('jadwal'));
    }

    /**
     * EDIT PAGE
     */
    public function edit($id)
    {
        $jadwal  = Jadwal::withTrashed()->findOrFail($id);
        $kelas   = Kelas::whereNull('deleted_at')->get();
        $guru    = Guru::with('mapel')->whereNull('deleted_at')->get();
        $ruangan = Ruangan::all();
        $mapel   = MataPelajaran::orderBy('nama_mapel')->get();

        return view('tu.jadwal.edit', compact(
            'jadwal',
            'kelas',
            'guru',
            'ruangan',
            'mapel'
        ));
    }

    /**
     * UPDATE
     */
    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::withTrashed()->findOrFail($id);

        $data = $request->validate([
            'kelas_id'    => 'required|exists:kelas,id',
            'guru_id'     => 'required|exists:guru,id',
            'mapel_id'    => 'required|exists:mata_pelajaran,id',
            'hari'        => 'required|string|max:20',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required',
            'ruangan_id'  => 'nullable|exists:ruangan,id',
        ]);

        $jadwal->update($data);

        return redirect()
            ->route('tu.jadwal.index')
            ->with('success', 'Jadwal berhasil diperbarui!');
    }

    /**
     * DELETE — Soft Delete
     */
    public function destroy($id)
    {
        Jadwal::findOrFail($id)->delete();

        return redirect()
            ->route('tu.jadwal.index')
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

        switch ($request->action) {
            case 'delete':
                Jadwal::whereIn('id', $request->ids)->delete();
                break;

            case 'restore':
                Jadwal::onlyTrashed()->whereIn('id', $request->ids)->restore();
                break;

            case 'forceDelete':
                Jadwal::onlyTrashed()->whereIn('id', $request->ids)->forceDelete();
                break;
        }

        return response()->json(['message' => 'Aksi massal berhasil.']);
    }

    /**
     * AJAX LIVE SEARCH
     */
    public function ajaxSearch(Request $request)
    {
        $keyword = $request->q;

        $data = Jadwal::with(['kelas', 'guru.mapel', 'ruangan'])
            ->whereHas('kelas', fn ($q) => $q->where('nama_kelas', 'like', "%$keyword%"))
            ->orWhereHas('guru', fn ($q) => $q->where('nama', 'like', "%$keyword%"))
            ->orWhere('hari', 'like', "%$keyword%")
            ->get();

        return response()->json($data);
    }
}
