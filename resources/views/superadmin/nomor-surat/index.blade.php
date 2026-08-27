@extends('layouts.app')

@section('title', 'Nomor Surat')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">

            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">

                <div>

                    <h3 class="fw-bold mb-2">
                        Data Nomor Surat
                    </h3>

                    <p class="mb-0 opacity-75">
                        Kelola data nomor surat sekolah
                    </p>

                </div>

                <div class="badge bg-white text-primary px-3 py-2 fs-6">
                    <i class="bi bi-shield-check me-2"></i>
                    Sinergi-MJ
                </div>

            </div>

        </div>
    </div>

    {{-- ALERT --}}
    <x-alert.flash key="success" type="success"/>
    <x-alert.flash key="error" type="danger"/>

    {{-- TABLE --}}
    <x-table.card
        title="Riwayat Nomor Surat"
        subtitle="Seluruh nomor surat yang pernah dibuat."
    >

        <x-slot:badge>
            <span class="badge bg-primary rounded-pill px-2 py-1">
                {{ $nomorSurat->count() }}
                <span class="text-badge"> Nomor</span>
            </span>
        </x-slot:badge>

        <x-slot:actions>

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

        </x-slot:actions>

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead>

                    <tr>

                        <th width="70" class="text-center">
                            No
                        </th>

                        <th>
                            Nomor Surat
                        </th>

                        <th width="220">
                            Tanggal Dibuat
                        </th>

                        <th width="170">
                            Status
                        </th>

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

                        <tr>

                            <td colspan="4">

                                <div class="text-center py-5">

                                    <i class="bi bi-file-earmark-x display-3 text-secondary"></i>

                                    <h5 class="fw-bold mt-3">

                                        Belum Ada Nomor Surat

                                    </h5>

                                    <p class="text-muted mb-0">

                                        Silakan generate nomor surat baru untuk mulai melakukan administrasi surat.

                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- PAGINATION --}}
        <x-table.pagination :paginator="$nomorSurat" />

    </x-table.card>

</div>

@endsection