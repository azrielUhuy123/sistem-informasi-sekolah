@extends('layouts.tu')

@section('title', 'Edit Guru')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Edit Guru</h1>
    <a href="{{ route('tu.guru.index') }}" class="btn btn-secondary">Kembali</a>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-body">

        <form action="{{ route('tu.guru.update', $guru->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">

                <div class="col-md-4 mb-3">
                    <label>NIP</label>
                    <input type="text" name="nip" class="form-control" value="{{ $guru->nip }}" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label>Nama Guru</label>
                    <input type="text" name="nama" class="form-control" value="{{ $guru->nama }}" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control" required>
                        <option value="L" {{ $guru->jenis_kelamin == 'L' ? 'selected':'' }}>Laki-laki</option>
                        <option value="P" {{ $guru->jenis_kelamin == 'P' ? 'selected':'' }}>Perempuan</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label>Mata Pelajaran</label>
                    <input type="text" name="mapel" class="form-control" value="{{ $guru->mapel }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label>Telepon</label>
                    <input type="text" name="telepon" class="form-control" value="{{ $guru->telepon }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $guru->email }}">
                </div>

                <div class="col-12 mb-3">
                    <label>Alamat</label>
                    <textarea name="alamat" class="form-control" rows="3">{{ $guru->alamat }}</textarea>
                </div>

            </div>

            <button class="btn btn-primary w-100 mt-3">Update</button>

        </form>

    </div>
</div>

@endsection
