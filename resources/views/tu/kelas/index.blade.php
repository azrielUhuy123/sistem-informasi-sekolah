@extends('layouts.tu')

@section('title', 'Manajemen Data Kelas')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Manajemen Data Kelas</h1>
    <a href="{{ route('tu.kelas.create') }}" class="btn btn-primary">+ Tambah Kelas</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card mt-3">
    <div class="card-body">

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
            <tr>
                <th>{!! sortLink('id', 'ID') !!}</th>
                <th>{!! sortLink('nama_kelas', 'Nama Kelas') !!}</th>
                <th>{!! sortLink('tingkat', 'Tingkat') !!}</th>
                <th>{!! sortLink('tahun_ajaran', 'Tahun Ajaran') !!}</th>
                <th style="width: 150px;">Aksi</th>
            </tr>
            </thead>

            <tbody>
            @foreach($kelas as $k)
                <tr>
                    <td>{{ $k->id }}</td>
                    <td>{{ $k->nama_kelas }}</td>
                    <td>{{ $k->tingkat }}</td>
                    <td>{{ $k->tahun_ajaran }}</td>
                    <td>
                        <a href="{{ route('tu.kelas.detail', $k->id) }}" class="btn btn-info btn-sm">Detail</a>

                        <a href="{{ route('tu.kelas.edit', $k->id) }}"
                           class="btn btn-warning btn-sm">Edit</a>

                        <form action="{{ route('tu.kelas.destroy', $k->id) }}"
                              method="POST"
                              class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus?')">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>

        </table>

    </div>
</div>

@endsection
