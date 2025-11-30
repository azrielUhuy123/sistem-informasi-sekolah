<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    // LIST DATA GURU
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'id');
        $order = $request->get('order', 'asc');

        $allowed = ['id', 'nip', 'nama', 'mapel', 'jenis_kelamin'];
        if (!in_array($sort, $allowed)) {
            $sort = 'id';
        }

        $guru = Guru::orderBy($sort, $order)->paginate(20)->withQueryString();

        return view('tu.guru.index', compact('guru'));
    }

    // FORM TAMBAH
    public function create()
    {
        return view('tu.guru.create');
    }

    // SIMPAN DATA GURU
    public function store(Request $request)
    {
        $request->validate([
            'nama'          => 'required',
            'nip'           => 'required|unique:guru,nip',
            'jenis_kelamin' => 'required',
            'mapel'         => 'required',   // <- BENAR
            'email'         => 'nullable|email|unique:guru,email',
            'telepon'       => 'nullable',
            'alamat'        => 'nullable',
            'foto'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = [
            'nama'          => $request->nama,
            'nip'           => $request->nip,
            'jenis_kelamin' => $request->jenis_kelamin,
            'mapel'         => $request->mapel,
            'email'         => $request->email,
            'telepon'       => $request->telepon,
            'alamat'        => $request->alamat,
        ];

        // Upload foto
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '-' . $file->getClientOriginalName();
            $file->move(public_path('foto-guru'), $filename);
            $data['foto'] = $filename;
        }

        Guru::create($data);

        return redirect()->route('tu.guru.index')
                         ->with('success', 'Guru berhasil ditambahkan');
    }

    // FORM EDIT
    public function edit($id)
    {
        $guru = Guru::findOrFail($id);
        return view('tu.guru.edit', compact('guru'));
    }

    // UPDATE GURU
    public function update(Request $request, $id)
    {
        $guru = Guru::findOrFail($id);

        $request->validate([
            'nama'          => 'required',
            'nip'           => 'required|unique:guru,nip,' . $guru->id,
            'jenis_kelamin' => 'required',
            'mapel'         => 'required',
            'email'         => 'nullable|email|unique:guru,email,' . $guru->id,
            'telepon'       => 'nullable',
            'alamat'        => 'nullable',
            'foto'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = [
            'nama'          => $request->nama,
            'nip'           => $request->nip,
            'jenis_kelamin' => $request->jenis_kelamin,
            'mapel'         => $request->mapel,
            'email'         => $request->email,
            'telepon'       => $request->telepon,
            'alamat'        => $request->alamat,
        ];

        // Update foto
        if ($request->hasFile('foto')) {

            // hapus foto lama jika ada
            if ($guru->foto && file_exists(public_path('foto-guru/'.$guru->foto))) {
                unlink(public_path('foto-guru/'.$guru->foto));
            }

            // upload baru
            $file = $request->file('foto');
            $filename = time() . '-' . $file->getClientOriginalName();
            $file->move(public_path('foto-guru'), $filename);
            $data['foto'] = $filename;
        }

        $guru->update($data);

        return redirect()->route('tu.guru.index')
                         ->with('success', 'Guru berhasil diupdate');
    }

    // HAPUS GURU
    public function destroy($id)
    {
        $guru = Guru::findOrFail($id);

        // hapus foto
        if ($guru->foto && file_exists(public_path('foto-guru/'.$guru->foto))) {
            unlink(public_path('foto-guru/'.$guru->foto));
        }

        $guru->delete();

        return back()->with('success', 'Guru berhasil dihapus');
    }
}
