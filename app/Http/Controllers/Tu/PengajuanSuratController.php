<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\PengajuanSurat;
use Illuminate\Http\Request;

class PengajuanSuratController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $pengajuan = PengajuanSurat::latest()->get();

        return view(
            'tu.pengajuan-surat.index',
            compact('pengajuan')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        return view('tu.pengajuan-surat.create');
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([

            'nama_surat' => 'required',

            'kategori_surat' => 'required',

            'tujuan_surat' => 'required',

            'file_surat' => 'required|mimes:pdf,doc,docx'

        ]);

        /*
        |--------------------------------------------------------------------------
        | UPLOAD FILE
        |--------------------------------------------------------------------------
        */
        $file = $request->file('file_surat');

        $namaFile = time() . '_' . $file->getClientOriginalName();

        $file->move(
            public_path('uploads/pengajuan-surat'),
            $namaFile
        );

        /*
        |--------------------------------------------------------------------------
        | SIMPAN DATABASE
        |--------------------------------------------------------------------------
        */
        PengajuanSurat::create([

            'user_id' => auth()->id(),

            'nama_surat' => $request->nama_surat,

            'kategori_surat' => $request->kategori_surat,

            'tujuan_surat' => $request->tujuan_surat,

            'file_surat' => $namaFile,

            'status' => 'pending'

        ]);

        return redirect()
            ->route('tu.pengajuan-surat.index')
            ->with(
                'success',
                'Pengajuan surat berhasil dibuat'
            );
    }

    public function show($id)
    {
        $pengajuan = PengajuanSurat::findOrFail($id);

        return view(
            'tu.pengajuan-surat.show',
            compact('pengajuan')
        );
    }
}