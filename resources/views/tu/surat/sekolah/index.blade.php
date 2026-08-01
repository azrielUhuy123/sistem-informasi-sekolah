@extends('layouts.app')

@section('title', 'Dokumen Sekolah')

@section('content')

<div class="container-fluid">
    
    {{-- HEADER --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2">Data Dokumen Sekolah</h3>
                    <p class="mb-0 opacity-75">Kelola template dokumen internal sekolah yang dapat diakses oleh guru dan kepsek</p>
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

    <div class="d-flex justify-content-end align-items-center mb-4">

        <a href="{{ route('tu.dokumen.create', 'sekolah') }}" 
           class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>
            Tambah Dokumen
        </a>
    </div>

    {{-- TABLE --}}
    <x-table.card
        title="Daftar Dokumen Sekolah"
        subtitle="Seluruh Dokumen Sekolah pada SDN Mekar Jaya"
    >
        <x-slot:badge>
            <span class="badge bg-primary rounded-pill px-3 py-2">
                {{ $dokumen->count() }} Dokumen Sekolah
            </span>
        </x-slot:badge>
 
        <x-slot:actions>
            <input
                type="text"
                id="searchInput"
                class="form-control rounded-3"
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
                        <th width="150">
                            Preview
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
                            <td class="text-center">
                                <span class="badge bg-light text-dark rounded-pill px-3 py-2">
                                    {{ $loop->iteration }}
                                </span>
                            </td>
                            {{-- NAMA SURAT --}}            
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="surat-icon me-3"><i class="bi bi-file-earmark-text-fill"></i></div>
                                    <div>
                                        <div class="fw-bold">{{ $row->nama_surat }}</div>
                                    </div>
                                </div>
                            </td>
                            {{-- KATEGORI --}}
                            <td>
                                <span class="badge bg-teal rounded-pill px-3 py-2">
                                    {{ $row->kategori_surat }}
                                </span>
                            </td>
                            {{-- PREVIEW --}}   
                            <td>
                                @if(
                                    pathinfo(
                                        $row->file,
                                        PATHINFO_EXTENSION
                                    ) === 'pdf'
                                )
                                    <a
                                        href="{{ asset('uploads/dokumen/' . $row->file) }}"
                                        target="_blank"
                                        class="badge bg-primary rounded-pill px-3 py-2"
                                    >
                                        <i class="bi bi-cloud-arrow-down"></i>
                                        
                                    </a>
                                @else
                                    <span class="text-muted small">
                                        Preview hanya untuk PDF
                                    </span>
                                @endif
                            </td>
                            {{-- AKSI --}}
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
                                            <a class="dropdown-item" href="{{ asset('uploads/dokumen/' . $row->file) }}" download>
                                                <i class="bi bi-download me-2"></i> Download
                                            </a>
                                        </li>    
                                        <li>
                                            <a class="dropdown-item" href="{{ route('tu.dokumen.edit', $row->id) }}">
                                                <i class="bi bi-pencil-square me-2"></i> Edit
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('tu.dokumen.destroy', $row->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Hapus data ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger btn-confirm"
                                                data-title="HAPUS DOKUMEN INI?"
                                                data-message="Dokumen ini akan dihapus secara permanen"
                                                data-button="btn-danger">
                                                    <i class="bi bi-trash me-2"></i> Hapus
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
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