@extends('layouts.admin')

@section('title', 'Tambah Tahun Ajaran')

@section('content')

<h1 class="mb-4">Tambah Tahun Ajaran</h1>

<div class="card">
    <div class="card-body">

        <form action="{{ route('admin.tahun-ajaran.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Tahun Ajaran</label>
                <input
                    type="text"
                    name="tahun"
                    class="form-control"
                    placeholder="Contoh: 2025/2026"
                    required
                >
            </div>

            <div class="mb-3">
                <label>Semester</label>
                <select name="semester" class="form-control">
                    <option value="">-- Pilih Semester --</option>
                    <option value="Ganjil">Ganjil</option>
                    <option value="Genap">Genap</option>
                </select>
            </div>

            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.tahun-ajaran.index') }}" class="btn btn-secondary ms-2">
                Kembali
            </a>

        </form>

    </div>
</div>

@endsection
