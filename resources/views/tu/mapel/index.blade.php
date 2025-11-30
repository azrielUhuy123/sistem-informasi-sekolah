@extends('layouts.tu')

@section('title', 'Manajemen Mata Pelajaran')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Manajemen Mata Pelajaran</h1>
    <a href="{{ route('tu.mapel.create') }}" class="btn btn-primary">+ Tambah Mapel</a>
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
                    <th>Nama Mapel</th>
                    <th>KKM</th>
                    <th width="150px">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach($mapel as $m)
                <tr>
                    <td>{{ $m->kode }}</td>
                    <td>{{ $m->nama_mapel }}</td>
                    <td>{{ $m->kkm }}</td>
                    <td>
                        <a href="{{ route('tu.mapel.edit', $m->id) }}" class="btn btn-warning btn-sm">Edit</a>

                        <form action="{{ route('tu.mapel.destroy', $m->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Hapus mapel ini?')" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>

    </div>
</div>

@endsection
