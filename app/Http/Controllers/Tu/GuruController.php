<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        $guru = Guru::with('mapels')->paginate(10);

        return view('tu.guru.index', compact('guru'));
    }

    public function create()
    {
        $mapel = MataPelajaran::orderBy('nama_mapel')->get();

        return view('tu.guru.create', compact('mapel'));
    }

    public function store(Request $request)
{
    $data = $request->only([
        'nama',
        'nip',
        'jenis_kelamin',
        'email',
        'telepon',
        'alamat'
    ]);

    // HANDLE FOTO
    if ($request->hasFile('foto')) {
        $file = $request->file('foto');
        $namaFile = time().'.'.$file->getClientOriginalExtension();

        // pindahkan ke folder public/foto-guru
        $file->move(public_path('foto-guru'), $namaFile);

        $data['foto'] = $namaFile;
    }

    \App\Models\Guru::create($data);

    return redirect()->route('tu.guru.index')
        ->with('success', 'Data guru berhasil ditambahkan');
}

    public function edit($id)
    {
        $guru  = Guru::findOrFail($id);
        $mapel = MataPelajaran::orderBy('nama_mapel')->get();

        return view('tu.guru.edit', compact('guru', 'mapel'));
    }

    public function update(Request $request, $id)
{
    $guru = Guru::findOrFail($id);

    $data = $request->all();

if ($request->hasFile('foto')) {
    $file = $request->file('foto');
    $namaFile = time().'.'.$file->getClientOriginalExtension();
    $file->move(public_path('foto-guru'), $namaFile);

    $data['foto'] = $namaFile;
}

$guru->update($data);

    return redirect()->route('tu.guru.index')
        ->with('success', 'Data guru berhasil diupdate');
    }

    public function show($id)
    {
        $guru = \App\Models\Guru::findOrFail($id);

        return view('tu.guru.show', compact('guru'));
    }

    public function destroy($id)
    {
        Guru::findOrFail($id)->delete();

        return back()->with('success', 'Guru berhasil dihapus.');
    }
    
}
