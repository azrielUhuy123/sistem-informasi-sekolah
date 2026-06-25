<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\WaliKelas;
use Illuminate\Http\Request;

class WaliKelasController extends Controller
{
    public function index()
    {
        $waliKelas = WaliKelas::with([
                'guru',
                'kelas',
                'tahunAjaran'
            ])
            ->latest()
            ->get();

        return view(
            'superadmin.wali-kelas.index',
            compact('waliKelas')
        );
    }

    public function create()
    {
        $guru = Guru::with('pegawai')
            ->get()
            ->sortBy('pegawai.nama');

        $kelas = Kelas::orderBy('nama_kelas')->get();

        return view(
            'superadmin.wali-kelas.create',
            compact(
                'guru',
                'kelas'
            )
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'guru_id' => 'required',
            'kelas_id' => 'required',
        ]);

        /**
         * CEK KELAS
         */
        $kelasSudahAda = WaliKelas::where(
                'kelas_id',
                $request->kelas_id
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->exists();

        if ($kelasSudahAda) {

            return back()->with(
                'error',
                'Kelas sudah memiliki wali kelas'
            );

        }

        /**
         * CEK GURU
         */
        $guruSudahAda = WaliKelas::where(
                'guru_id',
                $request->guru_id
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->exists();

        if ($guruSudahAda) {

            return back()->with(
                'error',
                'Guru sudah menjadi wali kelas'
            );

        }

        WaliKelas::create([

            'guru_id' => $request->guru_id,

            'kelas_id' => $request->kelas_id,

            'tahun_ajaran_id' => current_tahun_id(),

        ]);

        return redirect()
            ->route('superadmin.wali-kelas.index')
            ->with(
                'success',
                'Wali kelas berhasil ditentukan'
            );
    }

    public function destroy(WaliKelas $waliKelas)
    {
        $waliKelas->delete();

        return back()->with(
            'success',
            'Wali kelas berhasil dihapus'
        );
    }
}