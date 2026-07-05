@extends('layouts.app')

@section('title', 'Detail Kelas')

@section('content')

<div class="container-fluid">

{{-- HEADER --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2">Manajemen Kelas</h3>
                    <p class="mb-0 opacity-75">Kelola data kelas sekolah</p>
                </div>
                <div class="badge bg-white text-primary px-3 py-2 fs-6">
                    <i class="bi bi-shield-check me-2"></i> Sistem Terhubung
                </div>
            </div>
        </div>
    </div>

    {{-- ALERT SUCCESS --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- ALERT ERROR --}}
    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm">
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            {{ session('error') }}
        </div>
    @endif

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Detail Kelas</h1>
    <div>
        <a href="{{ route('tu.kelas.index') }}" class="btn btn-secondary">Kembali</a>
        <a href="{{ route('tu.kelas.edit', $kelas->id) }}" class="btn btn-warning">Edit</a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <h4 class="mb-3">Informasi Kelas</h4>

        <p><strong>Nama Kelas:</strong> {{ $kelas->nama_kelas }}</p>
        <p><strong>Tingkat:</strong> {{ $kelas->tingkat }}</p>
        <p><strong>Tahun Ajaran:</strong> {{ $kelas->tahun_ajaran }}</p>
    </div>
</div>

<div class="card">
    <div class="card-body">

        <h4 class="mb-3">Daftar Siswa</h4>

        @if($kelas->siswa->isEmpty())
            <p class="text-muted">Tidak ada siswa dalam kelas ini.</p>
        @else

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>NIS</th>
                    <th>Nama</th>
                    <th>Jenis Kelamin</th>
                    <th>Telepon Wali</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>

                @foreach($kelas->siswa as $s)
                <tr>
                    <td>{{ $s->nis }}</td>
                    <td>{{ $s->nama }}</td>
                    <td>{{ $s->jenis_kelamin }}</td>
                    <td>{{ $s->telepon_wali ?? '-' }}</td>
                    <td>
                        <a href="{{ route('tu.siswa.edit', $s->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>
        @endif

    </div>
</div>
</div>

@endsection
