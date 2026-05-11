<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\PengajuanSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

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

            'nama_surat'     => 'required',
            'kategori_surat' => 'required',
            'perihal'        => 'required',
            'tujuan_surat'   => 'required',
            'file_surat'     => 'required|mimes:pdf,doc,docx'

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

            'user_id'         => auth()->id(),

            // PENGIRIM OTOMATIS
            'pengirim'        => auth()->user()->role,

            'nama_surat'      => $request->nama_surat,
            'kategori_surat'  => $request->kategori_surat,
            'perihal'         => $request->perihal,
            'tujuan_surat'    => $request->tujuan_surat,

            'file_surat'      => $namaFile,

            'status'          => 'draft'

        ]);

        return redirect()
            ->route('tu.pengajuan-surat.index')
            ->with(
                'success',
                'Pengajuan surat berhasil dibuat'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $pengajuan = PengajuanSurat::findOrFail($id);

        return view(
            'tu.pengajuan-surat.show',
            compact('pengajuan')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */
    public function edit($id)
    {
        $pengajuan = PengajuanSurat::findOrFail($id);

        return view(
            'tu.pengajuan-surat.edit',
            compact('pengajuan')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $pengajuan = PengajuanSurat::findOrFail($id);

        $request->validate([

            'nama_surat'     => 'required',
            'kategori_surat' => 'required',
            'perihal'        => 'required',
            'tujuan_surat'   => 'required',

        ]);

        /*
        |--------------------------------------------------------------------------
        | DATA UPDATE
        |--------------------------------------------------------------------------
        */
        $data = [

            'nama_surat'      => $request->nama_surat,
            'kategori_surat'  => $request->kategori_surat,
            'perihal'         => $request->perihal,
            'tujuan_surat'    => $request->tujuan_surat,

            /*
            |--------------------------------------------------------------------------
            | RESET VALIDASI
            |--------------------------------------------------------------------------
            */
            'status'          => 'draft',
            'validated_by'    => null,
            'validated_at'    => null,

        ];

        /*
        |--------------------------------------------------------------------------
        | UPDATE FILE BARU
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('file_surat')) {

            // HAPUS FILE LAMA
            $oldFile = public_path(
                'uploads/pengajuan-surat/' . $pengajuan->file_surat
            );

            if (File::exists($oldFile)) {

                File::delete($oldFile);

            }

            // SIMPAN FILE BARU
            $file = $request->file('file_surat');

            $namaFile = time() . '_' . $file->getClientOriginalName();

            $file->move(
                public_path('uploads/pengajuan-surat'),
                $namaFile
            );

            $data['file_surat'] = $namaFile;
        }

        $pengajuan->update($data);

        return redirect()
            ->route('tu.pengajuan-surat.index')
            ->with(
                'success',
                'Pengajuan surat berhasil diupdate'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $pengajuan = PengajuanSurat::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | HAPUS FILE SURAT
        |--------------------------------------------------------------------------
        */
        $fileSurat = public_path(
            'uploads/pengajuan-surat/' . $pengajuan->file_surat
        );

        if (File::exists($fileSurat)) {

            File::delete($fileSurat);

        }

        $pengajuan->delete();

        return redirect()
            ->route('tu.pengajuan-surat.index')
            ->with(
                'success',
                'Pengajuan surat berhasil dihapus'
            );
    }
}