@extends('layouts.tu')

@section('title', 'Manajemen Ruangan')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Manajemen Ruangan</h1>
    <a href="{{ route('tu.ruangan.create') }}" class="btn btn-primary">+ Tambah Ruangan</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-body">

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Kode</th>
                    <th>Nama Ruangan</th>
                    <th>Kapasitas</th>
                    <th>Jenis</th>
                    <th style="width: 150px;">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach($ruangan as $r)
                <tr>
                    <td>{{ $r->kode }}</td>
                    <td>{{ $r->nama_ruangan }}</td>
                    <td>{{ $r->kapasitas ?? '-' }}</td>
                    <td>{{ $r->jenis_ruangan ?? '-' }}</td>

                    <td>
                        <a href="{{ route('tu.ruangan.edit', $r->id) }}"
                           class="btn btn-warning btn-sm">Edit</a>

                        <form action="{{ route('tu.ruangan.destroy', $r->id) }}"
                              method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Hapus ruangan ini?')">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>

        <div class="mt-3">
            {{ $ruangan->links() }}
        </div>

    </div>
</div>

@endsection
