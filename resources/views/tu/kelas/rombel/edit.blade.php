@extends('layouts.app')

@section('title', 'Edit Rombel')

@section('content')
<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="mb-4">
                <h3 class="text-primary fw-bold text-center mb-1">Edit Rombel</h3>
                <p class="text-muted text-center mb-0">
                    Angkatan {{ $rombel->kelasGrup->angkatan->tahun }}
                    · Kelas {{ $rombel->kelasGrup->kelasMaster->tingkat }}
                </p>
            </div>

            <x-alert.flash key="error" type="danger"/>

            <form action="{{ route('tu.kelas.rombel.update', $rombel->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="form-label" for="nama_kelas">Nama Rombel</label>
                    <input
                        id="nama_kelas"
                        type="text"
                        name="nama_kelas"
                        class="form-control"
                        value="{{ old('nama_kelas', $rombel->nama_kelas_label) }}"
                        placeholder="{{ $rombel->kelasGrup->kelasMaster->tingkat }}-A"
                        required
                    >
                    @error('nama_kelas')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                    @error('kelas_grup_id')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('tu.kelas.rombel.index', $rombel->kelas_grup_id) }}" class="btn btn-outline-secondary">
                        Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection