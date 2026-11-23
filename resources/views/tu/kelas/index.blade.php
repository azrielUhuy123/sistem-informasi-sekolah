@extends('layouts.app')

@section('title', 'Manajemen Kelas')

@section('content')

<div class="container-fluid dt-siswa-page">

    <x-table.page-header
        icon="bi-door-open"
        eyebrow="Data Akademik / Kelas"
        title="Manajemen Kelas"
        description="Kelola data kelas sekolah."
    />

    {{-- ALERT --}}
    <x-alert.flash key="success" type="success"/>

    <x-alert.flash key="error" type="danger"/>

    {{-- TABLE --}}
    <x-table.card
        title="Daftar Kelas"
        subtitle="Daftar seluruh kelas pada SDN Mekar Jaya"
        :count="$grupKelas->count()"
        count-label="data"
    >

        <x-slot:pageActions>

            <button
                type="button"
                class="btn btn-primary rounded-3"
                data-bs-toggle="modal"
                data-bs-target="#importKelasModal"
            >
                <i class="bi bi-upload me-1"></i>
                Import Data
            </button>

        </x-slot:pageActions>
        <div class="table-responsive dt-siswa-table-wrap">
            <table class="table table-hover align-middle dt-table">
                <thead>
                    <tr>
                        <x-table.heading width="70px">No</x-table.heading>
                        <x-table.heading>Tingkat</x-table.heading>
                        <x-table.heading width="200px">Angkatan</x-table.heading>
                        <x-table.heading width="150px">Jumlah Kelas</x-table.heading>
                        <x-table.heading width="90px">Aksi</x-table.heading>
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
                        <x-table.empty
                            :colspan="5"
                            title="Belum ada data kelas"
                            description="Data kelas belum tersedia."
                        />
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
