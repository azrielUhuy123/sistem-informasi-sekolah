<?php

namespace App\Http\Controllers\PPDB;

use App\Http\Controllers\Controller;
use App\Models\PpdbSetting;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function index()
    {
        $tahunAjaranId = request('tahun_ajaran_id') ?? current_tahun_id();
        $setting = PpdbSetting::where('tahun_ajaran_id', $tahunAjaranId)
            ->orderBy('id')
            ->first() ?? new PpdbSetting(['tahun_ajaran_id' => $tahunAjaranId]);

        $tahunAjaran = TahunAjaran::orderByDesc('id')
            ->get();

        return view(
            'ppdb.pengaturan.index',
            compact(
                'setting',
                'tahunAjaran'
            )
        );
    }

    public function store(
        Request $request
    )
    {
        $request->validate([

            'tahun_ajaran_id'
                => 'required',

            'tanggal_mulai'
                => 'required|date',

            'tanggal_selesai'
                => 'required|date|after_or_equal:tanggal_mulai',

            'kuota'
                => 'required|integer|min:1',

            'status'
                => 'required|in:dibuka,ditutup'
        ]);

        PpdbSetting::updateOrCreate(
            [
                'tahun_ajaran_id' => $request->tahun_ajaran_id,
            ],
            [
                'tahun_ajaran_id'
                    => $request->tahun_ajaran_id,

                'tanggal_mulai'
                    => $request->tanggal_mulai,

                'tanggal_selesai'
                    => $request->tanggal_selesai,

                'kuota'
                    => $request->kuota,

                'status'
                    => $request->status,
            ]
        );

        return redirect()
            ->route('tu.ppdb.pengaturan.index', [
                'tahun_ajaran_id' => $request->tahun_ajaran_id,
            ])
            ->with('success', 'Pengaturan PPDB berhasil disimpan');
    }
}