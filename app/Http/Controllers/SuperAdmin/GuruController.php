<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        $gurus = Guru::with([
            'pegawai',
            'waliKelas.kelas',
            'pengampuMapel.mapel',
            'pengampuMapel.kelas',
        ])
            ->latest()
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Hitung atribut yang digunakan tabel dan filter
        |--------------------------------------------------------------------------
        */

        $gurus = $gurus->map(function ($guru) {
            $isWaliKelas = (bool) $guru->waliKelas;

            // Jumlah mapel mengikuti perhitungan tampilan sebelumnya.
            $jumlahMapel = $isWaliKelas
                ? 4
                : $guru->pengampuMapel
                    ->filter(fn ($pengampu) => $pengampu->mapel)
                    ->unique('mapel_id')
                    ->count();

            // Gabungkan kelas wali dan kelas pengampu tanpa duplikasi.
            $kelasWali = $guru->waliKelas?->kelas;

            $kelasMengajar = $guru->pengampuMapel
                ->pluck('kelas')
                ->filter()
                ->unique('id');

            $semuaKelas = collect();

            if ($kelasWali) {
                $semuaKelas->push($kelasWali);
            }

            foreach ($kelasMengajar as $kelas) {
                if (!$kelasWali || $kelas->id != $kelasWali->id) {
                    $semuaKelas->push($kelas);
                }
            }

            $jumlahKelas = $semuaKelas->count();

            $guru->filter_status = $isWaliKelas
                ? 'Wali Kelas'
                : 'Guru Bidang';

            $guru->filter_mapel = match (true) {
                $jumlahMapel === 0 => '0 Mapel',
                $jumlahMapel === 1 => '1 Mapel',
                $jumlahMapel === 2 => '2 Mapel',
                $jumlahMapel === 3 => '3 Mapel',
                default => '4+ Mapel',
            };

            $guru->filter_kelas = match (true) {
                $jumlahKelas === 0 => '0 Kelas',
                $jumlahKelas <= 4 => '1–4 Kelas',
                $jumlahKelas <= 7 => '5–7 Kelas',
                $jumlahKelas <= 14 => '8–14 Kelas',
                default => '15+ Kelas',
            };

            $guru->jumlah_mapel = $jumlahMapel;
            $guru->jumlah_kelas = $jumlahKelas;

            return $guru;
        });

        /*
        |--------------------------------------------------------------------------
        | Pencarian nama dan NIP
        |--------------------------------------------------------------------------
        */

        if ($request->filled('q')) {
            $keyword = mb_strtolower(trim($request->input('q')));

            $gurus = $gurus->filter(function ($guru) use ($keyword) {
                $nama = mb_strtolower($guru->pegawai?->nama ?? '');
                $nip = mb_strtolower($guru->pegawai?->nip ?? '');

                return str_contains($nama, $keyword)
                    || str_contains($nip, $keyword);
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Filter status akademik
        |--------------------------------------------------------------------------
        */

        $statusFilter = array_intersect(
            (array) $request->input('status', []),
            ['Wali Kelas', 'Guru Bidang']
        );

        if (!empty($statusFilter)) {
            $gurus = $gurus->filter(
                fn ($guru) => in_array(
                    $guru->filter_status,
                    $statusFilter,
                    true
                )
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Filter jumlah mata pelajaran
        |--------------------------------------------------------------------------
        */

        $mapelFilter = array_intersect(
            (array) $request->input('mapel_count', []),
            ['0 Mapel', '1 Mapel', '2 Mapel', '3 Mapel', '4+ Mapel']
        );

        if (!empty($mapelFilter)) {
            $gurus = $gurus->filter(
                fn ($guru) => in_array(
                    $guru->filter_mapel,
                    $mapelFilter,
                    true
                )
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Filter total kelas
        |--------------------------------------------------------------------------
        */

        $kelasFilter = array_intersect(
            (array) $request->input('class_count', []),
            ['0 Kelas', '1–4 Kelas', '5–7 Kelas', '8–14 Kelas', '15+ Kelas']
        );

        if (!empty($kelasFilter)) {
            $gurus = $gurus->filter(
                fn ($guru) => in_array(
                    $guru->filter_kelas,
                    $kelasFilter,
                    true
                )
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $gurus = $gurus->values();

        $gurus = new LengthAwarePaginator(
            $gurus->forPage($currentPage, $perPage)->values(),
            $gurus->count(),
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        return view(
            'superadmin.guru.index',
            compact('gurus')
        );
    }

    public function show(Guru $guru)
    {
        $guru->load([
            'pegawai',
            'user',
            'waliKelas.kelas',
            'pengampuMapel.mapel',
        ]);

        $pegawai = $guru;

        return view(
            'superadmin.guru.show',
            compact('pegawai')
        );
    }
}