@extends('layouts.app')

@section('title', 'Data Pendaftar PPDB')

@section('content')

<div class="container-fluid">
{{-- HEADER --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                    <div>
                        <h3 class="fw-bold mb-2">Daftar Pendaftar PPDB</h3>
                        <p class="mb-0 opacity-75">Kelola data pendaftar PPDB</p>
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

    {{-- CARD --}}
    <x-table.card
        title="Daftar Pendaftar PPDB"
        subtitle="Seluruh pendaftar peserta didik baru pada SDN Mekar Jaya."
    >
        <x-slot:badge>
            <span class="badge bg-primary rounded-pill px-2 py-1">
                {{ $data->count() }} <span class="text-badge">Pendaftar</span>
            </span>
        </x-slot:badge>

        <x-slot:actions>
           <input
                type="text"
                id="searchInput"
                class="form-control table-search"
                placeholder="Cari Pendaftar..."                
            >  
        </x-slot:actions>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th width="180">No Pendaftaran</th>
                        <th>Nama</th>
                        <th width="150">Jenis Kelamin</th>
                        <th width="250">Tanggal Pendaftaran</th>
                        <th width="150">Status</th>
                        <th width="130">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tablePPDB">
                    @foreach($data as $item)
                    <tr>
                        <td>
                            <span>
                                {{ $item->no_pendaftaran }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                    <i class="bi bi-person-fill fs-4"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">
                                        {{ $item->nama }}
                                    </div>                                    
                                </div>
                            </div>
                            
                        </td>
                        
                        <td>
                            @if($item->jenis_kelamin == 'L')                    
                                <span class="badge bg-indigo rounded-pill px-2 py-1">
                                    <i class="bi bi-gender-male me-1"></i>
                                    Laki-Laki
                                </span>
                            @else
                                <span class="badge bg-rose rounded-pill px-2 py-1">
                                    <i class="bi bi-gender-female me-1"></i>
                                    Perempuan
                                </span>
                            @endif
                        </td>
                        <td>
                            <span class="text-muted">
                                {{ $item->tanggal_pendaftaran }}
                            </span>
                        </td>
                        <td>
                            @if($item->status == 'pending')
                                <span class="badge-status pending">
                                    <i class="bi bi-hourglass-split me-2"></i> Belum Diputuskan
                                </span>
                            @elseif($item->status == 'verifikasi')
                                <span class="badge-status verifikasi">
                                    <i class="bi bi-search me-2"></i>
                                    Tahap Verifikasi (Data Lama)
                                </span>
                            @elseif($item->status === 'diterima')
                                <span class="badge-status aktif">
                                    <i class="bi bi-check-circle-fill me-2"></i>
                                    Diterima
                                </span>
                            @elseif($item->status === 'ditolak')
                                <span class="badge-status nonaktif">
                                    <i class="bi bi-x-circle-fill me-2"></i>
                                    Ditolak
                                </span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('tu.ppdb.pendaftar.show', $item) }}"
                            class="btn btn-primary btn-sm rounded-pill px-3 py-0">
                                Detail
                                <i class="bi bi-chevron-right ms-1"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>   
            <div class="mt-4 d-flex justify-content-end">
                {{ $data->links() }} 
            </div>
        </div>
    </x-table.card>
</div>
{{-- SEARCH --}}
<script>

    const searchInput =
        document.getElementById('searchInput');

    searchInput.addEventListener('keyup', function () {

        const keyword =
            this.value.toLowerCase();

        const rows =
            document.querySelectorAll(
                '#tablePPDB tr'
            );

        rows.forEach(row => {

            const text =
                row.innerText.toLowerCase();

            row.style.display =
                text.includes(keyword)
                ? ''
                : 'none';

        });
    });

</script>
@endsection