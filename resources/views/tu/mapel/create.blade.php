@extends('layouts.tu')

@section('title', 'Tambah Mata Pelajaran')

@section('content')

<h1 class="mb-4">Tambah Mata Pelajaran</h1>

<div class="card">
    <div class="card-body">

        <form action="{{ route('tu.mapel.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Kode Mapel</label>
                <input type="text" name="kode" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Nama Mapel</label>
                <input type="text" name="nama_mapel" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>KKM</label>
                <input type="number" name="kkm" class="form-control" required value="70">
            </div>

            <button class="btn btn-primary">Simpan</button>
        </form>

    </div>
</div>

@endsection
