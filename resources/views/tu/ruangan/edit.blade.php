@extends('layouts.tu')

@section('title', 'Edit Ruangan')

@section('content')

<h1 class="mb-4">Edit Ruangan</h1>

<div class="card">
    <div class="card-body">

        <form action="{{ route('tu.ruangan.update', $ruangan->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Kode Ruangan</label>
                <input type="text" name="kode" class="form-control"
                       value="{{ $ruangan->kode }}" required>
            </div>

            <div class="mb-3">
                <label>Nama Ruangan</label>
                <input type="text" name="nama_ruangan" class="form-control"
                       value="{{ $ruangan->nama_ruangan }}" required>
            </div>

            <div class="mb-3">
                <label>Kapasitas</label>
                <input type="number" name="kapasitas" class="form-control"
                       value="{{ $ruangan->kapasitas }}">
            </div>

            <div class="mb-3">
                <label>Jenis Ruangan</label>
                <select name="jenis" class="form-control">
                    <option value="">-- Pilih Jenis --</option>
                    <option value="Kelas" {{ $ruangan->jenis == 'Kelas' ? 'selected':'' }}>Kelas</option>
                    <option value="Lab" {{ $ruangan->jenis == 'Lab' ? 'selected':'' }}>Lab</option>
                    <option value="Kantor" {{ $ruangan->jenis == 'Kantor' ? 'selected':'' }}>Kantor</option>
                </select>
            </div>

            <button class="btn btn-primary">Update</button>

        </form>

    </div>
</div>

@endsection
