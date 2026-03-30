@extends('layouts.tu')

@section('content')

@include('components.crud.index', [
    'title' => 'Manajemen Data Guru',
    'rows' => $guru,
    'columns' => [
        ['key' => 'nama', 'label' => 'Nama Guru'],
        ['key' => 'nip', 'label' => 'NIP'],
        ['key' => 'mapel.nama_mapel', 'label' => 'Mata Pelajaran'],
    ],
    'actions' => [
        'detail' => 'tu.guru.detail',
        'edit' => 'tu.guru.edit',
    ],
    'routeCreate' => route('tu.guru.create'),
])

@endsection