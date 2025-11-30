@extends('layouts.tu')

@section('title', 'Tambah Tahun Ajaran')

@section('content')

<h1 class="mb-4">Tambah Tahun Ajaran</h1>

<div class="card">
    <div class="card-body">

        <form action="{{ route('tu.tahun.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Tahun Ajaran</label>
                <input type="text" name="tahun" class="form-control" placeholder="2024/2025" required>
            </div>

            <div class="mb-3">
                <label>Semester</label>
                <select name="semester" class="form-control" required>
                    <option value="Ganjil">Ganjil</option>
                    <option value="Genap">Genap</option>
                </select>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" name="aktif" class="form-check-input" id="aktifCheck">
                <label for="aktifCheck" class="form-check-label">Set sebagai tahun ajaran aktif</label>
            </div>

            <button class="btn btn-primary">Simpan</button>
        </form>

    </div>
</div>

@endsection
