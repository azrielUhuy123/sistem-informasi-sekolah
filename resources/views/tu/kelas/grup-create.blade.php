@extends('layouts.tu')

@section('title', 'Tambah Grup Kelas')

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="fw-bold mb-3">Tambah Grup Kelas</h5>

        <form method="POST" action="{{ route('tu.kelas.grup.store') }}">
    @csrf

    {{-- TINGKAT --}}
    <div class="mb-3">
        <label class="form-label">Tingkat</label>
        <select name="tingkat" class="form-select" required>
            <option value="">-- Pilih Tingkat --</option>
            @for ($i = 1; $i <= 6; $i++)
                <option value="{{ $i }}">{{ $i }}</option>
            @endfor
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

    <button class="btn btn-primary">Simpan</button>
    <a href="{{ route('tu.kelas.index') }}" class="btn btn-secondary">Batal</a>
</form>

    </div>
</div>
@endsection
