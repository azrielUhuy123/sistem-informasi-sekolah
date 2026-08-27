@extends('layouts.app')

@section('title', 'Tambah Rombel')

@section('content')

<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm">
        <div class="card-body">            
            <div class="mb-4">
                <h3 class="text-primary fw-bold text-center mb-1">
                    Tambah Rombel Kelas {{ $kelasGrup->kelasMaster->tingkat }}
                </h3>
                <p class="text-muted text-center">Angkatan {{ $kelasGrup->angkatan->tahun }}</p>
            </div>

            {{-- ALERT --}}            
 
            <x-alert.flash key="error" type="danger"/>
            
            <form action="{{ route('tu.kelas.rombel.store', $kelasGrup->id) }}" method="POST">
                
                @csrf

                <div class="row g-4 mb-5">
                    <div>
                        <label class="form-label">Kode Kelas</label>
                        <input type="text"
                            name="kode"
                            class="form-control"
                            placeholder="A / B / C"
                            maxlength="1"
                            required
                            oninput="this.value = this.value.toUpperCase().replace(/[^A-Z]/g, '').slice(0, 1)">
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="{{ route('tu.kelas.rombel.index', $kelasGrup->id) }}" class="btn btn-outline-secondary rounded-3">
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

