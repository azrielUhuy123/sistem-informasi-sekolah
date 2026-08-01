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

    {{-- ALERT --}}
    <x-alert.flash key="success" type="success"/>

    <x-alert.flash key="error" type="danger"/>

    {{-- TABLE --}}
    <x-table.card
        title="Daftar Kelas"
        subtitle="Daftar seluruh kelas pada SDN Mekar Jaya"
    >
        <x-slot:badge>
            <span class="badge bg-primary rounded-pill px-3 py-2">
                {{ $grupKelas->count() }} Kelas
            </span>
        </x-slot:badge>

        <x-slot:actions>
            <form action="{{ route('tu.siswa.import') }}" method="POST" enctype="multipart/form-data" class="d-flex align-items-center gap-2 flex-wrap">
                @csrf
                <div class="border rounded-3 px-2 d-flex gap-1">
                    <i class="bi bi-paperclip text-muted mt-1"></i>
                    <input class="form-control form-control-sm mt-1" id="formFileSm" type="file" name="file" accept=".xlsx,.xls,.csv" required>
                </div>
                <button class="btn btn-primary">
                    <i class="bi bi-upload me-1"></i>
                    Import
                </button>
            </form>
        </x-slot:actions>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th width="70">No</th>
                        <th>Tingkat</th>
                        <th width="200">Angkatan</th>
                        <th width="150">Jumlah Kelas</th>
                        <th width="90">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($grupKelas as $grup)
                        <tr>
                            <td class="text-center">
                                <span class="badge bg-light text-dark rounded-pill px-3 py-2">
                                    {{ $loop->iteration }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-semibold">
                                    Kelas  
                                    <span class="font-monospace">
                                        {{ $grup->kelasMaster->tingkat }}
                                    </span>
                                    
                                </div>
                            </td>
                            <td>
                                {{ $grup->angkatan->tahun }}
                                <span class="text-muted">
                                    ({{ $grup->angkatan->keterangan }})
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-info text-dark fw-semibold rounded-pill px-3 py-2">
                                    {{ $grup->jumlah_kelas }} Kelas
                                </span>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-primary"
                                        type="button"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="bi bi-gear-fill"></i>
                                        <i class="bi bi-caret-down-fill"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('tu.kelas.rombel.index', $grup->id) }}">
                                                <i class="bi bi-door-open me-2"></i> Rombel
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('tu.kelas.export',$grup) }}">
                                                <i class="bi bi-file-earmark-excel me-2"></i> Export Excel
                                            </a>                                            
                                        </li>
                                    </ul>
                                </div>
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
    </x-table.card>
</div>

@endsection
