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

        <x-slot:pageActions>

            <form
                action="{{ route('superadmin.nomor-surat.generate') }}"
                method="POST"
            >

                @csrf

                <button
                    type="submit"
                    class="btn btn-primary rounded-3"
                >

                    <i class="bi bi-plus-circle-fill me-2"></i>

                    Generate Nomor

                </button>

            </form>

        </x-slot:pageActions>

        <div class="table-responsive dt-siswa-table-wrap">

            <table class="table table-hover align-middle mb-0 dt-table">
                <caption class="visually-hidden">Riwayat nomor surat resmi yang pernah dibuat</caption>

                <thead>

                    <tr>

                        <x-table.heading width="70" align="center">
                            No
                        </x-table.heading>

                        <x-table.heading>
                            Nomor Surat
                        </x-table.heading>

                        <x-table.heading width="220">
                            Tanggal Dibuat
                        </x-table.heading>

                        <x-table.heading width="170">
                            Status
                        </x-table.heading>

                    </tr>

                </thead>

                <tbody>

                    @forelse($nomorSurat as $item)

                        <tr>

                            {{-- NO --}}
                            <td>

                                <span class="badge bg-light text-dark rounded-circle">

                                    {{ $nomorSurat->firstItem() + $loop->index }}

                                </span>

                            </td>

                            {{-- NOMOR SURAT --}}
                            <td>

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
                            <td>

                                <div class="fw-semibold">

                                    {{ $item->created_at->translatedFormat('d F Y') }}

                                </div>

                                <small class="text-muted">

                                    {{ $item->created_at->format('H:i') }} WIB

                                </small>

                            </td>

                            {{-- STATUS --}}
                            <td>

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