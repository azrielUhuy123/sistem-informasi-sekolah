<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Ekskul;
use App\Models\EkskulSiswa;
use App\Models\PembinaEkskul;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EkskulAnggotaController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | GURU LOGIN
        |--------------------------------------------------------------------------
        */

        $guru = Auth::user()->guru;

        /*
        |--------------------------------------------------------------------------
        | EKSKUL YANG DIAMPU
        |--------------------------------------------------------------------------
        */

        $pembina = PembinaEkskul::with('ekskul')
            ->where(
                'guru_id',
                $guru->id
            )
            ->first();

        abort_if(!$pembina, 403);

        $ekskul = $pembina->ekskul;

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        $search = $request->search;

        /*
        |--------------------------------------------------------------------------
        | SISWA
        |--------------------------------------------------------------------------
        */

        $siswa = Siswa::query()

            ->when($search, function ($q) use ($search) {

                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%");

            })

            ->orderBy('nama')

            ->paginate(10)
            ->appends($request->query());

        /*
        |--------------------------------------------------------------------------
        | ANGGOTA SUDAH ADA
        |--------------------------------------------------------------------------
        */

        $anggotaIds = EkskulSiswa::where(
                'ekskul_id',
                $ekskul->id
            )
            ->pluck('siswa_id')
            ->toArray();

        return view(
            'guru.ekskul.anggota.index',
            compact(
                'ekskul',
                'siswa',
                'anggotaIds',
                'search'
            )
        );
    }

    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'siswa_id' => ['required', 'array', 'min:1'],
            'siswa_id.*' => ['integer', 'distinct', 'exists:siswa,id'],
        ]);

        $siswaIds = collect($request->siswa_id)
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        if (empty($siswaIds)) {
            return back()->with('error', 'Pilih minimal satu siswa.');
        }

        /*
        |--------------------------------------------------------------------------
        | GURU LOGIN
        |--------------------------------------------------------------------------
        */

        $guru = Auth::user()->guru;

        /*
        |--------------------------------------------------------------------------
        | PEMBINA
        |--------------------------------------------------------------------------
        */

        $pembina = PembinaEkskul::where(
            'guru_id',
            $guru->id
        )->first();

        abort_if(!$pembina, 403);

        $ekskul = Ekskul::findOrFail(
            $pembina->ekskul_id
        );

        $jumlahSekarang = EkskulSiswa::where(
            'ekskul_id',
            $ekskul->id
        )->count();

        $siswaYangSudahJoin = EkskulSiswa::where(
                'ekskul_id',
                $ekskul->id
            )
            ->whereIn('siswa_id', $siswaIds)
            ->pluck('siswa_id')
            ->toArray();

        $siswaBaru = array_values(array_diff($siswaIds, $siswaYangSudahJoin));

        if (empty($siswaBaru)) {
            return back()->with('error', 'Siswa yang dipilih sudah menjadi anggota ekskul ini.');
        }

        $tersedia = max($ekskul->kuota - $jumlahSekarang, 0);

        if (count($siswaBaru) > $tersedia) {
            return back()->with(
                'error',
                'Kuota ekskul tidak mencukupi'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use ($ekskul, $pembina, $siswaBaru) {
            foreach ($siswaBaru as $id) {
                EkskulSiswa::firstOrCreate(
                    [
                        'ekskul_id'       => $ekskul->id,
                        'siswa_id'        => $id,
                        'tahun_ajaran_id' => $pembina->tahun_ajaran_id,
                    ],
                    [
                        'status' => 'aktif'
                    ]
                );
            }
        });

        return back()->with(
            'success',
            'Anggota berhasil ditambahkan'
        );
    }
    public function daftar(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | GURU LOGIN
        |--------------------------------------------------------------------------
        */

        $guru = Auth::user()->guru;

        /*
        |--------------------------------------------------------------------------
        | PEMBINA
        |--------------------------------------------------------------------------
        */

        $pembina = PembinaEkskul::with('ekskul')
            ->where(
                'guru_id',
                $guru->id
            )
            ->first();

        abort_if(!$pembina, 403);

        $ekskul = $pembina->ekskul;

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        $search = $request->search;

        /*
        |--------------------------------------------------------------------------
        | ANGGOTA
        |--------------------------------------------------------------------------
        */

        $anggota = EkskulSiswa::with('siswa')

            ->where(
                'ekskul_id',
                $ekskul->id
            )

            ->whereHas(
                'siswa',
                function ($query) {
                    $query->whereNull('deleted_at');
                }
            )

            ->when($search, function ($q) use ($search) {

                $q->whereHas(
                    'siswa',
                    function ($siswa) use ($search) {

                        $siswa->where(
                            'nama',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'nis',
                            'like',
                            "%{$search}%"
                        );

                    }
                );

            })

            ->latest()

            ->paginate(15);

        return view(
            'guru.ekskul.anggota.daftar',
            compact(
                'ekskul',
                'anggota',
                'search'
            )
        );
    }
    public function destroy($id)
    {
        $guru = Auth::user()->guru;

        abort_if(!$guru, 403);

        $ekskulIds = PembinaEkskul::where(
                'guru_id',
                $guru->id
            )
            ->pluck('ekskul_id')
            ->toArray();

        if (empty($ekskulIds)) {
            abort(403);
        }

        $anggota = EkskulSiswa::findOrFail($id);

        if (!in_array((int) $anggota->ekskul_id, array_map('intval', $ekskulIds), true)) {
            abort(403);
        }

        $anggota->delete();

        return back()->with(
            'success',
            'Anggota berhasil dikeluarkan'
        );
    }
}