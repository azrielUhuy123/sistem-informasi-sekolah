@extends('layouts.app')

@section('title', 'Edit Tahun Ajaran')

@section('content')

<h1 class="mb-4">Edit Tahun Ajaran</h1>

<div class="card">
    <div class="card-body">

        <form action="{{ route('tu.tahun.update', $tahun->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Tahun Ajaran</label>
                <input type="text" 
                       name="tahun" 
                       class="form-control" 
                       value="{{ $tahun->tahun }}"
                       placeholder="2024/2025" 
                       required>
            </div>

            <div class="mb-3">
                <label>Semester</label>
                <select name="semester" class="form-control" required>
                    <option value="Ganjil" {{ $tahun->semester == 'Ganjil' ? 'selected' : '' }}>
                        Ganjil
                    </option>
                    <option value="Genap" {{ $tahun->semester == 'Genap' ? 'selected' : '' }}>
                        Genap
                    </option>
                </select>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" 
                       name="aktif" 
                       class="form-check-input" 
                       id="aktifCheck"
                       {{ $tahun->aktif ? 'checked' : '' }}>
                
                <label class="form-check-label" for="aktifCheck">
                    Set sebagai tahun ajaran aktif
                </label>
            </div>

            <button class="btn btn-primary">Update</button>

        </form>

    </div>
</div>

@endsection
