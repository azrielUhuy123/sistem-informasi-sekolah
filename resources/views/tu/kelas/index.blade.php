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
                    <i class="bi bi-shield-check me-2"></i> Sinergi-MJ
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
            <span class="badge bg-primary rounded-pill px-2 py-1">
                {{ $grupKelas->count() }} <span class="text-badge">Kelas</span>
            </span>
        </x-slot:badge>

        <x-slot:actions>

            <button
                type="button"
                class="btn btn-primary rounded-3"
                data-bs-toggle="modal"
                data-bs-target="#importKelasModal"
            >
                <i class="bi bi-upload me-1"></i>
                Import Data
            </button>

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
                            <td>
                                <span class="badge bg-light text-dark rounded-circle">
                                    {{ $loop->iteration }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-semibold">
                                    Kelas  
                                    <span>
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
                                @php
                                    $kelasStatus = $grup->jumlah_kelas > 0 ? 'aktif' : 'draft';
                                @endphp
                                <span class="badge-status {{ $kelasStatus }}">
                                    {{ $grup->jumlah_kelas }} Kelas
                                </span>
                            </td> 
                            <td>
                                <x-table.actions>
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
                                </x-table.actions>                                
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada data kelas</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-table.card>
</div>
<x-modal.import
    id="importKelasModal"
    title="Import Data Kelas"
    subtitle="Tambahkan data kelas secara massal."
    action="{{ route('tu.siswa.import') }}"
    name="file"
    accept=".xlsx,.xls,.csv"
/>
@endsection
