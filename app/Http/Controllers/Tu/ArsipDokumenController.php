<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\ArsipDokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class ArsipDokumenController extends Controller
{
    /**
     * Tampilkan semua dokumen.
     */
    public function index()
    {
        $data = ArsipDokumen::latest()->get();
        return view('tu.surat.index', compact('data'));
    }

    /**
     * Tampilkan form tambah dokumen.
     */
    public function create()
    {
        return view('tu.surat.create');
    }

    /**
     * Simpan dokumen baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_surat'      => 'required|string|max:255',
            'nomor_surat'     => 'nullable|string|max:255',
            'tanggal_dibuat'  => 'nullable|date',
            'kategori'        => 'nullable|string|max:255',
            'file_dokumen'    => 'required|file|mimes:pdf,doc,docx|max:50000',
            'keterangan'      => 'nullable|string',
        ]);

        // Simpan file ke storage/arsip
        $filePath = $request->file('file_dokumen')->store('arsip', 'public');

        ArsipDokumen::create([
            'nama_surat'     => $request->nama_surat,
            'nomor_surat'    => $request->nomor_surat,
            'tanggal_dibuat' => $request->tanggal_dibuat,
            'kategori'       => $request->kategori,
            'file_path'      => $filePath,
            'keterangan'     => $request->keterangan,
        ]);

        return redirect()->route('tu.surat.index')->with('success', 'Dokumen berhasil disimpan.');
    }

    /**
     * Preview PDF tanpa download.
     */
    public function preview($id)
    {
        $doc = ArsipDokumen::findOrFail($id);

        // hanya bisa untuk PDF
        if (pathinfo($doc->file_path, PATHINFO_EXTENSION) !== 'pdf') {
            return redirect()->back()->with('error', 'Preview hanya tersedia untuk file PDF.');
        }

        $fileUrl = asset('storage/' . $doc->file_path);

        return view('tu.surat.preview', compact('fileUrl'));
    }

    /**
     * Download file dokumen.
     */
    public function download($id)
    {
        $doc = ArsipDokumen::findOrFail($id);
        $path = Storage::disk('public')->path($doc->file_path);
        return response()->download($path);
    }

    /**
     * Tampilkan form edit metadata.
     */
    public function edit($id)
    {
        $doc = ArsipDokumen::findOrFail($id);
        return view('tu.surat.edit', compact('doc'));
    }

    /**
     * Update metadata dokumen.
     */
    public function update(Request $request, $id)
    {
        $doc = ArsipDokumen::findOrFail($id);

        $request->validate([
            'nama_surat'      => 'required|string|max:255',
            'nomor_surat'     => 'nullable|string|max:255',
            'tanggal_dibuat'  => 'nullable|date',
            'kategori'        => 'nullable|string|max:255',
            'keterangan'      => 'nullable|string',
        ]);

        $doc->update([
            'nama_surat'     => $request->nama_surat,
            'nomor_surat'    => $request->nomor_surat,
            'tanggal_dibuat' => $request->tanggal_dibuat,
            'kategori'       => $request->kategori,
            'keterangan'     => $request->keterangan,
        ]);

        return redirect()->route('tu.surat.index')->with('success', 'Data berhasil diperbarui.');
    }

    /**
     * Hapus dokumen + file fisik.
     */
    public function destroy($id)
    {
        $doc = ArsipDokumen::findOrFail($id);

        // Hapus file dari storage
        if (Storage::disk('public')->exists($doc->file_path)) {
            Storage::disk('public')->delete($doc->file_path);
        }

        $doc->delete();

        return redirect()->route('tu.surat.index')->with('success', 'Dokumen berhasil dihapus.');
    }
}
