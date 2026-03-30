@extends('layouts.tu')

@section('title', 'Mata Pelajaran')

@section('content')

<style>
/* 🔥 FIX TABLE NGACO */
.table-responsive {
    height: auto !important;
}

.table tbody {
    display: table-row-group !important;
}

.table tr {
    height: auto !important;
}

/* BONUS biar lebih compact */
.table td, .table th {
    vertical-align: middle !important;
}
</style>

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Manajemen Mata Pelajaran</h4>
            <small class="text-muted">
                Kelola data mata pelajaran
            </small>
        </div>

        <a href="{{ route('tu.mapel.create') }}" 
           class="btn btn-primary btn-sm shadow-sm">
            + Tambah Mapel
        </a>
    </div>

    {{-- CONTENT --}}
    @include('components.crud.index', [
        'title' => 'Daftar Mata Pelajaran',
        'rows' => $mata_pelajaran,

        'columns' => [
            [
                'key' => 'kode_mapel',
                'label' => 'Kode',
            ],
            [
                'key' => 'nama_mapel',
                'label' => 'Nama Mata Pelajaran',
            ],
            [
                'key' => 'kkm',
                'label' => 'KKM',
            ],
        ],

        'actions' => [
            'edit' => 'tu.mapel.edit',
            'delete' => 'tu.mapel.destroy',
        ],
    ])

</div>

@endsection