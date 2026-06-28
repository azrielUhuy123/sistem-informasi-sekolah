<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\Dokumen;
use Illuminate\Http\Request;

class DokumenController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DOKUMEN SEKOLAH
    |--------------------------------------------------------------------------
    */
    public function sekolah()
    {
        $dokumen = Dokumen::where('jenis_dokumen', 'sekolah')
            ->latest()
            ->get();

        return view('tu.surat.sekolah.index', compact('dokumen'));
    }

    /*
    |--------------------------------------------------------------------------
    | DOKUMEN SISWA
    |--------------------------------------------------------------------------
    */
    public function siswa()
    {
        $dokumen = Dokumen::where('jenis_dokumen', 'siswa')
            ->latest()
            ->get();

        return view('tu.surat.siswa.index', compact('dokumen'));
    }

    /*
    |--------------------------------------------------------------------------
    | FORM CREATE
    |--------------------------------------------------------------------------
    */
    public function create($jenis)
    {
        if ($jenis == 'sekolah') {

            return view('tu.surat.sekolah.create');

        } else {

            return view('tu.surat.siswa.create');

        }
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
            'file' => 'required|mimes:pdf,doc,docx'
        ]);

        $file = $request->file('file');

        $namaFile = time() . '_' . $file->getClientOriginalName();

        $file->move(public_path('uploads/dokumen'), $namaFile);

        Dokumen::create([
            'nama_surat' => $request->nama_surat,
            'kategori_surat' => $request->kategori_surat,
            'jenis_dokumen' => $request->jenis_dokumen,
            'file' => $namaFile
        ]);

        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        if ($request->jenis_dokumen == 'sekolah') {

            return redirect()
                ->route('tu.dokumen.sekolah')
                ->with('success', 'Dokumen sekolah berhasil ditambahkan');

        } else {

            return redirect()
                ->route('tu.dokumen.siswa')
                ->with('success', 'Dokumen siswa berhasil ditambahkan');

        }
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $dokumen = Dokumen::findOrFail($id);

        $path = public_path('uploads/dokumen/' . $dokumen->file);

        if (file_exists($path)) {
            unlink($path);
        }

        $dokumen->delete();

        return redirect()->back()
            ->with('success', 'Dokumen berhasil dihapus');
    }

    public function edit($id)
    {
        $dokumen = Dokumen::findOrFail($id);

        if ($dokumen->jenis_dokumen == 'sekolah') {

            return view('tu.surat.sekolah.edit', compact('dokumen'));

        } else {

            return view('tu.surat.siswa.edit', compact('dokumen'));

        }
    }

    public function update(Request $request, $id)
    {
        $dokumen = Dokumen::findOrFail($id);

        $request->validate([
            'nama_surat' => 'required',
            'kategori_surat' => 'required'
        ]);

        // Update file jika upload baru
        if ($request->hasFile('file')) {

            $oldPath = public_path('uploads/dokumen/' . $dokumen->file);

            if (file_exists($oldPath)) {
                unlink($oldPath);
            }

            $file = $request->file('file');

            $namaFile = time() . '_' . $file->getClientOriginalName();

            $file->move(public_path('uploads/dokumen'), $namaFile);

            $dokumen->file = $namaFile;
        }

        $dokumen->nama_surat = $request->nama_surat;
        $dokumen->kategori_surat = $request->kategori_surat;

        $dokumen->save();
        
        if ($dokumen->jenis_dokumen == 'sekolah') {

            return redirect()
                ->route('tu.dokumen.sekolah')
                ->with('success', 'Dokumen sekolah berhasil diperbarui');

        } else {

            return redirect()
                ->route('tu.dokumen.siswa')
                ->with('success', 'Dokumen siswa berhasil diperbarui');

        }
    }
}