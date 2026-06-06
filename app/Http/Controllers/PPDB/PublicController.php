<?php

namespace App\Http\Controllers\PPDB;

use App\Http\Controllers\Controller;
use App\Models\PpdbPendaftar;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function informasi()
    {
        return view('ppdb.public.informasi');
    }

    public function alur()
    {
        return view('ppdb.public.alur');
    }

    public function create()
    {
        return view('ppdb.public.daftar');
    }

    public function store(Request $request)
    {
        $data = PpdbPendaftar::create([

            'no_pendaftaran'
                => $this->generateNoPendaftaran(),

            'tanggal_pendaftaran'
                => now(),

            'status'
                => 'pending',

            'nama'
                => $request->nama,

            'nik'
                => $request->nik,

            'nomor_kk'
                => $request->nomor_kk,

            'tahun_ajaran_id'
                => current_tahun_id()

        ]);

        return redirect()->route(
            'ppdb.bukti',
            $data->no_pendaftaran
        );
    }

    public function bukti($no)
    {
        $data = PpdbPendaftar::where(
            'no_pendaftaran',
            $no
        )->firstOrFail();

        return view(
            'ppdb.public.bukti',
            compact('data')
        );
    }
    public function statusForm()
    {
        return view(
            'ppdb.public.status'
        );
    }

    public function cekStatus(Request $request)
    {
        $request->validate([
            'keyword' => 'required'
        ]);

        $data = PpdbPendaftar::where(
                'no_pendaftaran',
                $request->keyword
            )
            ->orWhere(
                'nik',
                $request->keyword
            )
            ->first();

        return view(
            'ppdb.public.status',
            compact('data')
        );
    }

    private function generateNoPendaftaran()
    {
        $last = PpdbPendaftar::max('id') + 1;

        return 'PPDB-'
            . now()->year
            . '-'
            . str_pad(
                $last,
                5,
                '0',
                STR_PAD_LEFT
            );
    }
}