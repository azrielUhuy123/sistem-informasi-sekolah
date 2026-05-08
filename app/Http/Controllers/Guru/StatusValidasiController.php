<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\PengajuanJadwal;
use Illuminate\Support\Facades\Auth;

class StatusValidasiController extends Controller
{
    public function index()
    {
        $guruId = Auth::user()->guru->id;

        $jadwals = PengajuanJadwal::with([
            'kelas',
            'mapel',
            'hari',
            'jamPelajaran'
        ])
        ->where('guru_id', $guruId)
        ->where('tahun_ajaran_id', current_tahun_id())
        ->whereIn('status', ['submitted', 'approved', 'rejected'])
        ->latest('updated_at')
        ->get();

        return view(
            'guru.status-validasi.index',
            compact('jadwals')
        );
    }
}