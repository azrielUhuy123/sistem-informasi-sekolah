<?php

namespace App\Http\Controllers\PPDB;

use App\Http\Controllers\Controller;
use App\Models\PpdbPendaftar;
use App\Models\PpdbSetting;

class DashboardController extends Controller
{
    public function index()
    {
        $tahunAjaranId = current_tahun_id();
        $setting = PpdbSetting::where('tahun_ajaran_id', $tahunAjaranId)
            ->orderBy('id')
            ->first();

        $pendaftarTahunAjaran = PpdbPendaftar::where('tahun_ajaran_id', $tahunAjaranId);

        $total = (clone $pendaftarTahunAjaran)->count();

        $pending = (clone $pendaftarTahunAjaran)
            ->whereIn('status', ['pending', 'verifikasi'])
            ->count();

        $diterima = (clone $pendaftarTahunAjaran)
            ->where('status', 'diterima')
            ->count();

        $ditolak = (clone $pendaftarTahunAjaran)
            ->where('status', 'ditolak')
            ->count();

        $kuota = $setting?->kuota ?? 0;
        $sisaKuota = max(
            0,
            $kuota - $diterima
        );

        $terisi = $diterima;

        $persentase = $kuota > 0
            ? round(($terisi / $kuota) * 100)
            : 0;

        $statusPpdb = $setting?->status;
        return view(
            'ppdb.dashboard.index',
            compact(
                'total',
                'pending',
                'diterima',
                'ditolak',
                'kuota',
                'sisaKuota',
                'persentase',
                'statusPpdb'
            )
        );
    }
}