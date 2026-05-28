<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\Ekskul;
use App\Models\Guru;
use Illuminate\Http\Request;

class EkskulController extends Controller
{
    public function index()
    {
        $ekskuls = Ekskul::with('guru')
            ->latest()
            ->get();

        return view(
            'tu.ekskul.index',
            compact('ekskuls')
        );
    }

    public function create()
    {
        $guru = Guru::orderBy('nama')
            ->get();

        return view(
            'tu.ekskul.create',
            compact('guru')
        );
    }

    public function store(Request $request)
    {
        Ekskul::create($request->all());

        return redirect()
            ->route('tu.ekskul.index')
            ->with(
                'success',
                'Ekskul berhasil ditambahkan'
            );
    }

    public function edit(Ekskul $ekskul)
    {
        $guru = Guru::orderBy('nama')
            ->get();

        return view(
            'tu.ekskul.edit',
            compact(
                'ekskul',
                'guru'
            )
        );
    }

    public function update(
        Request $request,
        Ekskul $ekskul
    ) {

        $ekskul->update($request->all());

        return redirect()
            ->route('tu.ekskul.index')
            ->with(
                'success',
                'Ekskul berhasil diupdate'
            );
    }

    public function destroy(Ekskul $ekskul)
    {
        $ekskul->delete();

        return back()->with(
            'success',
            'Ekskul berhasil dihapus'
        );
    }
}