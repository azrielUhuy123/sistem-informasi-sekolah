<?php

namespace App\Http\Controllers\Guru\Ekskul;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\PembinaEkskul;
use App\Models\HariSekolah;
use App\Models\PengajuanJadwalEkskul;

class JadwalEkskulController extends Controller
{
    public function index()
    {
        $guru = auth()->user()->guru;

        $pembina = PembinaEkskul::with(
                'ekskul'
            )
            ->where(
                'guru_id',
                $guru->id
            )
            ->firstOrFail();

        $hari = HariSekolah::all();

        $jadwal = PengajuanJadwalEkskul::with(
                'hari'
            )
            ->where(
                'ekskul_id',
                $pembina->ekskul_id
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->orderBy('hari_id')
            ->orderBy('jam_mulai')
            ->get();

        return view(
            'guru.ekskul.jadwal.index',
            compact(
                'pembina',
                'hari',
                'jadwal'
            )
        );
    }

    public function store(Request $request)
    {
        $request->validate([

            'hari_id'     => 'required|exists:hari_sekolah,id',

            'jam_mulai'   => 'required',

            'jam_selesai' => 'required',

        ]);

        $guru = auth()->user()->guru;

        $pembina = PembinaEkskul::where(
                'guru_id',
                $guru->id
            )
            ->firstOrFail();

        PengajuanJadwalEkskul::create([

            'ekskul_id'       => $pembina->ekskul_id,

            'guru_id'         => $guru->id,

            'hari_id'         => $request->hari_id,

            'tahun_ajaran_id' => current_tahun_id(),

            'jam_mulai'       => $request->jam_mulai,

            'jam_selesai'     => $request->jam_selesai,

            'status'          => 'draft',

        ]);

        return back()->with(
            'success',
            'Jadwal berhasil ditambahkan'
        );
    }

    public function edit(
        PengajuanJadwalEkskul $jadwal
    )
    {
        $guru = auth()->user()->guru;

        $hari = HariSekolah::orderBy(
            'id'
        )->get();

        /*
        |--------------------------------------------------------------------------
        | CEK KEPEMILIKAN DATA
        |--------------------------------------------------------------------------
        */

        if (
            $jadwal->guru_id != $guru->id
        ) {
            abort(403);
        }

        return view(
            'guru.ekskul.jadwal.edit',
            compact(
                'jadwal',
                'hari'
            )
        );
    }

    public function update(
        Request $request,
        PengajuanJadwalEkskul $jadwal
    )
    {
        $request->validate([

            'hari_id'     => 'required|exists:hari_sekolah,id',

            'jam_mulai'   => 'required',

            'jam_selesai' => 'required',

        ]);

        $guru = auth()->user()->guru;

        /*
        |--------------------------------------------------------------------------
        | CEK KEPEMILIKAN DATA
        |--------------------------------------------------------------------------
        */

        if (
            $jadwal->guru_id != $guru->id
        ) {
            abort(403);
        }

        $jadwal->update([

            'hari_id'     => $request->hari_id,

            'jam_mulai'   => $request->jam_mulai,

            'jam_selesai' => $request->jam_selesai,

            /*
            |--------------------------------------------------------------------------
            | KIRIM ULANG KE KEPSEK
            |--------------------------------------------------------------------------
            */

            'status'      => 'submitted',

            'catatan'     => null,

        ]);

        return redirect()
            ->route(
                'guru.ekskul.validasi.index'
            )
            ->with(
                'success',
                'Jadwal berhasil diperbarui dan dikirim ulang untuk validasi.'
            );
    }
}