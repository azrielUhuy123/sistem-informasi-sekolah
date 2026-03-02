@extends('layouts.tu')

@section('title', 'Tambah Grup Kelas')

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="fw-bold mb-3">Tambah Grup Kelas</h5>

        <form method="POST" action="{{ route('tu.kelas.grup.store') }}">
    @csrf
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    {{-- TINGKAT --}}
    <div class="mb-3">
        <label class="form-label">Tingkat</label>
        <select name="kelas_master_id" class="form-select" required>
            <option value="">-- Pilih Tingkat --</option>
            @foreach ($kelasMaster as $km)
                <option value="{{ $km->id }}">
                    Kelas {{ $km->tingkat }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- ANGKATAN --}}
    <div class="mb-3">
        <label class="form-label">Angkatan</label>
        <select name="angkatan_id" class="form-select" required>
            <option value="">-- Pilih Angkatan --</option>
            @foreach ($angkatan as $a)
                <option value="{{ $a->id }}">
                    {{ $a->tahun }} ({{ $a->keterangan }})
                </option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="btn btn-primary">
        Simpan
    </button>
    <a href="{{ route('tu.kelas.index') }}" class="btn btn-secondary">Batal</a>
</form>

    </div>
</div>
@endsection
