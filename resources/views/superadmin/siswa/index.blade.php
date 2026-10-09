@extends('layouts.app')

@section('title', 'Manajemen Data Siswa')

@section('content')
<div class="container-fluid">
    {{-- HEADER --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2">Data Siswa</h3>
                    <p class="mb-0 opacity-75">Daftar seluruh data siswa sekolah</p>
                </div>
                <div class="badge bg-white text-primary px-3 py-2 fs-6">
                    <i class="bi bi-shield-check me-2"></i> Sinergi-MJ
                </div>
            </div>
        </div>
    </div>

    <x-alert.flash key="success" type="success"/>
    <x-alert.flash key="error" type="danger"/>

    <x-table.card
        title="Daftar Seluruh Siswa"
        subtitle="Seluruh data siswa pada SDN Mekar Jaya">
        <x-slot:badge>
            <span class="badge bg-primary rounded-pill px-2 py-1">
                {{ $siswa->total() }} <span class="text-badge">siswa</span>
            </span>
        </x-slot:badge>

        <x-slot:actions>
            <div class="d-flex flex-column align-items-end gap-2">
                {{-- FILTER --}}
                <form
                    action="{{ route('superadmin.siswa.index') }}"
                    method="GET"
                    class="siswa-filter-form"
                >
                    <select
                        name="status" 
                        class="form-control siswa-filter-status"                        
                    >
                        <option value="">Semua Status</option>
                        <option
                            value="aktif"
                            @selected(request('status')=='aktif')
                        >
                            Aktif
                        </option>
                        <option
                            value="nonaktif"
                            @selected(request('status')=='nonaktif')
                        >
                            Nonaktif
                        </option>
                    </select>
                    {{-- SEARCH --}}
                    <input
                        type="text"
                        id="search"
                        name="search"
                        class="form-control siswa-filter-search"
                        placeholder="Cari Nama / NIS..."
                        value="{{ request('search') }}"
                    >
                    <button
                        type="submit"
                        class="btn btn-primary rounded-3"
                    >
                        <i class="bi bi-funnel me-1"></i>
                        Filter
                    </button>
                    @if(request()->filled('status') || request()->filled('search'))
                        <a
                            href="{{ route('superadmin.siswa.index') }}"
                            class="btn btn-outline-secondary rounded-3"
                        >
                            Reset
                        </a>
                    @endif
                </form>
                
            </div>
        </x-slot:actions>
                    
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>                            
                        <th width="70">No</th>
                        <th>Nama</th>
                        <th width="150">NISN</th>
                        <th width="150">Rombel</th>
                        <th width="150">Angkatan</th>
                        <th width="150">Status</th>
                        <th width="90">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableSiswa">
                    @forelse ($siswa as $row)
                        <tr>                                
                            <td>
                                <span class="badge bg-light text-dark rounded-circle">
                                    {{ $loop->iteration + ($siswa->currentPage()-1) * $siswa->perPage() }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                        <i class="bi bi-person-fill fs-4"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">
                                            {{ $row->nama }}
                                        </div>
                                        <small class="text-muted">
                                            NIS : {{ $row->nis }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $row->nisn ?? '-' }}</td>
                            <td>
                                <div class="fw-bold border text-label text-center bg-light rounded-3 px-2 py-2">
                                    {{ $row->penempatanAktif?->kelas?->nama_kelas_label ?? '-' }}
                                </div>
                            </td>
                            <td>
                                {{ $row->penempatanAktif?->kelas?->kelasGrup?->angkatan?->tahun ?? '-' }}
                            </td>
                            <td>
                                @if($row->trashed())
                                    <span class="badge-status nonaktif">
                                        <i class="bi bi-circle-fill me-2"></i> Nonaktif
                                    </span> 
                                @else
                                    <span class="badge-status aktif">
                                        <i class="bi bi-circle-fill me-2"></i> Aktif
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($row->trashed())
                                    <button
                                        type="button"
                                        class="btn btn-primary btn-sm rounded-pill px-3 py-0"
                                        disabled
                                    >
                                        Detail
                                        <i class="bi bi-chevron-right ms-1"></i>
                                    </button>   
                                @else
                                    <a
                                        href="{{ route('superadmin.siswa.show', $row) }}"
                                        class="btn btn-primary btn-sm rounded-pill px-3 py-0"
                                    >
                                        Detail
                                        <i class="bi bi-chevron-right ms-1"></i>
                                    </a> 
                                @endif                                                                                                   
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <div class="mb-3">
                                    <i class="bi bi-inbox fs-1"></i>
                                </div>
                                <div class="fw-semibold">Tidak ada data siswa</div>
                                <div class="small">Coba ubah filter atau hapus kata kunci pencarian.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $siswa->links() }}
        </div>        
    </x-table.card>
</div>
@endsection