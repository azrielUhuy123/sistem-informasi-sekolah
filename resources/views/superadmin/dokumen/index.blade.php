@extends('layouts.app')

@section('title', 'Template Dokumen')

@section('content')

<div class="container-fluid dt-siswa-page">

    <x-table.page-header
        icon="bi-file-earmark-text"
        eyebrow="Administrasi / Dokumen"
        title="Template Dokumen"
        description="Unduh template dokumen resmi sekolah untuk administrasi dan kebutuhan operasional."
    />

    {{-- ALERT --}}
    <x-alert.flash key="success" type="success"/>
    <x-alert.flash key="error" type="danger"/>

    {{-- TABLE --}}
    <x-table.card
        title="Arsip Template Dokumen"
        subtitle="Pilih kategori untuk mempermudah pencarian dokumen yang tersedia"
        :count="$dokumen->count()"
    >

        <x-slot:toolbar>
            <x-table.toolbar
                table-id="dt-superadmin-dokumen"
                action="{{ route('superadmin.dokumen.surat') }}"
                search-name="__dt_superadmin_dokumen_unused_search"
                filter-name="kategori"
                :filter-value="request('kategori')"
                filter-label="Kategori"
                filter-menu-title="Filter kategori"
                :filter-options="$kategori->map(fn ($item) => [
                    'value' => $item->kategori_surat,
                    'label' => $item->kategori_surat,
                ])->all()"
            >
                <x-slot:controls>
                    <label class="visually-hidden" for="searchInput">Cari dokumen pada daftar yang ditampilkan</label>
                    <input
                        type="text"
                        id="searchInput"
                        class="form-control table-search"
                        placeholder="Cari Dokumen..."
                        aria-label="Cari dokumen pada daftar yang ditampilkan"
                    >
                </x-slot:controls>
            </x-table.toolbar>
        </x-slot:toolbar>

        <x-table.chips
            :criteria="[
                ['name' => 'kategori', 'label' => 'Kategori', 'value' => request('kategori')],
            ]"
        />

        <div class="table-responsive dt-siswa-table-wrap">

            <table id="dt-superadmin-dokumen" class="table table-hover align-middle mb-0 dt-table">
                <caption class="visually-hidden">Arsip template dokumen sekolah dan tautan unduhan</caption>

                <thead>
                    <tr>
                        <x-table.heading width="70">
                            No
                        </x-table.heading>

                        <x-table.heading>
                            Nama Dokumen
                        </x-table.heading>

                        <x-table.heading width="170">
                            Kategori
                        </x-table.heading>

                        <x-table.heading width="120">
                            Aksi
                        </x-table.heading>
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

                        <x-table.empty
                            :colspan="4"
                            :state="request()->filled('kategori') ? 'no-results' : 'empty'"
                            title="Template dokumen belum tersedia"
                            description="Pilih kategori lain atau periksa kembali nanti."
                        />

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