@extends('layouts.tu')

@section('title', 'Detail Siswa')

@section('content')

<h2>Detail Siswa</h2>
<hr>

<div class="card p-4">

    <div class="row">
        <div class="col-md-3">
            <img src="{{ $siswa->foto ? asset('storage/foto_siswa/'.$siswa->foto) : 'https://via.placeholder.com/150' }}"
                 class="img-thumbnail">
        </div>

        <div class="col-md-9">

            <table class="table">
                <tr><th>NIS</th><td>{{ $siswa->nis }}</td></tr>
                <tr><th>NISN</th><td>{{ $siswa->nisn }}</td></tr>
                <tr><th>Nama</th><td>{{ $siswa->nama }}</td></tr>
                <tr><th>Kelas</th><td>{{ $siswa->kelas->nama_kelas }}</td></tr>
                <tr><th>Jenis Kelamin</th><td>{{ $siswa->jenis_kelamin }}</td></tr>
                <tr><th>Tanggal Lahir</th><td>{{ $siswa->tanggal_lahir }}</td></tr>
                <tr><th>Alamat</th><td>{{ $siswa->alamat }}</td></tr>
                <tr><th>Nama Wali</th><td>{{ $siswa->nama_wali }}</td></tr>
                <tr><th>Telepon Wali</th><td>{{ $siswa->telepon_wali }}</td></tr>
            </table>

            <a href="{{ route('tu.siswa.edit', $siswa->id) }}" class="btn btn-warning">Edit</a>
        </div>
    </div>

</div>

@endsection
