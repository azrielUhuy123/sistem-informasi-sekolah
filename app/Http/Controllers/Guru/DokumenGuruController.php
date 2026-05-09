<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dokumen;

class DokumenGuruController extends Controller
{
    public function index(Request $request)
    {
        $query = Dokumen::where('jenis_dokumen', 'sekolah');

        // FILTER
        if ($request->kategori) {

            $query->where(
                'kategori_surat',
                $request->kategori
            );
        }

        // DATA DOKUMEN
        $dokumen = $query->latest()->get();

        // AMBIL SEMUA KATEGORI UNIK
        $kategori = Dokumen::where('jenis_dokumen', 'sekolah')
            ->select('kategori_surat')
            ->distinct()
            ->orderBy('kategori_surat')
            ->get();

        return view(
            'guru.dokumen.index',
            compact('dokumen', 'kategori')
        );
    }
}