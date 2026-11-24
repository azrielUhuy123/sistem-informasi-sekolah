@extends('layouts.app')

@section('title', 'Nomor Surat')

@section('content')

<div class="container-fluid dt-siswa-page">

    <x-table.page-header
        icon="bi-envelope"
        eyebrow="Administrasi / Surat"
        title="Data Nomor Surat"
        description="Kelola dan pantau nomor surat resmi sekolah."
    />

    {{-- ALERT --}}
    <x-alert.flash key="success" type="success"/>
    <x-alert.flash key="error" type="danger"/>

    {{-- TABLE --}}
    <x-table.card
        title="Riwayat Nomor Surat"
        subtitle="Seluruh nomor surat yang pernah dibuat."
        :count="$nomorSurat->total()"
    >

        <x-slot:toolbar>
            <x-table.local-toolbar
                search-id="search-nomor-surat"
                :searchable="false"
                table-id="dt-superadmin-nomor-surat"
                :column-toggle="true"
                :refreshable="true"
                :refresh-url="request()->fullUrl()"
            >
                <form action="{{ route('superadmin.nomor-surat.generate') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary dt-toolbar__button">
                        <i class="bi bi-plus-circle-fill"></i>
                        Generate Nomor
                    </button>
                </form>
                <x-slot:columnMenu>
                    @foreach ([
                        'number' => 'No',
                        'letter' => 'Nomor Surat',
                        'created' => 'Tanggal Dibuat',
                        'status' => 'Status',
                    ] as $column => $label)
                        <label class="dt-column-menu__option">
                            <input type="checkbox" data-dt-column="{{ $column }}" checked>
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
                </x-slot:columnMenu>
            </x-table.local-toolbar>
        </x-slot:toolbar>

        <div class="table-responsive dt-siswa-table-wrap">

            <table id="dt-superadmin-nomor-surat" class="table table-hover align-middle mb-0 dt-table">
                <caption class="visually-hidden">Riwayat nomor surat resmi yang pernah dibuat</caption>
                <colgroup>
                    <col data-dt-column="number">
                    <col data-dt-column="letter">
                    <col data-dt-column="created">
                    <col data-dt-column="status">
                </colgroup>

                <thead>

                    <tr>

                        <x-table.heading width="70" align="center" column="number">
                            No
                        </x-table.heading>

                        <x-table.heading column="letter">
                            Nomor Surat
                        </x-table.heading>

                        <x-table.heading width="220" column="created">
                            Tanggal Dibuat
                        </x-table.heading>

                        <x-table.heading width="170" column="status">
                            Status
                        </x-table.heading>

                    </tr>

                </thead>

                <tbody>

                    @forelse($nomorSurat as $item)

                        <tr>

                            {{-- NO --}}
                            <td data-dt-column="number">

                                <span class="badge bg-light text-dark rounded-circle">

                                    {{ $nomorSurat->firstItem() + $loop->index }}

                                </span>

                            </td>

                            {{-- NOMOR SURAT --}}
                            <td data-dt-column="letter">

                                <div class="d-flex align-items-center">

                                    <div>

                                        <div class="fw-semibold">

                                            {{ $item->nomor_surat }}

                                        </div>

                                        <small class="text-muted">

                                            Nomor Surat Resmi

                                        </small>

                                    </div>

                                </div>

                            </td>

                            {{-- TANGGAL --}}
                            <td data-dt-column="created">

                                <div class="fw-semibold">

                                    {{ $item->created_at->translatedFormat('d F Y') }}

                                </div>

                                <small class="text-muted">

                                    {{ $item->created_at->format('H:i') }} WIB

                                </small>

                            </td>

                            {{-- STATUS --}}
                            <td data-dt-column="status">

                                <span class="badge-status aktif">

                                    <i class="bi bi-check-circle-fill me-2"></i>

                                    Digunakan

                                </span>

                            </td>

                        </tr>

                    @empty

                        <x-table.empty
                            :colspan="4"
                            title="Belum Ada Nomor Surat"
                            description="Silakan generate nomor surat baru untuk mulai melakukan administrasi surat."
                        />

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- PAGINATION --}}
        <x-table.pagination :paginator="$nomorSurat" :show-summary="true" />

    </x-table.card>

</div>

@endsection