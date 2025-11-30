<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    public function index()
    {
        $tahun = TahunAjaran::orderBy('id', 'desc')->get();
        return view('tu.tahun.index', compact('tahun'));
    }

    public function create()
    {
        return view('tu.tahun.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun' => 'required',
            'semester' => 'required'
        ]);

        // jika diset aktif, nonaktifkan yang lain
        if ($request->aktif) {
            TahunAjaran::where('aktif', 1)->update(['aktif' => 0]);
        }

        TahunAjaran::create([
            'tahun' => $request->tahun,
            'semester' => $request->semester,
            'aktif' => $request->aktif ? 1 : 0,
        ]);

        return redirect()->route('tu.tahun.index')
                         ->with('success', 'Tahun ajaran berhasil ditambahkan');
    }

    public function edit($id)
    {
        $tahun = TahunAjaran::findOrFail($id);
        return view('tu.tahun.edit', compact('tahun'));
    }

    public function update(Request $request, $id)
    {
        $tahun = TahunAjaran::findOrFail($id);

        $request->validate([
            'tahun' => 'required',
            'semester' => 'required'
        ]);

        if ($request->aktif) {
            TahunAjaran::where('aktif', 1)->update(['aktif' => 0]);
        }

        $tahun->update([
            'tahun' => $request->tahun,
            'semester' => $request->semester,
            'aktif' => $request->aktif ? 1 : 0,
        ]);

        return redirect()->route('tu.tahun.index')
                         ->with('success', 'Tahun ajaran berhasil diperbarui');
    }

    public function destroy($id)
    {
        TahunAjaran::findOrFail($id)->delete();

        return back()->with('success', 'Tahun ajaran berhasil dihapus');
    }
}
