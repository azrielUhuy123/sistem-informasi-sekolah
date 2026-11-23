@extends('layouts.app')

@section('title', 'Rombel')

@section('content')

<div class="container-fluid dt-siswa-page">

    <x-table.page-header
        icon="bi-collection"
        eyebrow="Data Akademik / Rombongan Belajar"
        title="Manajemen Rombel"
        description="Kelola data rombel sekolah."
    />

    {{-- ALERT --}}
    <x-alert.flash key="success" type="success"/>

    <x-alert.flash key="error" type="danger"/>

    {{-- TABLE --}}
    <x-table.card
        title="Daftar Rombel"
        subtitle="Kelas {{ $kelasGrup->kelasMaster->tingkat }} Angkatan {{ $kelasGrup->angkatan->tahun }}"
        :count="$rombels->count()"
        count-label="data"
    > 

        <x-slot:pageActions>
            <x-button.add
                :href="route('tu.kelas.rombel.create', $kelasGrup->id)"
                label="Tambah Rombel"
            />            
        </x-slot:pageActions>
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
        <div class="table-responsive dt-siswa-table-wrap">
            <table class="table table-hover align-middle dt-table">
                <thead>
                    <tr>
                        <x-table.heading width="70px">No</x-table.heading>
                        <x-table.heading width="120px">Rombel</x-table.heading>
                        <x-table.heading width="90px">Tingkat</x-table.heading>
                        <x-table.heading>Wali Kelas</x-table.heading>
                        <x-table.heading width="150px">Jumlah Siswa</x-table.heading>
                        <x-table.heading width="150px">Angkatan</x-table.heading>
                        <x-table.heading width="90px" class="text-center">Aksi</x-table.heading>
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
                        <x-table.empty
                            :colspan="7"
                            title="Belum ada data rombel"
                            description="Data rombel belum tersedia untuk angkatan ini."
                        />
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
