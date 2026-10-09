@extends('layouts.app')

@section('title', 'Arsip Dokumen')

@section('content')

<div class="container-fluid">
    
    {{-- HEADER --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2">Arsip Dokumen</h3>
                    <p class="mb-0 opacity-75">Unduh dan akses seluruh dokumen resmi sekolah sesuai kategori yang tersedia.</p>
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
        title="Daftar Dokumen Sekolah"
        subtitle="Seluruh Dokumen Sekolah pada SDN Mekar Jaya"
    >
        <x-slot:badge> 
            <span class="badge bg-primary rounded-pill px-2 py-1">
                {{ $dokumen->count() }} <span class="text-badge">Dokumen</span>
            </span>
        </x-slot:badge>
 
        <x-slot:actions>
            <input
                type="text"
                id="searchInput"
                class="form-control table-search"
                placeholder="Cari Dokumen..."
            >            
        </x-slot:actions>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th width="70">
                            No
                        </th>
                        <th>
                            Nama Dokumen
                        </th>
                        <th width="150">
                            Kategori
                        </th>                        
                        <th width="90">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody id="tableDokumen">
                    @forelse($dokumen as $row)
                        <tr>
                            {{-- NOMOR --}}
                            <td>
                                <span class="badge bg-light text-dark rounded-circle">
                                    {{ $loop->iteration }}
                                </span>
                            </td>
                            {{-- NAMA SURAT --}}            
                            <td>
                                <div class="d-flex align-items-center">                                    
                                    <div>
                                        <div class="fw-semibold">{{ $row->nama_surat }}</div>
                                    </div>
                                </div>
                            </td>
                            {{-- KATEGORI --}}
                            <td>
                                <span class="badge bg-teal rounded-pill px-3 py-2">
                                    {{ $row->kategori_surat }}
                                </span>
                            </td>                            
                            {{-- AKSI --}}
                            <td>
                                <a
                                    href="{{ asset('uploads/dokumen/' . $row->file) }}"
                                    download
                                    class="btn btn-primary btn-sm rounded-pill px-3 py-0">                                    
                                    Download <i class="bi bi-cloud-arrow-down-fill ms-1"></i>

                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5"
                                class="text-center py-5">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="bi bi-folder-x text-muted"
                                        style="font-size: 50px;"></i>
                                    <h6 class="mt-3 text-muted">
                                        Belum ada dokumen
                                    </h6>
                                    <p class="text-muted small mb-0">
                                        Dokumen sekolah yang diupload akan muncul di sini
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-table.card>
 
</div>
<style>

.surat-icon{

    width:50px;

    height:50px;

    border-radius:16px;

    background:linear-gradient(135deg,#2563eb,#0f4c81);

    color:#fff;

    display:flex;

    align-items:center;

    justify-content:center;

    font-size:1.2rem;

    flex-shrink:0;

}

@media(max-width:768px){

    .surat-icon{

        width:42px;

        height:42px;

        font-size:1rem;

    }

}

</style>
{{-- SEARCH --}}
<script>

    const searchInput =
        document.getElementById('searchInput');

    searchInput.addEventListener('keyup', function () {

        const keyword =
            this.value.toLowerCase();

        const rows =
            document.querySelectorAll(
                '#tableDokumen tr'
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