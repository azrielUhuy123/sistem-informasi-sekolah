@extends('layouts.app')

@section('title', 'Manajemen Kelas')

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

    {{-- CARD --}}
    <div class="card border-0 shadow-sm">
        {{-- TOP BAR --}}
        <div class="card-header bg-light border-0 p-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h5 class="fw-semibold mb-1">
                        Daftar Kelas
                    </h5>
                    <span class="text-muted small">
                        Total:
                        {{ $grupKelas->count() }}
                        kelas
                    </span>
                </div>

                <div class="d-flex flex-column flex-md-row align-items-md-center gap-3">
                    <div class="d-flex align-items-start gap-2">
                        <div class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="bi bi-file-earmark-excel fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-semibold mb-1">Import Data Siswa</h6>
                            <p class="small text-muted mb-0">Unggah file Excel/CSV untuk menambahkan data siswa.</p>
                        </div>
                    </div>

                    <form action="{{ route('tu.siswa.import') }}" method="POST" enctype="multipart/form-data" class="d-flex align-items-center gap-2 flex-wrap">
                        @csrf
                        <div class="border rounded-3 px-2 d-flex gap-2">
                            <i class="bi bi-paperclip text-muted"></i>
                            <input class="form-control form-control-sm border-0 shadow-none" id="formFileSm" type="file" name="file" accept=".xlsx,.xls,.csv" required>
                        </div>
                        <button class="btn btn-dark btn-sm">
                            <i class="bi bi-upload me-1"></i>
                            Import
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="card-body">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Tingkat</th>
                        <th>Angkatan</th>
                        <th>Jumlah Kelas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($grupKelas as $grup)
                        <tr>
                            <td>Kelas {{ $grup->kelasMaster->tingkat }}</td>

                            <td>
                                {{ $grup->angkatan->tahun }}
                                <span class="text-muted">
                                    ({{ $grup->angkatan->keterangan }})
                                </span>
                            </td>

                            <td>
                                <span class="badge bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle">
                                    {{ $grup->jumlah_kelas }} Kelas
                                </span>
                            </td>

                            <td>
                                <a href="{{ route('tu.kelas.rombel.index', $grup->id) }}"
                                   class="btn btn-sm btn-dark">
                                    Masuk
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                Belum ada data kelas
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
