<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class GuruController extends Controller
{
    public function index(Request $request)
    {
        $guru = Guru::with('mapels')->paginate(10);

        return view('tu.guru.index', compact('guru'));
    }

    public function create()
    {
        // ambil mapel unik biar tidak double fase
        $mapel = MataPelajaran::all()
            ->unique('kode_mapel')
            ->values();

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

        // =========================
        // HANDLE FOTO
        // =========================
        if ($request->hasFile('foto')) {

            $file = $request->file('foto');

            $namaFile =
                time() . '.' .
                $file->getClientOriginalExtension();

            $file->move(
                public_path('foto-guru'),
                $namaFile
            );

            $data['foto'] = $namaFile;
        }

        // =========================
        // CREATE USER LOGIN
        // =========================
        $user = User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make('password123'),
            'role' => 'guru',
        ]);

        // =========================
        // RELASI USER
        // =========================
        $data['user_id'] = $user->id;

        // =========================
        // SIMPAN GURU
        // =========================
        $guru = Guru::create($data);

        // =========================
        // SIMPAN MAPEL
        // =========================
        if ($request->has('mapel_ids')) {

            $guru->mapels()->sync(
                $request->mapel_ids
            );
        }

        return redirect()
            ->route('tu.guru.index')
            ->with(
                'success',
                'Data guru berhasil ditambahkan'
            );
    }

    public function edit($id)
    {
        $guru = Guru::with('mapels')->findOrFail($id);

        $mapel = MataPelajaran::all()
            ->unique('kode_mapel')
            ->values();

        return view('tu.guru.edit', compact('guru', 'mapel'));
    }

    public function update(Request $request, $id)
    {
        $guru = Guru::findOrFail($id);

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
            $namaFile = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('foto-guru'), $namaFile);

            $data['foto'] = $namaFile;
        }

        // UPDATE DATA GURU
        $guru->update($data);

        // UPDATE RELASI MAPEL
        $guru->mapels()->sync($request->mapel_ids ?? []);

        return redirect()->route('tu.guru.index')
            ->with('success', 'Data guru berhasil diupdate');
    }

    public function show($id)
    {
        $guru = Guru::with('mapels')->findOrFail($id);

        return view('tu.guru.show', compact('guru'));
    }

    public function destroy($id)
    {
        Guru::findOrFail($id)->delete();

        return back()->with('success', 'Guru berhasil dihapus.');
    }
}