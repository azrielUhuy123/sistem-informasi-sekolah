@extends('layouts.tu')

@section('title', 'Detail Jadwal')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Detail Jadwal</h1>
    <a href="{{ route('tu.jadwal.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="card">
    <div class="card-body">

        <table class="table">
            <tr>
                <th>Kelas</th>
                <td>{{ $jadwal->kelas->nama_kelas }}</td>
            </tr>

            <tr>
                <th>Mata Pelajaran</th>
                <td>{{ $jadwal->mapel }}</td>
            </tr>

            <tr>
                <th>Guru</th>
                <td>{{ $jadwal->guru->nama }}</td>
            </tr>

            <tr>
                <th>Hari</th>
                <td>{{ $jadwal->hari }}</td>
            </tr>

            <tr>
                <th>Jam</th>
                <td>{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</td>
            </tr>
        </table>

    </div>
</div>

@endsection
