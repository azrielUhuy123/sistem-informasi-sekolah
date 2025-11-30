@extends('layouts.tu')

@section('title', 'Detail Kelas')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Detail Kelas</h1>
    <div>
        <a href="{{ route('tu.kelas.index') }}" class="btn btn-secondary">Kembali</a>
        <a href="{{ route('tu.kelas.edit', $kelas->id) }}" class="btn btn-warning">Edit</a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <h4 class="mb-3">Informasi Kelas</h4>

        <p><strong>Nama Kelas:</strong> {{ $kelas->nama_kelas }}</p>
        <p><strong>Tingkat:</strong> {{ $kelas->tingkat }}</p>
        <p><strong>Tahun Ajaran:</strong> {{ $kelas->tahun_ajaran }}</p>
    </div>
</div>

<div class="card">
    <div class="card-body">

        <h4 class="mb-3">Daftar Siswa</h4>

        @if($kelas->siswa->isEmpty())
            <p class="text-muted">Tidak ada siswa dalam kelas ini.</p>
        @else

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>NIS</th>
                    <th>Nama</th>
                    <th>Jenis Kelamin</th>
                    <th>Telepon Wali</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>

                @foreach($kelas->siswa as $s)
                <tr>
                    <td>{{ $s->nis }}</td>
                    <td>{{ $s->nama }}</td>
                    <td>{{ $s->jenis_kelamin }}</td>
                    <td>{{ $s->telepon_wali ?? '-' }}</td>
                    <td>
                        <a href="{{ route('tu.siswa.edit', $s->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>
        @endif

    </div>
</div>

@endsection
