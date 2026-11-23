@extends('layouts.app')

@section('title', 'Pengajuan Surat')

@section('content')

<div class="container-fluid dt-siswa-page">

    <x-table.page-header
        icon="bi-file-earmark-text"
        eyebrow="Administrasi / Pengajuan Surat"
        title="Data Pengajuan Surat"
        description="Kelola pengajuan surat atau laporan untuk divalidasi kepala sekolah."
    />

    {{-- ALERT --}}
    <x-alert.flash key="success" type="success"/>

    <x-alert.flash key="error" type="danger"/>    

    {{-- TABLE --}}
    <x-table.card
        title="Daftar Pengajuan Surat"
        subtitle="Seluruh Pengajuan Surat pada SDN Mekar Jaya"
        :count="$pengajuan->count()"
        count-label="data"
    >
 
        <x-slot:toolbar>
            <x-table.local-toolbar
                search-id="searchInput"
                search-label="Cari pengajuan surat"
                search-placeholder="Cari dokumen..."
            >
                <x-button.add
                    :href="route('tu.pengajuan-surat.create')"
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
                        <x-table.heading width="150px">Status</x-table.heading>
                        <x-table.heading width="90px">Aksi</x-table.heading>
                    </tr>
                </thead>
                <tbody id="tablePengajuan">
                    @forelse($pengajuan as $row)
                        <tr>
                            <td>
                                <span class="badge bg-light text-dark rounded-circle">
                                    {{ $loop->iteration }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">                                    
                                    <div>
                                        <div class="fw-semibold">{{ $row->nama_surat }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-teal rounded-pill px-3 py-2">
                                    {{ $row->kategori_surat }}
                                </span>
                            </td>
                            <td>
                                @if($row->status == 'draft')
                                    <span class="badge-status pending">
                                        <i class="bi bi-hourglass-split me-2"></i> Validasi
                                    </span>
                                @elseif($row->status == 'disetujui')
                                    <span class="badge-status aktif">
                                        <i class="bi bi-check-circle-fill me-2"></i> Valid
                                    </span>
                                @else
                                    <span class="badge-status nonaktif">
                                        <i class="bi bi-x-circle-fill me-2"></i> Ditolak
                                    </span>
                                @endif
                            </td>
                            <td>
                                <x-table.actions>                                 
                                    <li>
                                        <a class="dropdown-item" href="{{ route('tu.pengajuan-surat.show', $row->id) }}">
                                            <i class="bi bi-eye me-2"></i> Detail
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('tu.pengajuan-surat.edit', $row->id) }}">
                                            <i class="bi bi-pencil-square me-2"></i> Edit
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('tu.pengajuan-surat.destroy', $row->id) }}"
                                            method="POST"
                                            >
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                            class="dropdown-item text-danger btn-confirm"
                                            data-title="HAPUS PENGAJUAN SURAT?"
                                            data-message="Pengajuan Surat akan dihapus secara permanen"
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
                            title="Belum ada pengajuan surat"
                            description="Pengajuan surat belum tersedia."
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
                '#tablePengajuan tr'
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