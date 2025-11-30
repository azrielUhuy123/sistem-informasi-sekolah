<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class MataPelajaranController extends Controller
{
    // INDEX
    public function index()
    {
        $mapel = MataPelajaran::orderBy('nama_mapel')->paginate(20);
        return view('tu.mapel.index', compact('mapel'));
    }

    // CREATE FORM
    public function create()
    {
        return view('tu.mapel.create');
    }

    // STORE
    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'nullable|unique:mata_pelajaran,kode',
            'nama_mapel' => 'required',
            'kkm' => 'required|numeric|min:0|max:100'
        ]);

        MataPelajaran::create($request->all());

        return redirect()->route('tu.mapel.index')
            ->with('success', 'Mata pelajaran berhasil ditambahkan');
    }

    // EDIT FORM
    public function edit($id)
    {
        $mapel = MataPelajaran::findOrFail($id);
        return view('tu.mapel.edit', compact('mapel'));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $mapel = MataPelajaran::findOrFail($id);

        $request->validate([
            'kode' => 'nullable|unique:mata_pelajaran,kode,' . $mapel->id,
            'nama_mapel' => 'required',
            'kkm' => 'required|numeric|min:0|max:100'
        ]);

        $mapel->update($request->all());

        return redirect()->route('tu.mapel.index')
            ->with('success', 'Mata pelajaran berhasil diupdate');
    }

    // DELETE
    public function destroy($id)
    {
        $mapel = MataPelajaran::findOrFail($id);
        $mapel->delete();

        return back()->with('success', 'Mata pelajaran berhasil dihapus');
    }
}
