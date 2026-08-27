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
    
    {{-- 🟡 EMPTY STATE --}}
    @if($siswa->isEmpty())
        <div class="alert alert-info">
            Semua siswa sudah memiliki kelas di tahun ajaran ini.
        </div>
    @endif

    {{-- 🔷 FORM --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="mb-4">
                <h4 class="text-primary fw-bold text-center mb-1">Pindahkan Siswa Ke Kelas</h4>
                <p class="text-muted text-center">
                    Tahun Ajaran :
                    <strong>{{ $tahunAktif->tahun }}</strong>
                </p>
            </div>
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
                        class="form-control"
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
                <div class="mb-5">
                    <label class="form-label">Kelas</label>
                    <select 
                        name="kelas_id" 
                        class="form-control"
                        required
                        {{ $tahunAktif->status !== 'aktif' ? 'disabled' : '' }}
                    >
                        <option value="">-- Pilih Kelas --</option>
                        @foreach ($kelas as $k)
                            <option value="{{ $k->id }}"
                                {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kelas_label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- ACTION --}}
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="{{ route('tu.siswa.index') }}" class="btn btn-outline-secondary rounded-3">
                        <i class="bi bi-chevron-left me-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary rounded-3"
                        {{ $tahunAktif->status !== 'aktif' ? 'disabled' : '' }}
                    >
                        Simpan <i class="bi bi-save ms-1"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection