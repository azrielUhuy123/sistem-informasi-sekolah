<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->q;

        // Cari siswa
        $siswa = Siswa::where('nama', 'like', "%$q%")
                       ->orWhere('nis', 'like', "%$q%")
                       ->get();

        // Cari kelas
        $kelas = Kelas::where('nama_kelas', 'like', "%$q%")
                       ->get();

        return view('tu.search.index', compact('q', 'siswa', 'kelas'));
    }

    public function ajax(Request $request)
    {
        $q = $request->q;

        // Cari siswa
        $siswa = Siswa::where('nama', 'like', "%$q%")
                  ->limit(5)
                  ->get(['id', 'nama', 'nis', 'kelas_id']);

        // Cari kelas
        $kelas = Kelas::where('nama_kelas', 'like', "%$q%")
                  ->limit(5)
                  ->get(['id', 'nama_kelas'f]);

        return response()->json([
            'siswa' => $siswa,
            'kelas' => $kelas
        ]);
    }

}
