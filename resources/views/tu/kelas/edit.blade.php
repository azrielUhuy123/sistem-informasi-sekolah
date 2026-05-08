@extends('layouts.app')

@section('title', 'Edit Kelas')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Edit Kelas</h1>
    <a href="{{ route('tu.kelas.index') }}" class="btn btn-secondary">Kembali</a>
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

        <form action="{{ route('tu.kelas.update', $kelas->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Nama Kelas</label>
                <input type="text" 
                       name="nama_kelas" 
                       class="form-control" 
                       value="{{ $kelas->nama_kelas }}" 
                       required>
            </div>

            <div class="mb-3">
                <label>Tingkat</label>
                <input type="number" 
                       name="tingkat" 
                       class="form-control" 
                       value="{{ $kelas->tingkat }}" 
                       required>
            </div>

            <div class="mb-3">
                <label>Tahun Ajaran</label>
                <input type="text" 
                       name="tahun_ajaran" 
                       class="form-control" 
                       value="{{ $kelas->tahun_ajaran }}" 
                       required>
            </div>

            <button class="btn btn-primary w-100 mt-2">Update</button>
        </form>

    </div>
</div>

@endsection
