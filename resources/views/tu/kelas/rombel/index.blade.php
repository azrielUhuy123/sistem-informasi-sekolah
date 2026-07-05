@extends('layouts.app')

@section('title', 'Rombel')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2">Manajemen Rombel</h3>
                    <p class="mb-0 opacity-75">Kelola data rombel sekolah</p>
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

    {{-- CARD --}}
    <div class="card border-0 shadow-sm">
        {{-- TOP BAR --}}
        <div class="card-header bg-light border-0 p-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h5 class="fw-semibold mb-1">Daftar Rombel</h5>
                    <span class="text-muted small">
                        Total: {{ $rombels->count() }} rombel
                    </span>
                    <div class="small text-muted mt-1">
                        <i class="bi bi-building me-1"></i>
                        Kelas {{ $kelasGrup->tingkat }} – Angkatan {{ $kelasGrup->angkatan->tahun }}
                    </div>
                </div>

                <div class="d-flex flex-wrap align-items-center gap-2">
                    <a href="{{ route('tu.kelas.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                    <a href="{{ route('tu.kelas.rombel.create', $kelasGrup->id) }}" class="btn btn-dark btn-sm">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Rombel
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body">
            @if(session('import_report'))
                <div class="alert alert-info border-0 shadow-sm mb-4">
                    <div class="fw-semibold mb-2"><i class="bi bi-info-circle me-2"></i> Laporan Import</div>
                    <div class="small">
                        Total diproses: {{ session('import_report.total') }}<br>
                        Penempatan baru: {{ session('import_report.created') }}<br>
                        Pindah kelas: {{ session('import_report.updated') }}
                    </div>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Kelas</th>
                            <th>Angkatan</th>
                            <th>Jumlah Siswa</th>
                            <th>Wali Kelas</th>
                            <th width="120" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rombels as $rombel)
                            <tr>
                                <td class="fw-semibold">{{ $rombel->nama_kelas }}</td>
                                <td>{{ $kelasGrup->angkatan->tahun }}</td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle">
                                        {{ $rombel->siswa_count }} Siswa
                                    </span>
                                </td>
                                <td>{{ optional(optional($rombel->waliKelas)->guru)->nama ?? '-' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('tu.kelas.rombel.show', $rombel->id) }}"
                                       class="btn btn-sm btn-dark">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Belum ada rombel
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
