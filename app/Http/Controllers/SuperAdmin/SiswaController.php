<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Angkatan;
use App\Models\Kelas;
use App\Models\KelasGrup;
use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::withTrashed()
            ->with([
                'penempatanAktif.kelas.kelasGrup.angkatan'
            ]);

        if ($request->filled('search')) {

            $query->where(function ($q) use ($request) {

                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('nis', 'like', '%' . $request->search . '%');

            });
        }

        if ($request->status == 'aktif') {
            $query->whereNull('deleted_at');
        }

        if ($request->status == 'nonaktif') {
            $query->onlyTrashed();
        }

        $siswa = $query
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view(
            'superadmin.siswa.index',
            [
                'siswa'      => $siswa,
                'angkatan'   => Angkatan::all(),
                'kelasGrup'  => KelasGrup::with('kelasMaster')->get(),
                'kelas'      => Kelas::all(),
            ]
        );
    }

    public function show(Siswa $siswa)
    {
        return view(
            'superadmin.siswa.show',
            compact('siswa')
        );
    }
}