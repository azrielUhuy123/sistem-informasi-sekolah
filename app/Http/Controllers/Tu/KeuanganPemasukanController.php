<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\KeuanganPemasukan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KeuanganPemasukanController extends Controller
{
    public function index()
    {
        $pemasukan = KeuanganPemasukan::orderBy('tanggal', 'desc')->paginate(10);
        return view('tu.keuangan.pemasukan.index', compact('pemasukan'));
    }

    public function create()
    {
        return view('tu.keuangan.pemasukan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal'           => 'required|date',
            'sumber_dana'       => 'required|string|max:255',
            'periode_anggaran'  => 'required|string|max:100',
            'nominal'           => 'required|numeric|min:0',
            'dokumen'           => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $dokumen = null;
        if ($request->hasFile('dokumen')) {
            $dokumen = $request->file('dokumen')->store('dokumen-pemasukan', 'public');
        }

        KeuanganPemasukan::create([
            'tanggal'          => $request->tanggal,
            'sumber_dana'      => $request->sumber_dana,
            'periode_anggaran' => $request->periode_anggaran,
            'nominal'          => $request->nominal,
            'keterangan'       => $request->keterangan,
            'dokumen'          => $dokumen,
        ]);

        return redirect()
            ->route('tu.keuangan.pemasukan.index')
            ->with('success', 'Pemasukan berhasil ditambahkan');
    }
}
