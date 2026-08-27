@extends('layouts.app')

@section('title', 'Tambah Mata Pelajaran')

@section('content')

<div class="container-fluid py-4">

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="mb-3">
                <h3 class="text-primary fw-bold text-center mb-1">Tambah Mata Pelajaran</h3>
                <p class="text-muted text-center">Tambahkan Data Mata Pelajaran</p>
            </div>
            {{-- ALERT --}}            
 
            <x-alert.flash key="error" type="danger"/>
            <form action="{{ route('tu.mapel.store') }}" method="POST">
                
                @csrf

                <div class="row g-4 mb-5">
                    <div>
                        <label class="form-label">Kode Mapel</label>
                        <input type="text" name="kode_mapel" class="form-control"
                        placeholder="Contoh : MTK" 
                        required
                        oninput="this.value = this.value.toUpperCase().replace(/[0-9]/g, '')">
                    </div>
                    <div>
                        <label class="form-label">Nama Mapel</label>
                        <input type="text" name="nama_mapel" class="form-control" 
                        required
                        placeholder="Contoh : Matematika"
                        oninput="this.value = this.value.replace(/\s+/g, ' ').replace(/(^|\s)\S/g, c => c.toUpperCase())">
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="{{ route('tu.mapel.index') }}" class="btn btn-outline-secondary rounded-3">
                        <i class="bi bi-chevron-left me-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary rounded-3">
                        Simpan <i class="bi bi-save ms-1"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection