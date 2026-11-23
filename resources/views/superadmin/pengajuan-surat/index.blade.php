@extends('layouts.app')

@section('title', 'Validasi Surat')

@section('content')

<div class="container-fluid dt-siswa-page">

    <x-table.page-header
        icon="bi-envelope-check"
        eyebrow="Administrasi / Surat"
        title="Validasi Pengajuan Surat"
        description="Kelola pengajuan surat dari Tata Usaha dan Guru untuk proses validasi Kepala Sekolah."
    />

    {{-- ALERT --}}
    <x-alert.flash key="success" type="success"/>
    <x-alert.flash key="error" type="danger"/>

    {{-- TABLE --}}
    <x-table.card
        title="Daftar Pengajuan Surat"
        subtitle="Seluruh pengajuan surat yang memerlukan proses validasi Kepala Sekolah"
        :count="$pengajuan->count()"
    >

        <x-slot:toolbar>
            <x-table.local-toolbar
                search-id="searchInput"
                search-label="Cari dokumen"
                search-placeholder="Cari dokumen..."
            />
        </x-slot:toolbar>

        <div class="table-responsive dt-siswa-table-wrap">

            <table class="table table-hover align-middle mb-0 dt-table">
                <caption class="visually-hidden">Daftar pengajuan surat, pengirim, status validasi, dan tindakan</caption>

                <thead>
                    <tr>

                        <x-table.heading width="70">
                            No
                        </x-table.heading>

                        <x-table.heading>
                            Nama Dokumen
                        </x-table.heading>

                        <x-table.heading width="150">
                            Kategori
                        </x-table.heading>

                        <x-table.heading width="150">
                            Pengirim
                        </x-table.heading>

                        <x-table.heading width="150">
                            Status
                        </x-table.heading>

                        <x-table.heading width="90">
                            Aksi
                        </x-table.heading>

                    </tr>
                </thead>

                <tbody id="tablePengajuan">

                    @forelse($pengajuan as $row)

                        @php

                            $senderColor = strtolower($row->pengirim) == 'guru'
                                ? 'success'
                                : 'primary';

                        @endphp

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

                                    <div>

                                        <div class="fw-semibold">
                                            {{ $row->nama_surat }}
                                        </div>

                                    </div>

                                </div>

                            </td>

                            {{-- KATEGORI --}}
                            <td>

                                <span class="badge bg-teal rounded-pill px-3 py-2">
                                    {{ $row->kategori_surat }}
                                </span>

                            </td>

                            {{-- PENGIRIM --}}
                            <td>

                                <x-table.role-badge :role="$row->pengirim" />

                            </td>

                            {{-- STATUS --}}
                            <td>

                                @if($row->status == 'draft')

                                    <span class="badge-status pending">
                                        <i class="bi bi-hourglass-split me-2"></i>
                                        Validasi
                                    </span>

                                @elseif($row->status == 'disetujui')

                                    <span class="badge-status aktif">
                                        <i class="bi bi-check-circle-fill me-2"></i>
                                        Valid
                                    </span>

                                @else

                                    <span class="badge-status nonaktif">
                                        <i class="bi bi-x-circle-fill me-2"></i>
                                        Ditolak
                                    </span>

                                @endif

                            </td>

                            {{-- AKSI --}}
                            <td>
                                <a
                                    class="btn btn-primary btn-sm rounded-pill px-3 py-0"
                                    href="{{ route('superadmin.pengajuan-surat.show', $row->id) }}"
                                >                                    
                                    Validasi <i class="bi bi-chevron-right ms-1"></i>
                                </a>                                                       
                            </td>

                        </tr>

                    @empty

                        <x-table.empty
                            :colspan="6"
                            title="Belum ada pengajuan surat"
                            description="Pengajuan surat yang masuk akan tampil di sini."
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