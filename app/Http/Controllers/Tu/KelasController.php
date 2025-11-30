<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    /**
     * INDEX — hanya sorting, tanpa filter
     */
    public function index(Request $request)
    {
        $query = Kelas::query();

        // ========================
        // SORTING
        // ========================
        $sort = $request->get('sort', 'id'); // default sorting by ID
        $order = $request->get('order', 'asc');

        // hanya boleh sorting berdasarkan kolom ini
        $allowedSort = ['id', 'nama_kelas', 'tingkat', 'tahun_ajaran'];

        if (!in_array($sort, $allowedSort)) {
            $sort = 'id';
        }

        $query->orderBy($sort, $order);

        $kelas = $query->get();

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
     * STORE — Tambah kelas
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas'   => 'required',
            'tingkat'      => 'required|integer',
            'tahun_ajaran' => 'required',
        ]);

        Kelas::create([
            'nama_kelas'   => $request->nama_kelas,
            'tingkat'      => $request->tingkat,
            'tahun_ajaran' => $request->tahun_ajaran,
        ]);

        return redirect()->route('tu.kelas.index')
            ->with('success', 'Kelas berhasil ditambahkan');
    }


    /**
     * EDIT PAGE
     */
    public function edit($id)
    {
        $kelas = Kelas::findOrFail($id);
        return view('tu.kelas.edit', compact('kelas'));
    }


    /**
     * UPDATE kelas
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kelas'   => 'required',
            'tingkat'      => 'required|integer',
            'tahun_ajaran' => 'required',
        ]);

        $kelas = Kelas::findOrFail($id);

        $kelas->update([
            'nama_kelas'   => $request->nama_kelas,
            'tingkat'      => $request->tingkat,
            'tahun_ajaran' => $request->tahun_ajaran,
        ]);

        return redirect()->route('tu.kelas.index')
            ->with('success', 'Kelas berhasil diupdate');
    }


    /**
     * DELETE kelas
     */
    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();

        return redirect()->route('tu.kelas.index')
            ->with('success', 'Kelas berhasil dihapus');
    }
}
