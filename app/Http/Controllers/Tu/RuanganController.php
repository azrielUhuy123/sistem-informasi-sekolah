<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    /**
     * INDEX
     */
    public function index()
    {
        $ruangan = Ruangan::orderBy('nama_ruangan')->paginate(10);
        return view('tu.ruangan.index', compact('ruangan'));
    }

    /**
     * CREATE PAGE
     */
    public function create()
    {
        return view('tu.ruangan.create');
    }

    /**
     * STORE (TAMBAH DATA)
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_ruangan' => 'required|string|max:50|unique:ruangan,kode_ruangan',
            'nama_ruangan' => 'required|string|max:255',
            'kapasitas' => 'nullable|integer',
            'jenis_ruangan' => 'nullable|string|max:100',
        ]);

        Ruangan::create([
            'kode_ruangan' => $request->kode_ruangan,
            'nama_ruangan' => $request->nama_ruangan,
            'kapasitas' => $request->kapasitas,
            'jenis_ruangan' => $request->jenis_ruangan,
        ]);

        return redirect()
            ->route('tu.ruangan.index')
            ->with('success', 'Ruangan berhasil ditambahkan.');
    }

    /**
     * EDIT PAGE
     */
    public function edit($id)
    {
        $ruangan = Ruangan::findOrFail($id);
        return view('tu.ruangan.edit', compact('ruangan'));
    }

    /**
     * UPDATE (EDIT DATA)
     */
    public function update(Request $request, $id)
    {
        $ruangan = Ruangan::findOrFail($id);

        $request->validate([
            'kode_ruangan' => 'required|string|max:50|unique:ruangan,kode_ruangan,' . $ruangan->id,
            'nama_ruangan' => 'required|string|max:255',
            'kapasitas' => 'nullable|integer',
            'jenis_ruangan' => 'nullable|string|max:100',
        ]);

        $ruangan->update([
            'kode_ruangan' => $request->kode_ruangan,
            'nama_ruangan' => $request->nama_ruangan,
            'kapasitas' => $request->kapasitas,
            'jenis_ruangan' => $request->jenis_ruangan,
        ]);

        return redirect()
            ->route('tu.ruangan.index')
            ->with('success', 'Ruangan berhasil diperbarui.');
    }

    /**
     * DELETE
     */
    public function destroy($id)
    {
        Ruangan::findOrFail($id)->delete();

        return back()->with('success', 'Ruangan berhasil dihapus.');
    }
}