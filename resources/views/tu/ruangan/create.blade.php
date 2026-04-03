@extends('layouts.tu')

@section('title', 'Tambah Ruangan')

@section('content')

<h1 class="mb-4">Tambah Ruangan</h1>

<div class="card">
    <div class="card-body">

        <form action="{{ route('tu.ruangan.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label>Kode Ruangan</label>
        <input type="text" name="kode_ruangan" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Nama Ruangan</label>
        <input type="text" name="nama_ruangan" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Kapasitas</label>
        <input type="number" name="kapasitas" class="form-control">
    </div>

    <div class="mb-3">
        <label>Jenis Ruangan</label>
        <select name="jenis_ruangan" class="form-control">
            <option value="">-- Pilih Jenis --</option>
            <option value="Kelas">Kelas</option>
            <option value="Lab">Lab</option>
            <option value="Kantor">Kantor</option>
        </select>
    </div>

    <button class="btn btn-primary">Simpan</button>
</form>

    </div>
</div>

@endsection
