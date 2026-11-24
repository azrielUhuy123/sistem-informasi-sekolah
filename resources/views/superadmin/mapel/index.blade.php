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

            <p class="dt-siswa-page-description">
                Daftar dan informasi mata pelajaran sekolah.
            </p>
        </div>
    </header>

    <x-alert.flash key="success" type="success" />
    <x-alert.flash key="error" type="danger" />

    <x-table.card
        title="Daftar Mata Pelajaran"
        subtitle="Mata pelajaran yang terdata di sekolah"
        :count="$mata_pelajaran->total()"
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
                :column-toggle="true"
                :refreshable="true"
                :refresh-url="request()->fullUrl()"
            >
                <x-slot:columnMenu>
                    <label class="dt-column-menu__option">
                        <input
                            type="checkbox"
                            data-dt-column="number"
                            checked
                        >
                        <span>No</span>
                    </label>

                    <label class="dt-column-menu__option">
                        <input
                            type="checkbox"
                            data-dt-column="code"
                            checked
                        >
                        <span>Kode</span>
                    </label>

                    <label class="dt-column-menu__option">
                        <input
                            type="checkbox"
                            data-dt-column="name"
                            checked
                        >
                        <span>Nama Mata Pelajaran</span>
                    </label>
                </x-slot:columnMenu>
            </x-table.toolbar>
        </x-slot:toolbar>

        <x-table.chips
            :criteria="[
                [
                    'name' => 'q',
                    'label' => 'Search',
                    'value' => request('q'),
                ],
            ]"
        />

        <div
            class="table-responsive dt-siswa-table-wrap"
            role="region"
            aria-label="Daftar mata pelajaran"
            tabindex="0"
        >
            <table
                id="superadmin-mapel-table"
                class="table table-hover align-middle dt-table"
            >
                <caption class="visually-hidden">
                    Daftar mata pelajaran
                </caption>

                <colgroup>
                    <col data-dt-column="number">
                    <col data-dt-column="code">
                    <col data-dt-column="name">
                </colgroup>

                <thead>
                    <tr>
                        <x-table.heading
                            class="text-center"
                            column="number"
                        >
                            No
                        </x-table.heading>

                        <x-table.heading column="code">
                            Kode
                        </x-table.heading>

                        <x-table.heading column="name">
                            Nama Mata Pelajaran
                        </x-table.heading>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($mata_pelajaran as $row)
                        <tr>
                            <td
                                class="text-center text-body-secondary"
                                data-dt-column="number"
                            >
                                {{ $loop->iteration + ($mata_pelajaran->currentPage() - 1) * $mata_pelajaran->perPage() }}
                            </td>

                            <td
                                class="text-nowrap"
                                data-dt-column="code"
                            >
                                <span class="fw-semibold">
                                    {{ $row->kode_mapel ?? '-' }}
                                </span>
                            </td>

                            <td data-dt-column="name">
                                <span class="fw-semibold">
                                    {{ $row->nama_mapel }}
                                </span>
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

        <div class="dt-siswa-pagination">
            <x-table.pagination
                :paginator="$mata_pelajaran"
                :show-summary="true"
            />
        </div>
    </x-table.card>
</div>
@endsection