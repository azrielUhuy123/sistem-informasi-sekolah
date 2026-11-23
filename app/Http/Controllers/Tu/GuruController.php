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

    public function ajaxSearch(Request $request)
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'search' => ['nullable', 'string', 'max:100'],
        ]);
        $keyword = trim($validated['q'] ?? $validated['search'] ?? '');

        $gurus = Guru::query()
            ->with([
                'pegawai:id,nama,nip,jenis_kelamin',
                'mapels:id,nama_mapel',
            ])
            ->whereHas('pegawai')
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where(function ($query) use ($keyword) {
                    $query->whereHas('pegawai', function ($query) use ($keyword) {
                        $query->where('nama', 'like', "%{$keyword}%")
                            ->orWhere('nip', 'like', "%{$keyword}%")
                            ->orWhere('jenis_kelamin', 'like', "%{$keyword}%");
                    });
                    $query->orWhereHas('mapels', fn ($query) => $query
                        ->where('nama_mapel', 'like', "%{$keyword}%"));
                });
            })
            ->orderBy('id')
            ->limit(50)
            ->get()
            ->map(fn (Guru $guru) => [
                'id' => $guru->id,
                'nama' => $guru->pegawai?->nama,
                'nip' => $guru->pegawai?->nip,
                'jenis_kelamin' => $guru->pegawai?->jenis_kelamin,
                'mapel' => $guru->mapels->pluck('nama_mapel')->implode(', '),
            ]);

        return response()->json($gurus);
    }

    public function show($id)
    {
        $pegawai = Guru::with('pegawai')->findOrFail($id);
        return view('tu.guru.show', compact('pegawai'));
    }

    public function detail($id)
    {
        return $this->show($id);
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