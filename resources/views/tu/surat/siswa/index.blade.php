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
                    <p class="mb-0 opacity-75">Kelola dokumen siswa yang diajukan untuk divalidasi kepala sekolah</p>
                </div>
                <div class="badge bg-white text-primary px-3 py-2 fs-6">
                    <i class="bi bi-shield-check me-2"></i> Sistem Terhubung
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-end align-items-center mb-4">

        <a href="{{ route('tu.dokumen.create', 'siswa') }}" 
           class="btn btn-dark">
            <i class="bi bi-plus-lg me-1"></i>
            Tambah Dokumen
        </a>
    </div>
    

    {{-- ALERT SUCCESS --}}
    @if(session('success'))

        <div
            class="alert alert-success border-0 rounded-4 shadow-sm"
        >

            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

        </div>

    @endif

    {{-- ALERT ERROR --}}
    @if(session('error'))

        <div
            class="alert alert-danger border-0 rounded-4 shadow-sm"
        >

            <i class="bi bi-exclamation-circle-fill me-2"></i>

            {{ session('error') }}

        </div>

    @endif

    {{-- CARD --}}
    <div
        class="card border-0 shadow-sm"
    >

        {{-- TOP BAR --}}
        <div
            class="card-header bg-light border-0 p-4"
        >

            <div
                class="d-flex justify-content-between align-items-center flex-wrap gap-3"
            >

                <div>

                    <h5 class="fw-semibold mb-1">
                        Daftar Dokumen
                    </h5>

                    <span class="text-muted small">
                        Total:
                        {{ $dokumen->count() }}
                        dokumen
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

        {{-- TABLE --}}
        <div class="card-body">
            <div class="table-responsive">
                <table
                    class="table align-middle"
                >

                    <thead>

                        <tr>

                            <th>
                                No
                            </th>

                            <th>
                                Nama Dokumen
                            </th>

                            <th>
                                Kategori
                            </th>

                            <th>
                                Preview
                            </th>

                            <th>
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody id="tableDokumen">

                        @forelse($dokumen as $row)

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

                                    @if(
                                        pathinfo(
                                            $row->file,
                                            PATHINFO_EXTENSION
                                        ) === 'pdf'
                                    )

                                        <a
                                            href="{{ asset('uploads/dokumen/' . $row->file) }}"
                                            target="_blank"
                                            class="btn btn-light border rounded-3 btn-sm px-3"
                                        >

                                            <i class="bi bi-eye"></i>

                                        </a>

                                    @else

                                        <span class="text-muted small">
                                            Preview hanya untuk PDF
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
                                                    <a class="dropdown-item" href="{{ asset('uploads/dokumen/' . $row->file) }}" download>
                                                        <i class="bi bi-download me-2"></i> Download
                                                    </a>
                                                </li>

                                                
                                                <li><hr class="dropdown-divider"></li>

                                                <li>
                                                    <form action="{{ route('tu.dokumen.destroy', $row->id) }}"
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

                                <td
                                    colspan="5"
                                    class="text-center py-5 text-muted"
                                >

                                    <i
                                        class="bi bi-folder2-open d-block mb-2"
                                        style="font-size:40px;"
                                    ></i>

                                    Tidak ada dokumen siswa

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>
            </div>

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