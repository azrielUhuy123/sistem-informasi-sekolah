<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanSurat;
use illuminate\Support\Facades\Auth;


class ValidasiSuratController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $pengajuan = PengajuanSurat::latest()->get();

        return view(
            'superadmin.pengajuan-surat.index',
            compact('pengajuan')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $pengajuan = PengajuanSurat::findOrFail($id);

        return view(
            'superadmin.pengajuan-surat.show',
            compact('pengajuan')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDASI
    |--------------------------------------------------------------------------
    */
    public function validasi($id)
    {
        $pengajuan = PengajuanSurat::findOrFail($id);

        // UPDATE DATABASE
        $pengajuan->update([

            'status' => 'disetujui',

            'validated_by' => Auth::id(),

            'validated_at' => now(),

        ]);

        return redirect()
            ->back()
            ->with(
                'success',
                'Surat berhasil divalidasi & ditandatangani'
            );
    }

        /*
        |--------------------------------------------------------------------------
    | TOLAK
    |--------------------------------------------------------------------------
    */
    public function tolak($id)
    {
        $pengajuan = PengajuanSurat::findOrFail($id);

        $pengajuan->update([

            'status' => 'ditolak'

        ]);

        return redirect()
            ->back()
            ->with(
                'success',
                'Pengajuan surat ditolak'
            );
    }
}