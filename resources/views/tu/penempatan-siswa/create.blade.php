@extends('layouts.app')

@section('title', 'Assign Siswa ke Kelas')

@section('content')
<div class="container-fluid">

    {{-- 🔒 WARNING STATUS --}}
    @if($tahunAktif->status !== 'aktif')
    <div class="alert alert-warning">
        Assign siswa hanya bisa dilakukan saat <b>tahun aktif</b>.
    </div>
    @endif

    {{-- 🔷 HEADER --}}
    <div class="mb-4">
        <h4 class="mb-1">Assign Siswa ke Kelas</h4>

        <small class="text-muted">
            Tahun Ajaran:
            <strong>{{ $tahunAktif->tahun ?? '-' }}</strong>

            @if($tahunAktif)
                {!! match($tahunAktif->status) {
                    'aktif'    => '<span class="badge bg-success ms-2">AKTIF</span>',
                    'transisi' => '<span class="badge bg-warning text-dark ms-2">TRANSISI</span>',
                    'selesai'  => '<span class="badge bg-secondary ms-2">SELESAI</span>',
                } !!}
            @endif
        </small>
    </div>

    {{-- 🔴 ERROR --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- 🟡 EMPTY STATE --}}
    @if($siswa->isEmpty())
        <div class="alert alert-info">
            Semua siswa sudah memiliki kelas di tahun ajaran ini.
        </div>
    @endif

    {{-- 🔷 FORM --}}
    <div class="card shadow-sm">
        <div class="card-body">

            <form 
                action="{{ route('tu.penempatan-siswa.store') }}" 
                method="POST"
                {{ $tahunAktif->status !== 'aktif' ? 'onsubmit=return false;' : '' }}
            >
                @csrf

                {{-- Siswa --}}
                <div class="mb-3">
                    <label class="form-label">Siswa</label>
                    <select 
                        name="siswa_id" 
                        class="form-select rounded-3"
                        required
                        {{ $tahunAktif->status !== 'aktif' ? 'disabled' : '' }}
                    >
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
                    <select 
                        name="kelas_id" 
                        class="form-select rounded-3"
                        required
                        {{ $tahunAktif->status !== 'aktif' ? 'disabled' : '' }}
                    >
                        <option value="">-- Pilih Kelas --</option>
                        @foreach ($kelas as $k)
                            <option value="{{ $k->id }}"
                                {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- ACTION --}}
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="{{ route('tu.siswa.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary px-4"
                        {{ $tahunAktif->status !== 'aktif' ? 'disabled' : '' }}
                    >
                        <i class="bi bi-save me-1"></i> Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection