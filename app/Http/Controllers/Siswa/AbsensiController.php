<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\AbsensiSiswa;

class AbsensiController extends Controller
{
    public function index()
    {
        $siswa = auth()->user()->siswa;

        $absensi = AbsensiSiswa::with('jadwal.mapel')
            ->where(
                'siswa_id',
                $siswa->id
            )
            ->latest('tanggal')
            ->paginate(20);

        return view(
            'siswa.absensi.index',
            compact(
                'siswa',
                'absensi'
            )
        );
    }
}