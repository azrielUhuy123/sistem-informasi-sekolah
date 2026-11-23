@extends('layouts.app')

@section('title', 'Monitoring Ekskul')

@section('content')

<div class="container-fluid dt-siswa-page">

    <x-table.page-header
        icon="bi-activity"
        eyebrow="Monitoring / Ekstrakurikuler"
        title="Monitoring Ekskul"
        description="Pantau kegiatan ekstrakurikuler, pembina, dan proses monitoring secara terpusat."
    />

    {{-- TABLE --}}
    <x-table.card
        title="Daftar Ekstrakurikuler"
        subtitle="Pilih ekstrakurikuler untuk melihat hasil monitoring"
        :count="$ekskul->count()"
    >
        <x-slot:toolbar>
            <x-table.toolbar
                table-id="monitoring-ekskul-table"
                :column-toggle="true"
                :refreshable="true"
                :refresh-url="request()->fullUrl()"
            >
                <x-slot:columnMenu>
                    <label class="dt-column-menu__option">
                        <input type="checkbox" data-dt-column="nomor">
                        <span>No</span>
                    </label>
                    <label class="dt-column-menu__option">
                        <input type="checkbox" data-dt-column="ekskul">
                        <span>Ekstrakurikuler</span>
                    </label>
                    <label class="dt-column-menu__option">
                        <input type="checkbox" data-dt-column="pembina">
                        <span>Pembina</span>
                    </label>
                    <label class="dt-column-menu__option">
                        <input type="checkbox" data-dt-column="aksi">
                        <span>Aksi</span>
                    </label>
                </x-slot:columnMenu>
            </x-table.toolbar>
        </x-slot:toolbar>

        <div class="table-responsive dt-siswa-table-wrap">

            <table id="monitoring-ekskul-table" class="table table-hover align-middle mb-0 dt-table" data-dt-table>

                <thead>
                    <tr>
                        <x-table.heading column="nomor" width="70">No</x-table.heading>
                        <x-table.heading column="ekskul">Ekstrakurikuler</x-table.heading>
                        <x-table.heading column="pembina">Pembina</x-table.heading>
                        <x-table.heading column="aksi" width="140">Aksi</x-table.heading>
                    </tr>
                </thead>

                <tbody>

                    @forelse($ekskul as $e)

                        <tr>

                            {{-- NO --}}
                            <td data-dt-column="nomor">
                                <span class="badge bg-light text-dark rounded-circle">
                                    {{ $loop->iteration }}
                                </span>
                            </td>

                            {{-- EKSTRAKURIKULER --}}
                            <td data-dt-column="ekskul">
                                <span class="fw-semibold">
                                    {{ $e->nama }}
                                </span>                                                                        
                            </td>
                            {{-- PEMBINA --}}
                            <td data-dt-column="pembina">
                                @if($e->pembina)
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3"
                                            style="width: 48px; height: 48px;"
                                        >
                                            <i class="bi bi-person-fill fs-4"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">
                                                {{ $e->pembina->guru->pegawai?->nama ?? '-' }}
                                            </div>
                                            <small class="text-muted">
                                                Pembina Ekskul
                                            </small>
                                        </div>
                                    </div>
                                @else
                                    <span class="badge-status pending">
                                        Belum Ada Pembina
                                    </span>
                                @endif
                            </td>
                            {{-- AKSI --}}
                            <td data-dt-column="aksi">
                                <a
                                    href="{{ route('superadmin.monitoring-ekskul.show', $e->id) }}"
                                    class="btn btn-primary btn-sm rounded-pill px-3 py-0"
                                >
                                    Monitoring
                                    <i class="bi bi-chevron-right ms-1"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <i class="bi bi-inbox display-5 text-secondary"></i>
                                <p class="mt-3 mb-0 text-muted">
                                    Data ekstrakurikuler belum tersedia.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-table.card>
</div>
@endsection