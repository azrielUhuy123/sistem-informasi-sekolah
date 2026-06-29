@extends('layouts.app')

@section('title', 'Manajemen Kelas')

@section('content')
<div class="container-fluid">

    {{-- HEADER --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2">Data Kelas</h3>
                    <p class="mb-0 opacity-75">Kelola data kelas sekolah</p>
                </div>
                <div class="badge bg-white text-primary px-3 py-2 fs-6">
                    <i class="bi bi-shield-check me-2"></i> Sistem Terhubung
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-end align-items-center mb-4">
    <a href="{{ route('tu.kelas.grup.create') }}"
                class="btn btn-dark">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Grup
                </a>
                </div>
    <div class="card shadow-sm">
        <div class="card-body">

            <div class="d-flex justify-content-end align-items-center mb-3">
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                    <form action="{{ route('tu.siswa.import') }}" method="POST" enctype="multipart/form-data" class="d-flex gap-2">
                        @csrf
                        <input type="file" name="file" class="form-control form-control-sm" required>
                        <button class="btn btn-primary btn-sm">
                            Import Excel
                        </button>
                    </form>

            </div>

            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 20%">Tingkat</th>
                        <th>Angkatan</th>
                        <th style="width: 20%">Jumlah Kelas</th>
                        <th style="width: 15%" class="text-center">Aksi</th>
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
                                <span class="badge bg-secondary">
                                    {{ $grup->jumlah_kelas }} Kelas
                                </span>
                            </td>

                            <td class="text-center">
                                <a href="{{ route('tu.kelas.rombel.index', $grup->id) }}"
                                class="btn btn-sm btn-outline-primary">
                                    Masuk
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">
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
