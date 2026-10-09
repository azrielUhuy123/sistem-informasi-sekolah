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
        title="Daftar Rombel"
        subtitle="Kelas {{ $kelasGrup->kelasMaster->tingkat }} Angkatan {{ $kelasGrup->angkatan->tahun }}"
    > 
        <x-slot:badge>
            <span class="badge bg-primary rounded-pill px-2 py-1">
                {{ $rombels->count() }} <span class="text-badge">Rombel</span>
            </span>
        </x-slot:badge>

        <x-slot:actions>
            <x-button.add
                :href="route('tu.kelas.rombel.create', $kelasGrup->id)"
                label="Tambah Rombel"
            />            
        </x-slot:actions>
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
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th width="70">No</th>
                        <th width="120">Rombel</th>
                        <th width="90">Tingkat</th>
                        <th>Wali Kelas</th>
                        <th width="150">Jumlah Siswa</th>
                        <th width="150">Angkatan</th>
                        <th width="90" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rombels as $rombel)
                        <tr>
                            <td>
                                <span class="badge bg-light text-dark rounded-circle">
                                    {{ $loop->iteration }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-bold">
                                    {{ $rombel->nama_kelas_label }}
                                </div>
                            </td>
                            <td>{{ $kelasGrup->kelasMaster->tingkat }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <div class="fw-semibold">
                                            {{ optional(optional($rombel->waliKelas)->guru)->nama ?? '-' }}
                                        </div>

                                        <small class="text-muted">
                                            NIP : {{ optional(optional($rombel->waliKelas)->guru)->nip ?? '-' }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @php
                                    $siswaStatus = $rombel->siswa_count > 0 ? 'aktif' : 'draft';
                                @endphp
                                <span class="badge-status {{ $siswaStatus }}">                                    
                                    {{ $rombel->siswa_count }} Siswa
                                </span>
                            </td>
                            <td>
                                <div class="fw-semibold">
                                    {{ $kelasGrup->angkatan->tahun }}
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">                                    
                                    <a href="{{ route('tu.kelas.rombel.show', $rombel->id) }}"
                                    class="btn btn-primary btn-sm rounded-pill px-3 py-0">
                                        Detail
                                        <i class="bi bi-chevron-right ms-1"></i>
                                    </a>                      
                                    <form action="{{ route('tu.kelas.rombel.destroy', $rombel->id) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger rounded-pill px-3 py-0 btn-sm btn-confirm"
                                        data-title="Hapus Rombel"
                                        data-message="Rombel akan dihapus dari sistem ini."
                                        data-button="btn-danger">
                                            <i class="bi bi-trash"></i>                                            
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada data rombel</td>
                        </tr>
                    @endforelse
                </tbody>
                
            </table>
            
        </div>
        <div class="d-flex justify-content-between align-items-center mt-4">
            <a href="{{ route('tu.kelas.index') }}" 
            class="btn btn-outline-secondary rounded-3">
                <i class="bi bi-chevron-left me-1"></i> Kembali
            </a>    
        </div>
    </x-table.card>
</div>

@endsection
