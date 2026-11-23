@extends('layouts.app')

@section('title', 'Dokumen Sekolah')

@section('content')

<div class="container-fluid dt-siswa-page">

    <x-table.page-header
        icon="bi-file-earmark-text"
        eyebrow="Dokumen / Sekolah"
        title="Data Dokumen Sekolah"
        description="Kelola template dokumen internal sekolah yang dapat diakses guru dan kepala sekolah."
    />

    {{-- ALERT --}}
    <x-alert.flash key="success" type="success"/>

    <x-alert.flash key="error" type="danger"/>

    {{-- TABLE --}}
    <x-table.card
        title="Daftar Dokumen Sekolah"
        subtitle="Seluruh Dokumen Sekolah pada SDN Mekar Jaya"
        :count="$dokumen->count()"
        count-label="data"
    >
 
        <x-slot:toolbar>
            <x-table.local-toolbar
                search-id="searchInput"
                search-label="Cari dokumen sekolah"
                search-placeholder="Cari dokumen..."
            >
                <x-button.add
                    :href="route('tu.dokumen.create', 'sekolah')"
                    label="Tambah Dokumen Sekolah"
                />
            </x-table.local-toolbar>
        </x-slot:toolbar>
        <div class="table-responsive dt-siswa-table-wrap">
            <table class="table table-hover align-middle dt-table">
                <thead>
                    <tr>
                        <x-table.heading width="70px">No</x-table.heading>
                        <x-table.heading>Nama Dokumen</x-table.heading>
                        <x-table.heading width="150px">Kategori</x-table.heading>
                        <x-table.heading width="100px">Preview</x-table.heading>
                        <x-table.heading width="90px">Aksi</x-table.heading>
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
                                        class="btn btn-primary rounded-pill"
                                    >
                                        <i class="bi bi-file-earmark-text-fill"></i>  
                                        
                                    </a>
                                @else
                                    <span class="text-muted small">
                                        Preview hanya untuk PDF
                                    </span>
                                @endif
                            </td>
                            {{-- AKSI --}}
                            <td>
                                <x-table.actions>              
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
                                            >
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
                                </x-table.actions>
                            </td>
                        </tr>
                    @empty
                        <x-table.empty
                            :colspan="5"
                            title="Belum ada data dokumen sekolah"
                            description="Template dokumen sekolah belum tersedia."
                        />
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