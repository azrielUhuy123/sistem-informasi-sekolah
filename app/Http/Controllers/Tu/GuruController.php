<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\DataKepegawaian;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class GuruController extends Controller
{
    public function index()
    {
        $gurus = Guru::with([
            'pegawai',
            'waliKelas.kelas',
            'pengampuMapel.mapel',
            'pengampuMapel.kelas',
        ])
        ->latest()
        ->get();

        return view(
            'tu.guru.index',
            compact('gurus')
        );
    }

    public function show($id)
    {
        $pegawai = Guru::with('pegawai')->findOrFail($id);
        return view('tu.guru.show', compact('pegawai'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'data_kepegawaian_id' => 'required|exists:data_kepegawaian,id',
            'mapel_ids' => 'nullable|array',
            'mapel_ids.*' => 'exists:mata_pelajaran,id',
        ]);

        $pegawai = DataKepegawaian::findOrFail(
            $request->data_kepegawaian_id
        );

        if ($pegawai->guru) {
            return back()
                ->withInput()
                ->with('error', 'Pegawai tersebut sudah terdaftar sebagai guru.');
        }

        $guru = Guru::create([
            'data_kepegawaian_id' => $pegawai->id,
        ]);

        $guru->mapels()->sync(
            $request->mapel_ids ?? []
        );

        return redirect()
            ->route('tu.guru.index')
            ->with(
                'success',
                'Data guru berhasil ditambahkan.'
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

    public function destroy($id)
    {
        Guru::findOrFail($id)->delete();

        return back()->with('success', 'Guru berhasil dihapus.');
    }
}