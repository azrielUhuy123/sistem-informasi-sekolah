<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\NomorSurat;

class NomorSuratSiswaController extends Controller
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
                auth()->id()
            )
            ->latest()
            ->get();

        return view(
            'siswa.nomor-surat.index',
            compact('nomorSurat')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | GENERATE
    |--------------------------------------------------------------------------
    */
    public function generate()
    {
        $last = NomorSurat::latest()->first();

        $urutan = 1;

        if ($last) {

            preg_match(
                '/421\.1\/(\d+)\//',
                $last->nomor_surat,
                $matches
            );

            if (isset($matches[1])) {

                $urutan = (int) $matches[1] + 1;

            }
        }

        $nomorUrut = str_pad(
            $urutan,
            3,
            '0',
            STR_PAD_LEFT
        );

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

        NomorSurat::create([

            'user_id' => auth()->id(),

            'pengirim' => auth()->user()->role,

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