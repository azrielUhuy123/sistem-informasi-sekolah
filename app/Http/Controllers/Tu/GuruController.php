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
        $guru = Guru::with('mapel')->paginate(10);

        return view('tu.guru.index', compact('guru'));
    }

    public function create()
    {
        $mapel = MataPelajaran::orderBy('nama_mapel')->get();

        return view('tu.guru.create', compact('mapel'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'          => 'required|string|max:255',
            'nip'           => 'nullable|string|max:50|unique:guru,nip',
            'jenis_kelamin' => 'required|in:L,P',
            'mapel_id'      => 'required|exists:mata_pelajaran,id',
            'email'         => 'nullable|email|max:255',
            'telepon'       => 'nullable|string|max:50',
            'alamat'        => 'nullable|string',
            'foto'          => 'nullable|image|max:2048',
        ]);

        $data = $request->except('foto');

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('guru', 'public');
        }

        Guru::create($data);

        return redirect()
            ->route('tu.guru.index')
            ->with('success', 'Guru berhasil ditambahkan.');
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

        $request->validate([
            'nama'          => 'required|string|max:255',
            'nip'           => 'nullable|string|max:50|unique:guru,nip,' . $guru->id,
            'jenis_kelamin' => 'required|in:L,P',
            'mapel_id'      => 'required|exists:mata_pelajaran,id',
            'email'         => 'nullable|email|max:255',
            'telepon'       => 'nullable|string|max:50',
            'alamat'        => 'nullable|string',
            'foto'          => 'nullable|image|max:2048',
        ]);

        $data = $request->except('foto');

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('guru', 'public');
        }

        $guru->update($data);

        return redirect()
            ->route('tu.guru.index')
            ->with('success', 'Guru berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Guru::findOrFail($id)->delete();

        return back()->with('success', 'Guru berhasil dihapus.');
    }
}
