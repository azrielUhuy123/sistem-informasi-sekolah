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
                    <i class="bi bi-shield-check me-2"></i> Sistem Terhubung
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end align-items-center mb-4">

        <a href="{{ route('tu.pengajuan-surat.create') }}" 
           class="btn btn-dark">
            <i class="bi bi-plus-lg me-1"></i>
            Tambah Pengajuan
        </a>
    </div>

    {{-- ALERT SUCCESS --}}
    @if(session('success'))

        <div class="alert alert-success border-0 shadow-sm">

            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}

        </div>

    @endif

    {{-- ALERT ERROR --}}
    @if(session('error'))

        <div class="alert alert-danger border-0 shadow-sm">

            <i class="bi bi-exclamation-circle-fill me-2"></i>
            {{ session('error') }}

        </div>

    @endif

    {{-- CARD --}}

    <div class="card border-0 shadow-sm">

        {{-- TOP BAR --}}
        <div
            class="card-header bg-light border-0 p-4"
        >

            <div
                class="d-flex justify-content-between align-items-center flex-wrap gap-3"
            >

                <div>

                    <h5 class="fw-semibold mb-1">
                        Daftar Pengajuan Surat
                    </h5>

                    <span class="text-muted small">
                        Total:
                        {{ $pengajuan->count() }}
                        pengajuan   
                    </span>

                </div>

                <div style="max-width: 280px; width:100%;">

                    <input
                        type="text"
                        id="searchInput"
                        class="form-control rounded-3"
                        placeholder="Cari dokumen..."
                    >

                </div>

            </div>

        </div>

        <div class="card-body">

            <table class="table align-middle">

                <thead>

                    <tr>

                        <th>No</th>
                        <th>Nama Dokumen</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Aksi</th>

                    </tr>

                </thead>

                <tbody id="tablePengajuan">

                    @forelse($pengajuan as $row)

                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>

                            <td>
                                {{ $row->nama_surat }}
                            </td>

                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ $row->kategori_surat }}
                                </span>
                            </td>

                            <td>

                                @if($row->status == 'draft')

                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">
                                        <i class="bi bi-hourglass-split me-1"></i> Validasi
                                    </span>

                                @elseif($row->status == 'disetujui')

                                    <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle">
                                        <i class="bi bi-check-circle me-1"></i> Valid
                                    </span>

                                @else

                                    <span class="badge bg-danger-subtle text-danger-emphasis border border-danger-subtle">
                                        <i class="bi bi-x-circle me-1"></i> Ditolak
                                    </span>

                                @endif

                            </td>

                            <td>
                                <div class="dropdown">

                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                            type="button"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false">

                                        <i class="bi bi-gear-fill"></i>

                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">

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
                                                onsubmit="return confirm('Hapus data ini?')">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="dropdown-item text-danger">
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
                                class="text-center text-muted py-4">

                                Belum ada pengajuan surat

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

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