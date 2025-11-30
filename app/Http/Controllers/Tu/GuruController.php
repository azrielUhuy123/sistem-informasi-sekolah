<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use Illuminate\Http\Request;
use App\Models\MataPelajaran;

class GuruController extends Controller
{
    // LIST DATA GURU
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'id');
        $order = $request->get('order', 'asc');

        // Kolom yang diizinkan untuk sorting
        $allowed = ['id', 'nip', 'nama', 'mapel_id', 'jenis_kelamin'];
        if (!in_array($sort, $allowed)) {
            $sort = 'id';
        }

        $guru = Guru::with('mapel')   // penting!
                    ->orderBy($sort, $order)
                    ->paginate(20)
                    ->withQueryString();

        return view('tu.guru.index', compact('guru'));
    }

    // FORM TAMBAH
    public function create()
    {
        $mapel = MataPelajaran::orderBy('nama_mapel')->get();
        return view('tu.guru.create', compact('mapel'));
    }

    // SIMPAN
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'nip' => 'required|unique:guru,nip',
            'jenis_kelamin' => 'required',
            'mapel_id' => 'required|exists:mata_pelajaran,id',
            'email' => 'nullable|email|unique:guru,email',
            'telepon' => 'nullable',
            'alamat' => 'nullable',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = $request->only([
            'nama','nip','jenis_kelamin','email','telepon','alamat','mapel_id'
        ]);

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
        $mapel = MataPelajaran::orderBy('nama_mapel')->get();
        return view('tu.guru.edit', compact('guru','mapel'));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $guru = Guru::findOrFail($id);

        $request->validate([
            'nama' => 'required',
            'nip' => 'required|unique:guru,nip,' . $guru->id,
            'jenis_kelamin' => 'required',
            'mapel_id' => 'required|exists:mata_pelajaran,id',
            'email' => 'nullable|email|unique:guru,email,' . $guru->id,
            'telepon' => 'nullable',
            'alamat' => 'nullable',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = $request->only([
            'nama','nip','jenis_kelamin','email','telepon','alamat','mapel_id'
        ]);

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

    // HAPUS
    public function destroy($id)
    {
        $guru = Guru::findOrFail($id);

        if ($guru->foto && file_exists(public_path('foto-guru/'.$guru->foto))) {
            unlink(public_path('foto-guru/'.$guru->foto));
        }

        $guru->delete();

        return back()->with('success', 'Guru berhasil dihapus');
    }
}
