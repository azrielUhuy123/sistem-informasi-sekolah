<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Dokumen;

class DownloadDokumenController extends Controller
{
    public function index()
    {
        $dokumen = Dokumen::where(
                'jenis_dokumen',
                'siswa'
            )
            ->latest()
            ->get();

        return view(
            'siswa.download-dokumen.index',
            compact('dokumen')
        );
    }
}