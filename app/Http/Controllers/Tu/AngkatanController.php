<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\Angkatan;
use Illuminate\Http\Request;

class AngkatanController extends Controller
{
    public function index()
    {
        $angkatan = Angkatan::orderBy('tahun', 'desc')->paginate(10);

        return view('tu.angkatan.index', compact('angkatan'));
    }

    public function create()
    {
        return view('tu.angkatan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun' => 'required|digits:4|unique:angkatan,tahun',
            'keterangan' => 'nullable|string',
        ]);

        Angkatan::create([
            'tahun' => $request->tahun,
            'keterangan' => $request->keterangan,
            'aktif' => true,
        ]);

        return redirect()
            ->route('tu.angkatan.index')
            ->with('success', 'Angkatan berhasil ditambahkan');
    }

    public function edit(Angkatan $angkatan)
    {
        return view('tu.angkatan.edit', compact('angkatan'));
    }

    public function update(Request $request, Angkatan $angkatan)
    {
        $request->validate([
            'tahun' => 'required|digits:4|unique:angkatan,tahun,' . $angkatan->id,
            'keterangan' => 'nullable|string',
        ]);

        $angkatan->update($request->only('tahun', 'keterangan'));

        return redirect()
            ->route('tu.angkatan.index')
            ->with('success', 'Angkatan berhasil diperbarui');
    }

    public function destroy(Angkatan $angkatan)
    {
        $angkatan->delete();

        return back()->with('success', 'Angkatan berhasil dihapus');
    }

    /**
     * Toggle Aktif / Nonaktif
     */
    public function toggleAktif(Angkatan $angkatan)
    {
        $angkatan->update([
            'aktif' => !$angkatan->aktif
        ]);

        return back()->with('success', 'Status angkatan diperbarui');
    }
}
