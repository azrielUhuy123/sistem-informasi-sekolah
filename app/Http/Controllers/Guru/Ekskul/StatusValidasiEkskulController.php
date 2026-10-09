<?php

namespace App\Http\Controllers\Guru\Ekskul;

use App\Http\Controllers\Controller;
use App\Models\PembinaEkskul;
use App\Models\PengajuanJadwalEkskul;
use Illuminate\Support\Facades\Auth;

class StatusValidasiEkskulController extends Controller
{
public function index()
{
    $guru = Auth::user()->guru;

    $ekskul = PembinaEkskul::where(
            'guru_id',
            $guru->id
        )
        ->first();

    $jadwal = PengajuanJadwalEkskul::with(
            'hari'
        )
        ->where(
            'ekskul_id',
            $ekskul->ekskul_id
        )
        ->where(
            'tahun_ajaran_id',
            current_tahun_id()
        )
        ->orderBy('hari_id')
        ->get();

    return view(
        'guru.ekskul.validasi.index',
        compact(
            'jadwal'
        )
    );
}
}