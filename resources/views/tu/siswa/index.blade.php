@extends('layouts.tu')

@section('title', 'Manajemen Data Siswa')

@section('content')

{{-- ===== TABEL + HEADER + TOMBOL TAMBAH ===== --}}
@include('components.crud.index', [
    'title' => null,
    'rows' => $siswa,
    'columns' => [

        [
            'key' => 'nama',
            'label' => 'Nama Siswa',
            'sortable' => true,
        ],
        [
            'key' => 'nis',
            'label' => 'NIS',
            'sortable' => true,
        ],

        // KELAS AKTIF
        [
            'key' => '__kelas_aktif',
            'label' => 'Kelas Aktif',
            'value' => fn ($row) =>
                $row->penempatanAktif
                    ? '<span class="badge bg-primary">'
                        . $row->penempatanAktif->kelas->nama_kelas .
                      '</span>'
                    : '<span class="badge bg-secondary">Belum ditempatkan</span>',
            'raw' => true,
            'sortable' => false,
        ],

        // TAHUN AJARAN
        [
            'key' => '__tahun_ajaran',
            'label' => 'Tahun Ajaran',
            'value' => fn ($row) =>
                $row->penempatanAktif
                    ? $row->penempatanAktif->tahunAjaran->tahun
                    : '-',
            'sortable' => false,
        ],

        // AKSI KELAS
        [
            'key' => '__aksi_kelas',
            'label' => 'Aksi Kelas',
            'value' => fn ($row) =>
                !$row->penempatanAktif
                    ? '<a href="' . route('tu.penempatan-siswa.create', [
                            'siswa_id' => $row->id
                      ]) . '"
                        class="btn btn-sm btn-outline-primary">
                        Assign Kelas
                       </a>'
                    : '<span class="text-muted">—</span>',
            'raw' => true,
            'sortable' => false,
        ],

        // STATUS
        [
            'key' => '__status_siswa',
            'label' => 'Status',
            'value' => function ($row) {
                if (!$row->penempatanAktif) {
                    return '<span class="badge bg-warning text-dark">
                                Belum ditempatkan
                            </span>';
                }

                return match ($row->penempatanAktif->status) {
                    'aktif' => '<span class="badge bg-success">Aktif</span>',
                    'naik'  => '<span class="badge bg-info text-dark">Naik</span>',
                    'lulus' => '<span class="badge bg-secondary">Lulus</span>',
                    default => '<span class="badge bg-dark">-</span>',
                };
            },
            'raw' => true,
            'sortable' => false,
        ],
    ],

    'actions' => [
        'detail' => 'tu.siswa.detail',
        'edit' => 'tu.siswa.edit',
        'delete' => 'tu.siswa.destroy',
        'restore' => 'tu.siswa.restore',
        'forceDelete' => 'tu.siswa.forceDelete',
    ],

    'routeCreate' => route('tu.siswa.create'),
    'routeBulk' => 'tu.siswa.bulk',

    'extraActions' => view('tu.siswa.extra-actions')->render(),
])

@endsection
