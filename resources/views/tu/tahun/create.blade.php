@extends('layouts.tu')

@section('title', 'Tambah Tahun Ajaran')

@section('content')

<h2 class="mb-4 fw-bold">Tambah Tahun Ajaran</h2>

<div class="card shadow-sm">
    <div class="card-body">

        <form action="{{ route('tu.tahun.store') }}" method="POST">
            @csrf

            {{-- Tahun Ajaran --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Tahun Ajaran</label>
                <input
                    type="text"
                    name="tahun"
                    class="form-control"
                    placeholder="2024/2025"
                    required
                >
            </div>

            {{-- Semester --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Semester</label>
                <select name="semester" class="form-select" required>
                    <option value="">— Pilih Semester —</option>
                    <option value="Ganjil">Ganjil</option>
                    <option value="Genap">Genap</option>
                </select>
            </div>

            {{-- Aktif --}}
            <div class="form-check mb-4">
                <input
                    type="checkbox"
                    name="aktif"
                    class="form-check-input"
                    id="aktifCheck"
                >
                <label class="form-check-label" for="aktifCheck">
                    Set sebagai tahun ajaran aktif
                </label>
            </div>

            {{-- Button --}}
            <div class="d-flex justify-content-between">
                <a href="{{ route('tu.tahun.index') }}" class="btn btn-secondary">
                    ← Kembali
                </a>

                <button type="submit" class="btn btn-primary">
                    Simpan
                </button>
            </div>

        </form>

    </div>
</div>

@endsection
