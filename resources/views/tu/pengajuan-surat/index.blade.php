@extends('layouts.app')

@section('title', 'Pengajuan Surat')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2">Data Pengajuan Surat</h3>
                    <p class="mb-0 opacity-75">Kelola pengajuan Surat atau Laporan untuk divalidasi kepala sekolah</p>
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
        title="Daftar Pengajuan Surat"
        subtitle="Seluruh Pengajuan Surat pada SDN Mekar Jaya"
    >
        <x-slot:badge>
            <span class="badge bg-primary rounded-pill px-2 py-1">
                {{ $pengajuan->count() }} <span class="text-badge">Pengajuan Surat</span>
            </span>
        </x-slot:badge>
 
        <x-slot:actions>
            <input
                type="text"
                id="searchInput"
                class="form-control table-search"
                placeholder="Cari dokumen..."
            >
            <x-button.add
                :href="route('tu.pengajuan-surat.create')"
                label="Tambah Dokumen Sekolah"
            /> 
        </x-slot:actions>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th width="70">No</th>
                        <th>Nama Dokumen</th>
                        <th width="150">Kategori</th>
                        <th width="150">Status</th>
                        <th width="90">Aksi</th>
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
                        <tr>
                            <td colspan="5"
                                class="text-center text-muted">
                                Belum ada data pengajuan surat
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