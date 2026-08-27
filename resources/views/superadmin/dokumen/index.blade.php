@extends('layouts.app')

@section('title', 'Template Dokumen')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">

                <div>
                    <h3 class="fw-bold mb-2">
                        Template Dokumen
                    </h3>

                    <p class="mb-0 opacity-75">
                        Download berbagai template dokumen resmi sekolah yang digunakan
                        untuk administrasi, surat menyurat, dan kebutuhan operasional.
                    </p>
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
        title="Arsip Template Dokumen"
        subtitle="Pilih kategori untuk mempermudah pencarian dokumen yang tersedia"
    >

        <x-slot:badge>
            <span class="badge bg-primary rounded-pill px-2 py-1">
                {{ $dokumen->count() }}
                <span class="text-badge">Dokumen</span>
            </span>
        </x-slot:badge>

        <x-slot:actions>

            <input
                type="text"
                id="searchInput"
                class="form-control table-search"
                placeholder="Cari Dokumen..."
            >

            <form
                method="GET"
                action="{{ route('superadmin.dokumen.surat') }}"
            >

                <select
                    name="kategori"
                    class="form-control"
                    onchange="this.form.submit()"
                >

                    <option value="">
                        Semua Kategori
                    </option>

                    @foreach($kategori as $item)

                        <option
                            value="{{ $item->kategori_surat }}"
                            {{ request('kategori') == $item->kategori_surat ? 'selected' : '' }}
                        >
                            {{ $item->kategori_surat }}
                        </option>

                    @endforeach

                </select>

            </form>

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

                        <th width="170">
                            Kategori
                        </th>

                        <th width="120">
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

                            {{-- NAMA DOKUMEN --}}
                            <td>

                                <div class="d-flex align-items-center">

                                    <div
                                        class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3"
                                        style="width: 48px; height: 48px;"
                                    >
                                        <i class="bi bi-file-earmark-text-fill fs-5"></i>
                                    </div>

                                    <div>

                                        <div class="fw-semibold">
                                            {{ $row->nama_surat }}
                                        </div>

                                    </div>

                                </div>

                            </td>

                            {{-- KATEGORI --}}
                            <td>

                                @if($row->kategori_surat)

                                    <span class="badge bg-teal rounded-pill px-3 py-2">
                                        {{ $row->kategori_surat }}
                                    </span>

                                @else

                                    <span class="text-muted">
                                        -
                                    </span>

                                @endif

                            </td>

                            {{-- AKSI --}}
                            <td>

                                <a
                                    href="{{ asset('uploads/dokumen/' . $row->file) }}"
                                    download
                                    class="btn btn-primary btn-sm rounded-pill px-3 py-0"
                                >
                                    Download
                                    <i class="bi bi-download ms-1"></i>
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="4" class="text-center py-5">

                                <i class="bi bi-folder-x display-5 text-secondary"></i>

                                <p class="mt-3 mb-0 text-muted">
                                    Template dokumen belum tersedia.
                                </p>

                            </td>

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
            document.querySelectorAll('#tableDokumen tr');

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