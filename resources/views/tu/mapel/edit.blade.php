@extends('layouts.app')

@section('title', 'Edit Mata Pelajaran')

@section('content')

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Edit Mata Pelajaran</h4>
            <p class="text-muted mb-0">Perbarui Data Mata Pelajaran</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('tu.mapel.update', $mapel->id) }}" method="POST">
                
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div>
                        <label class="form-label">Kode Mapel</label>
                        <input type="text" name="kode_mapel" class="form-control rounded-3" value="{{ $mapel->kode_mapel }}" required>
                    </div>
                    <div>
                        <label class="form-label">Nama Mapel</label>
                        <input type="text" name="nama_mapel" class="form-control rounded-3" value="{{ $mapel->nama_mapel }}" required>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="{{ route('tu.mapel.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save me-1"></i> Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
