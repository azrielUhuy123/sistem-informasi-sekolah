@extends('layouts.tu')

@section('title', 'Manajemen Tahun Ajaran')

@section('content')

@include('components.crud.index', [
    'title' => 'Manajemen Tahun Ajaran',
    'rows'  => $tahun,

    'columns' => [
        [
            'key'      => 'tahun',
            'label'    => 'Tahun Ajaran',
            'sortable' => true,
        ],
        [
            'key'      => 'semester',
            'label'    => 'Semester',
            'sortable' => true,
            'format'   => function ($row) {
                return $row->semester === 'Ganjil'
                    ? '<span class="badge bg-primary">Ganjil</span>'
                    : '<span class="badge bg-warning text-dark">Genap</span>';
            },
        ],
        [
            'key'      => 'aktif',
            'label'    => 'Status',
            'sortable' => true,
            'format'   => function ($row) {
                return $row->aktif
                    ? '<span class="badge bg-success">Aktif</span>'
                    : '<span class="badge bg-danger">Tidak Aktif</span>';
            },
        ],
    ],

    'actions' => [
        'detail'      => 'tu.tahun.detail',
        'edit'        => 'tu.tahun.edit',
        'delete'      => 'tu.tahun.destroy',
        'restore'     => 'tu.tahun.restore',
        'forceDelete' => 'tu.tahun.forceDelete',
    ],

    'routeCreate' => route('tu.tahun.create'),
    'routeBulk'   => 'tu.tahun.bulk',
])

@endsection
