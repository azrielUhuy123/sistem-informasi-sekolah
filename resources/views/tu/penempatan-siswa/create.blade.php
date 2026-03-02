@extends('layouts.tu')

@section('title', 'Assign Siswa ke Kelas')

@section('content')
<div class="container-fluid">

    {{-- Header --}}
    <div class="mb-4">
        <h4 class="mb-0">Assign Siswa ke Kelas</h4>
        <small class="text-muted">
            Tahun Ajaran Aktif:
            <strong>{{ $tahunAktif->tahun ?? '-' }}</strong>
        </small>
    </div>

    {{-- Error --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('tu.penempatan-siswa.store') }}" method="POST">
                @csrf

                {{-- Siswa --}}
                <div class="mb-3">
                    <label class="form-label">Siswa</label>
                    <select name="siswa_id" class="form-select" required>
                        <option value="">-- Pilih Siswa --</option>
                        @foreach ($siswa as $s)
                            <option value="{{ $s->id }}">
                                {{ $s->nama }} ({{ $s->nis }})
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">
                        Hanya siswa yang belum ditempatkan di tahun ajaran ini
                    </small>
                </div>

                {{-- Kelas --}}
                <div class="mb-3">
                    <label class="form-label">Kelas</label>
                    <select name="kelas_id" class="form-select" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach ($kelas as $k)
                            <option value="{{ $k->id }}"
                                {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Action --}}
                <div class="d-flex justify-content-end">
                    <a href="{{ route('tu.siswa.index') }}" class="btn btn-secondary me-2">
                        Batal
                    </a>
                    <button class="btn btn-success">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
