@extends('layouts.tu')

@section('title', 'Manajemen Data Siswa')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Manajemen Data Siswa</h1>
    <a href="{{ route('tu.siswa.create') }}" class="btn btn-primary">+ Tambah Siswa</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<form action="{{ route('tu.siswa.import') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="input-group mb-3" style="max-width: 400px;">
        <input type="file" name="file" class="form-control" required>
        <button class="btn btn-success">Import Excel</button>
    </div>
</form>

<a href="{{ route('tu.siswa.export.excel') }}" class="btn btn-success btn-sm">Export Excel</a>
<a href="{{ route('tu.siswa.export.pdf') }}" class="btn btn-danger btn-sm">Export PDF</a>

<div class="card mt-3">
    <div class="card-body">

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
            <tr>
                <th>{!! sortLink('nis', 'NIS') !!}</th>
                <th>{!! sortLink('nisn', 'NISN') !!}</th>
                <th>{!! sortLink('nama', 'Nama') !!}</th>
                <th>{!! sortLink('kelas_id', 'Kelas') !!}</th>
                <th>{!! sortLink('jenis_kelamin', 'Jenis Kelamin') !!}</th>
                <th>{!! sortLink('tanggal_lahir', 'Tanggal Lahir') !!}</th>
                <th>Alamat</th>
                <th>Nama Wali</th>
                <th>Telepon Wali</th>
                <th>Aksi</th>
            </tr>
            </thead>

            <tbody>
            @foreach($siswa as $s)
                <tr>
                    <td>{{ $s->nis }}</td>
                    <td>{{ $s->nisn ?? '-' }}</td>
                    <td>{{ $s->nama }}</td>
                    <td>{{ $s->kelas->nama_kelas ?? '-' }}</td>
                    <td>{{ $s->jenis_kelamin }}</td>
                    <td>{{ $s->tanggal_lahir ?? '-' }}</td>
                    <td>{{ $s->alamat ?? '-' }}</td>
                    <td>{{ $s->nama_wali ?? '-' }}</td>
                    <td>{{ $s->telepon_wali ?? '-' }}</td>

                    <td>
                        <a href="{{ route('tu.siswa.edit', $s->id) }}"
                           class="btn btn-warning btn-sm">Edit</a>

                        <form action="{{ route('tu.siswa.destroy', $s->id) }}"
                              method="POST"
                              class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus siswa ini?')">
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
