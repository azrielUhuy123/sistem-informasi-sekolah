<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\PenempatanSiswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class RombelController extends Controller
{
    public function index()
    {
        $tahunAktif = TahunAjaran::aktif();

            if (!$tahunAktif) {
                abort(500, 'Tahun ajaran aktif belum diset');
            }

        $kelas = Kelas::withCount([
            'penempatan as jumlah_siswa' => function ($query) use ($tahunAktif) {
                $query->where('tahun_ajaran_id', $tahunAktif->id)
                      ->whereNull('tanggal_keluar');
            }
        ])->get();

        return view('rombel.index', compact('kelas'));
    }

    public function show($id)
    {
        $kelas = Kelas::findOrFail($id);

        $tahunAktif = TahunAjaran::aktif();

        if (!$tahunAktif) {
            abort(500, 'Tahun ajaran aktif belum diset');
        }

        $penempatan = $kelas->penempatan()
            ->with('siswa')
            ->where('tahun_ajaran_id', $tahunAktif->id)
            ->whereNull('tanggal_keluar')
            ->get();

        return view('rombel.show', compact('kelas', 'penempatan'));
    }
}
