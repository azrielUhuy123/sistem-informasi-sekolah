<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\PengampuMapel;
use Illuminate\Http\Request;

class PengampuMapelController extends Controller
{
    /**
     * LIST
     */
    public function index()
    {
        $guru = Guru::with('pegawai')
    ->get()
    ->sortBy('pegawai.nama');

        $kelas = Kelas::orderBy('nama_kelas')->get();

        $mapel = MataPelajaran::orderBy('nama_mapel')->get();

        $pengampu = PengampuMapel::with([
                'guru',
                'kelas',
                'mapel'
            ])
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->latest()
            ->get();

        return view(
            'superadmin.pengampu-mapel.index',
            compact(
                'guru',
                'kelas',
                'mapel',
                'pengampu'
            )
        );
    }

    /**
     * STORE
     */
    public function store(Request $request)
    {
        $request->validate([
            'guru_id' => 'required',
            'kelas_id' => 'required',
            'mata_pelajaran_id' => 'required',
        ]);

        /**
         * CEK DUPLIKAT
         */
        $sudahAda = PengampuMapel::where(
                'guru_id',
                $request->guru_id
            )
            ->where(
                'kelas_id',
                $request->kelas_id
            )
            ->where(
                'mata_pelajaran_id',
                $request->mata_pelajaran_id
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->exists();

        if ($sudahAda) {

            return back()->with(
                'error',
                'Pengampu mapel sudah ada.'
            );

        }

        /**
         * CREATE
         */
        PengampuMapel::create([
            'guru_id' => $request->guru_id,
            'kelas_id' => $request->kelas_id,
            'mata_pelajaran_id' => $request->mata_pelajaran_id,
            'tahun_ajaran_id' => current_tahun_id(),
        ]);

        return back()->with(
            'success',
            'Pengampu mapel berhasil ditambahkan.'
        );
    }

    /**
     * DELETE
     */
    public function destroy(PengampuMapel $pengampuMapel)
    {
        $pengampuMapel->delete();

        return back()->with(
            'success',
            'Pengampu mapel berhasil dihapus.'
        );
    }
}