<?php

namespace App\Http\Controllers\PPDB;

use App\Http\Controllers\Controller;
use App\Models\PpdbPendaftar;

class PendaftarController extends Controller
{
    public function index()
    {
        $data = PpdbPendaftar::latest()
            ->paginate(20);

        return view(
            'ppdb.pendaftar.index',
            compact('data')
        );
    }

    public function show(
        PpdbPendaftar $pendaftar
    )
    {
        return view(
            'ppdb.pendaftar.show',
            compact('pendaftar')
        );
    }

    public function verifikasi()
    {
        $data = PpdbPendaftar::where(
            'status',
            'pending'
        )->latest()->paginate(20);

        return view(
            'ppdb.pendaftar.verifikasi',
            compact('data')
        );
    }

    public function diterima()
    {
        $data = PpdbPendaftar::where(
            'status',
            'diterima'
        )->latest()->paginate(20);

        return view(
            'ppdb.pendaftar.diterima',
            compact('data')
        );
    }

    public function ditolak()
    {
        $data = PpdbPendaftar::where(
            'status',
            'ditolak'
        )->latest()->paginate(20);

        return view(
            'ppdb.pendaftar.ditolak',
            compact('data')
        );
    }

    public function terima(
        PpdbPendaftar $pendaftar
    )
    {
        $pendaftar->update([
            'status' => 'diterima'
        ]);

        return back()->with(
            'success',
            'Pendaftar diterima'
        );
    }

    public function tolak(
        PpdbPendaftar $pendaftar
    )
    {
        $pendaftar->update([
            'status' => 'ditolak'
        ]);

        return back()->with(
            'success',
            'Pendaftar ditolak'
        );
    }
}