@extends('layouts.tu')

@section('title', 'Tahun Ajaran')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Manajemen Tahun Ajaran</h1>
    <a href="{{ route('tu.tahun.create') }}" class="btn btn-primary">+ Tambah Tahun Ajaran</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-body">

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Tahun</th>
                    <th>Semester</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach($tahun as $t)
                <tr>
                    <td>{{ $t->id }}</td>
                    <td>{{ $t->tahun }}</td>
                    <td>{{ $t->semester }}</td>
                    <td>
                        @if($t->aktif)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-secondary">Tidak aktif</span>
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('tu.tahun.edit', $t->id) }}" class="btn btn-warning btn-sm">Edit</a>

                        <form action="{{ route('tu.tahun.destroy', $t->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm"
                                onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>

    </div>
</div>

@endsection
