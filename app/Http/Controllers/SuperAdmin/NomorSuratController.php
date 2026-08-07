<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\NomorSurat;
use illuminate\Support\Facades\Auth;

class NomorSuratController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $nomorSurat = NomorSurat::where(
                'user_id',
                Auth::id()
            )
            ->latest()
            ->get();

        return view(
            'superadmin.nomor-surat.index',
            compact('nomorSurat')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | GENERATE NOMOR
    |--------------------------------------------------------------------------
    */
    public function generate()
    {
        /*
        |--------------------------------------------------------------------------
        | AMBIL NOMOR TERAKHIR GLOBAL
        |--------------------------------------------------------------------------
        */
        $last = NomorSurat::latest()->first();

        $urutan = 1;

        if ($last) {

            preg_match('/421\.1\/(\d+)\//', $last->nomor_surat, $matches);

            if (isset($matches[1])) {

                $urutan = (int) $matches[1] + 1;

            }
        }

        /*
        |--------------------------------------------------------------------------
        | FORMAT NOMOR
        |--------------------------------------------------------------------------
        */
        $nomorUrut = str_pad($urutan, 3, '0', STR_PAD_LEFT);

        $bulanRomawi = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII'
        ];

        $bulan = $bulanRomawi[now()->month];

        $tahun = now()->year;

        $nomorSurat = "421.1/{$nomorUrut}/SDNMkj/{$bulan}/{$tahun}";

        /*
        |--------------------------------------------------------------------------
        | SIMPAN
        |--------------------------------------------------------------------------
        */
        NomorSurat::create([

            'user_id' => Auth::id(),

            'pengirim' => Auth::user()->role,

            'nomor_surat' => $nomorSurat,

            'status' => 'digunakan'

        ]);

        return redirect()
            ->back()
            ->with(
                'success',
                'Nomor surat berhasil dibuat'
            );
    }
}