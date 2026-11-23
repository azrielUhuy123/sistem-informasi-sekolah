@extends('layouts.app')

@section('title', 'Mata Pelajaran')

@section('content')

<div class="container-fluid dt-siswa-page">
    <header class="dt-siswa-page-header">
        <div>
            <p class="dt-siswa-eyebrow">
                <i class="bi bi-book-half" aria-hidden="true"></i>
                Master Data / Mata Pelajaran
            </p>
            <h1 class="dt-siswa-page-title">Mata Pelajaran</h1>
            <p class="dt-siswa-page-description">Daftar dan informasi mata pelajaran sekolah.</p>
        </div>
    </header>

    {{-- ================= ALERT ================= --}}
    <x-alert.flash key="success" type="success" />
    <x-alert.flash key="error" type="danger" />

    {{-- ================= TABEL ================= --}}
    <x-table.card
        title="Daftar Mata Pelajaran"
        subtitle="Mata pelajaran yang terdata di sekolah"
        :count="$mata_pelajaran->count()"
        count-label="data"
    >

        <x-slot:toolbar>
            <x-table.toolbar
                table-id="superadmin-mapel-table"
                :action="route('superadmin.mapel.index')"
                :searchable="true"
                search-name="q"
                :search-value="request('q')"
                search-label="Cari mata pelajaran"
                search-placeholder="Cari nama atau kode mapel..."
                filter-name="deleted"
                :filter-value="$deleted === 'active' ? 'active' : ''"
                filter-label="Status"
                filter-menu-title="Filter status"
                :filter-options="[
                    ['value' => 'active', 'label' => 'Aktif'],
                ]"
            >
            </x-table.toolbar>
        </x-slot:toolbar>

        <x-table.chips :criteria="[
            ['name' => 'q', 'label' => 'Search', 'value' => request('q')],
            [
                'name' => 'deleted',
                'label' => 'Status',
                'value' => $deleted === 'active' ? $deleted : null,
                'display' => 'Aktif',
                'removeValue' => 'all',
            ],
        ]" :clear-all-values="['deleted' => 'all']" />

        {{-- ================= TABLE ================= --}}
        <div
            class="table-responsive dt-siswa-table-wrap"
            role="region"
            aria-label="Daftar mata pelajaran"
            tabindex="0"
        >
            <table id="superadmin-mapel-table" class="table table-hover align-middle dt-table">
                <caption class="visually-hidden">Daftar mata pelajaran</caption>

                <thead>
                    <tr>
                        <x-table.heading class="text-center">No</x-table.heading>
                        <x-table.heading>Kode</x-table.heading>
                        <x-table.heading>Nama Mata Pelajaran</x-table.heading>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($mata_pelajaran as $row)
                        <tr>
                            <td class="text-center text-body-secondary">
                                {{ $loop->iteration }}
                            </td>
                            <td class="text-nowrap">
                                <span class="fw-semibold">
                                    {{ $row->kode_mapel ?? '-' }}
                                </span>
                            </td>
                            <td>
                                <span class="fw-semibold">{{ $row->nama_mapel }}</span>
                            </td>
                        </tr>
                    @empty
                        @if (request()->filled('q'))
                            <x-table.empty
                                :colspan="3"
                                state="no-results"
                                title="Tidak ada mata pelajaran yang cocok"
                                description="Ubah kata kunci pencarian, lalu coba lagi."
                            />
                        @else
                            <x-table.empty
                                :colspan="3"
                                state="empty"
                                title="Belum ada data mata pelajaran"
                                description="Data mata pelajaran belum tersedia."
                            />
                        @endif
                    @endforelse
                </tbody>
            </table>
        </div>

    </x-table.card>

</div>

@endsection