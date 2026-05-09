<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Dokumen;
use Illuminate\Http\Request;

class DokumenSuperAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Dokumen::where('jenis_dokumen', 'sekolah');

        // FILTER KATEGORI
        if ($request->kategori) {

            $query->where(
                'kategori_surat',
                $request->kategori
            );
        }

        // DATA DOKUMEN
        $dokumen = $query->latest()->get();

        // KATEGORI
        $kategori = Dokumen::where('jenis_dokumen', 'sekolah')
            ->select('kategori_surat')
            ->distinct()
            ->orderBy('kategori_surat')
            ->get();

        return view(
            'superadmin.dokumen.index',
            compact('dokumen', 'kategori')
        );
    }
}