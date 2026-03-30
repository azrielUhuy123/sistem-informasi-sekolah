@extends('layouts.tu')

@section('title', 'Jadwal')
@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Manajemen Data Jadwal</h4>
            <small class="text-muted">Kelola jadwal pelajaran</small>
        </div>

        <a href="{{ route('tu.jadwal.create') }}" class="btn btn-primary btn-sm">
            + Tambah
        </a>
    </div>

    @include('components.crud.index', [
        'title' => 'Daftar Jadwal',
        'rows' => $jadwal,
        'columns' => [
            ['key' => 'kelas.nama_kelas', 'label' => 'Kelas'],
            ['key' => 'guru.nama', 'label' => 'Guru'],
            ['key' => 'mapel.nama_mapel', 'label' => 'Mata Pelajaran'],
            ['key' => 'hari', 'label' => 'Hari'],
            ['key' => 'jam_mulai', 'label' => 'Jam Mulai'],
            ['key' => 'jam_selesai', 'label' => 'Jam Selesai'],
        ],
        'actions' => [
            'edit' => 'tu.jadwal.edit',
            'delete' => 'tu.jadwal.destroy',
        ],
        'routeCreate' => route('tu.jadwal.create'),
    ])

</div>

@endsection