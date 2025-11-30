<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    public function index()
    {
        $ruangan = Ruangan::orderBy('nama_ruangan')->paginate(20);
        return view('tu.ruangan.index', compact('ruangan'));
    }

    public function create()
    {
        return view('tu.ruangan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:ruangan,kode',
            'nama_ruangan' => 'required',
            'kapasitas' => 'nullable|integer',
            'jenis' => 'nullable'
        ]);

        Ruangan::create($request->all());

        return redirect()->route('tu.ruangan.index')
                         ->with('success', 'Ruangan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $ruangan = Ruangan::findOrFail($id);
        return view('tu.ruangan.edit', compact('ruangan'));
    }

    public function update(Request $request, $id)
    {
        $ruangan = Ruangan::findOrFail($id);

        $request->validate([
            'kode' => 'required|unique:ruangan,kode,' . $ruangan->id,
            'nama_ruangan' => 'required',
            'kapasitas' => 'nullable|integer',
            'jenis' => 'nullable'
        ]);

        $ruangan->update($request->all());

        return redirect()->route('tu.ruangan.index')
                         ->with('success', 'Ruangan berhasil diupdate');
    }

    public function destroy($id)
    {
        $ruangan = Ruangan::findOrFail($id);
        $ruangan->delete();

        return back()->with('success', 'Ruangan berhasil dihapus');
    }
}
