@extends('layouts.app')

@section('title', 'Tambah Angkatan')

@section('content')

<div class="card">
    <div class="card-body">

        <form action="{{ route('tu.angkatan.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Tahun Angkatan</label>
                <input type="number"
                       name="tahun"
                       class="form-control"
                       placeholder="Contoh: 2024"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">Keterangan (opsional)</label>
                <input type="text"
                       name="keterangan"
                       class="form-control"
                       placeholder="Angkatan 2024/2025">
            </div>

            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('tu.angkatan.index') }}" class="btn btn-secondary">
                Kembali
            </a>

        </form>

    </div>
</div>

@endsection
