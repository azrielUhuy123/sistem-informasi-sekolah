@extends('layouts.app')

@section('title', 'Validasi Surat')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">

                <div>
                    <h3 class="fw-bold mb-2">
                        Validasi Pengajuan Surat
                    </h3>

                    <p class="mb-0 opacity-75">
                        Kelola pengajuan surat dari Tata Usaha maupun Guru
                        untuk proses validasi Kepala Sekolah.
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
        title="Daftar Pengajuan Surat"
        subtitle="Seluruh pengajuan surat yang memerlukan proses validasi Kepala Sekolah"
    >

        <x-slot:badge>
            <span class="badge bg-primary rounded-pill px-2 py-1">
                {{ $pengajuan->count() }}
                <span class="text-badge">Pengajuan Surat</span>
            </span>
        </x-slot:badge>

        <x-slot:actions>

            <input
                type="text"
                id="searchInput"
                class="form-control table-search"
                placeholder="Cari dokumen..."
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
                            Pengirim
                        </th>

                        <th width="150">
                            Status
                        </th>

                        <th width="90">
                            Aksi
                        </th>

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

                        <tr>

                            <td
                                colspan="6"
                                class="text-center py-4"
                            >
                                Belum ada pengajuan surat
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