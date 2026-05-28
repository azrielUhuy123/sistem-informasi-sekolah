<?php

namespace App\Http\Controllers\Guru\Ekskul;

use App\Http\Controllers\Controller;
use App\Models\Ekskul;
use App\Models\EkskulSiswa;
use App\Models\AbsensiEkskul;
use App\Models\PembinaEkskul;
use App\Models\JurnalEkskul;

use Illuminate\Http\Request;

class JurnalEkskulController extends Controller
{
    public function index()
    {
        $guru = auth()->user()->guru;

        $pembina = PembinaEkskul::with('ekskul')
            ->where(
                'guru_id',
                $guru->id
            )
            ->firstOrFail();

        $ekskul = $pembina->ekskul;

        $jurnal = JurnalEkskul::where(
                'ekskul_id',
                $ekskul->id
            )
            ->latest('tanggal')
            ->paginate(10);

        return view(
            'guru.ekskul.jurnal.index',
            compact(
                'ekskul',
                'jurnal'
            )
        );
    }

    public function store(Request $request)
    {
        $request->validate([

            'tanggal' => 'required',

            'materi' => 'required',

            'kegiatan' => 'required',

        ]);

        $guru = auth()->user()->guru;

        $pembina = PembinaEkskul::where(
            'guru_id',
            $guru->id
        )->firstOrFail();

        JurnalEkskul::create([

            'ekskul_id' =>
                $pembina->ekskul_id,

            'tanggal' =>
                $request->tanggal,

            'materi' =>
                $request->materi,

            'kegiatan' =>
                $request->kegiatan,

            'catatan' =>
                $request->catatan,

            'created_by' =>
                auth()->id()

        ]);

        return back()->with(
            'success',
            'Jurnal berhasil disimpan'
        );
    }
    public function destroy($id)
    {
        JurnalEkskul::findOrFail($id)
            ->delete();

        return back()->with(
            'success',
            'Jurnal berhasil dihapus'
        );
    }
}
