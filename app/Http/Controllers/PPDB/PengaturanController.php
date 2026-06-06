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
        $data = PpdbSetting::latest()
            ->paginate(10);

        return view(
            'ppdb.pengaturan.index',
            compact('data')
        );
    }

    public function create()
    {
        $tahunAjaran = TahunAjaran::all();

        return view(
            'ppdb.pengaturan.create',
            compact('tahunAjaran')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_ajaran_id' => 'required',
            'tanggal_mulai'   => 'required',
            'tanggal_selesai' => 'required',
            'kuota'           => 'required',
            'status'          => 'required'
        ]);

        PpdbSetting::create(
            $request->all()
        );

        return redirect()
            ->route(
                'tu.ppdb.pengaturan.index'
            )
            ->with(
                'success',
                'Pengaturan berhasil disimpan'
            );
    }
}