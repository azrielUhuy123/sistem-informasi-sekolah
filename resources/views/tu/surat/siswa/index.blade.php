@extends('layouts.app')

@section('title', 'Dokumen Siswa')

@section('content')

<div class="container-fluid">
    
    {{-- HEADER --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2">Data Dokumen Siswa</h3>
                    <p class="mb-0 opacity-75">Kelola template dokumen internal siswa</p>
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
        title="Daftar Dokumen Siswa"
        subtitle="Seluruh Dokumen Siswa pada SDN Mekar Jaya"
    >
        <x-slot:badge>
            <span class="badge bg-primary rounded-pill px-2 py-1">
                {{ $dokumen->count() }} <span class="text-badge">Dokumen Siswa</span>
            </span>
        </x-slot:badge>
 
        <x-slot:actions>
            <input
                type="text"
                id="searchInput"
                class="form-control table-search  "
                placeholder="Cari Dokumen..."
            >
            <x-button.add
                :href="route('tu.dokumen.create', 'siswa')"
                label="Tambah Dokumen Siswa"
            />
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
                        <th width="100">
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
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada data dokumen</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
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