@extends('layouts.app')

@section('title', 'Edit Mata Pelajaran')

@section('content')

<h1 class="mb-4">Edit Mata Pelajaran</h1>

<div class="card">
    <div class="card-body">

        <form action="{{ route('tu.mapel.update', $mapel->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Kode Mapel</label>
                <input type="text" name="kode" class="form-control" value="{{ $mapel->kode }}" required>
            </div>

            <div class="mb-3">
                <label>Nama Mapel</label>
                <input type="text" name="nama_mapel" class="form-control" value="{{ $mapel->nama_mapel }}" required>
            </div>

            <button class="btn btn-primary">Update</button>
        </form>

    </div>
</div>

@endsection
