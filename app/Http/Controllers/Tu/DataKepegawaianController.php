<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\DataKepegawaian;
use Illuminate\Http\Request;

class DataKepegawaianController extends Controller
{
    // LIST DATA
    public function index()
    {
        $data = DataKepegawaian::all();
        return view('tu.datakepegawaian.index', compact('data'));
    }

    // FORM TAMBAH
    public function create()
    {
        return view('tu.datakepegawaian.create');
    }

    // SIMPAN DATA BARU
    public function store(Request $request)
    {
        DataKepegawaian::create($request->all());

        return redirect()->route('tu.datakepegawaian.index')
            ->with('success', 'Data pegawai berhasil ditambahkan!');
    }

    // DETAIL DATA (resource -> show)
    public function show($id)
    {
        $pegawai = DataKepegawaian::findOrFail($id);
        return view('tu.datakepegawaian.detail', compact('pegawai'));
    }

    // FORM EDIT
    public function edit($id)
    {
        $pegawai = DataKepegawaian::findOrFail($id);
        return view('tu.datakepegawaian.edit', compact('pegawai'));
    }

    // UPDATE DATA
    public function update(Request $request, $id)
    {
        $pegawai = DataKepegawaian::findOrFail($id);
        $pegawai->update($request->all());

        return redirect()->route('tu.datakepegawaian.index')
            ->with('success', 'Data pegawai berhasil diperbarui!');
    }

    // HAPUS DATA
    public function destroy($id)
    {
        DataKepegawaian::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Data pegawai dihapus!');
    }
}
