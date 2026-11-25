@extends('layouts.app')

@section('title', 'Monitoring Pengajuan Perubahan Pegawai')

@section('content')
<div class="container-fluid dt-siswa-page">
    <x-table.breadcrumb
        :items="[
            [
                'label' => 'Kepala Sekolah',                                
            ],
            [
                'label' => 'Data Kepegawaian',
                'url' => route('superadmin.pegawai-changes.employees'),
            ],
            [
                'label' => 'Monitoring Perubahan Data Kepegawaian',
                'url' => route('superadmin.pegawai-changes.index'),
            ],
        ]"
    />
    <x-table.page-header icon="bi-arrow-repeat" eyebrow="Manajemen Kepegawaian" title="Monitoring Perubahan Data Kepegawaian" description="Pantau pengajuan perubahan data pegawai tanpa persetujuan tahap kedua." />
    <x-table.card title="Pengajuan Perubahan Data" subtitle="Informasi pengajuan perubahan data pada kepegawaian" :count="$requests->total()" count-label="pengajuan">
        <x-slot:headerActions>
            <nav class="dt-employee-navigation" aria-label="Navigasi Manajemen Kepegawaian">

                <a
                    href="{{ route('superadmin.pegawai-changes.employees') }}"
                    class="dt-employee-navigation__link {{ request()->routeIs('superadmin.pegawai-changes.employees') ? 'is-active' : '' }}"
                    @if (request()->routeIs('superadmin.pegawai-changes.employees')) aria-current="page" @endif
                >
                    Data Pegawai
                </a>

                <a
                    href="{{ route('superadmin.datakepegawaian.approval') }}"
                    class="dt-employee-navigation__link {{ request()->routeIs('superadmin.datakepegawaian.approval') ? 'is-active' : '' }}"
                    @if (request()->routeIs('superadmin.datakepegawaian.approval')) aria-current="page" @endif
                >
                    Pegawai Baru
                </a>

                <a
                    href="{{ route('superadmin.pegawai-changes.index') }}"
                    class="dt-employee-navigation__link {{ request()->routeIs('superadmin.pegawai-changes.index') ? 'is-active' : '' }}"
                    @if (request()->routeIs('superadmin.pegawai-changes.index')) aria-current="page" @endif
                >
                    Perubahan Data
                </a>

            </nav>
        </x-slot:headerActions>
        <x-slot:toolbar>
            <x-table.toolbar
                table-id="dt-superadmin-pegawai-changes"
                action="{{ route('superadmin.pegawai-changes.index') }}"
                :searchable="true"
                search-name="search"
                search-value="{{ $search }}"
                search-label="Cari pengajuan perubahan"
                search-placeholder="Nama pegawai, NIP, atau ID pengajuan"
                filter-name="status"
                :filter-value="$selectedStatuses"
                filter-label="Status"
                filter-menu-title="Filter status pengajuan"
                :filter-options="$statusOptions"
                :column-toggle="true"
                :refreshable="true"
                refresh-url="{{ request()->fullUrl() }}"
            >
                <x-slot:columnMenu>
                    @foreach ([
                        'created' => 'Tanggal',
                        'employee' => 'Pegawai',
                        'items' => 'Jumlah item',
                        'status' => 'Status',
                        'action' => 'Aksi',
                    ] as $column => $label)
                        <label class="dt-column-menu__option">
                            <input
                                type="checkbox"
                                data-dt-column="{{ $column }}"
                                checked
                            >
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
                </x-slot:columnMenu>
            </x-table.toolbar>
        </x-slot:toolbar>
        <x-table.chips
            :criteria="array_merge(
                [
                    [
                        'name' => 'search',
                        'label' => 'Search',
                        'value' => $search,
                    ],
                ],
                $statusChipCriteria
            )"
        />
    <div class="table-responsive dt-siswa-table-wrap">
        <table id="dt-superadmin-pegawai-changes" class="table table-hover align-middle dt-table">
            <colgroup>
                <col data-dt-column="created">
                <col data-dt-column="employee">
                <col data-dt-column="items">
                <col data-dt-column="status">
                <col data-dt-column="action">
            </colgroup>
            <thead><tr><th data-dt-column="created">Tanggal</th><th data-dt-column="employee">Pegawai</th><th data-dt-column="items">Jumlah item</th><th data-dt-column="status">Status</th><th data-dt-column="action">Aksi</th></tr></thead>
            <tbody>
                @forelse ($requests as $changeRequest)
                    <tr>
                        <td data-dt-column="created">{{ $changeRequest->created_at->format('d-m-Y H:i') }}</td>
                        <td data-dt-column="employee">{{ $changeRequest->pegawai->nama }}</td>
                        <td data-dt-column="items">{{ $changeRequest->items_count }}</td>
                        <td data-dt-column="status">{{ str_replace('_', ' ', ucfirst($changeRequest->status)) }}</td>
                        <td data-dt-column="action"><a class="btn btn-sm btn-outline-primary dt-siswa-detail" href="{{ route('superadmin.pegawai-changes.show', $changeRequest) }}">Pantau <i class="bi bi-activity"></i></a></td>
                    </tr>
                @empty
                    @if ($search !== '' || ! empty($selectedStatuses))
                        <x-table.empty
                            :colspan="5"
                            state="no-results"
                            title="Tidak ada pengajuan yang cocok"
                            description="Ubah status atau kata kunci pencarian, lalu coba lagi."
                        />
                    @else
                        <x-table.empty
                            :colspan="5"
                            state="empty"
                            title="Belum ada pengajuan perubahan data"
                            description="Pengajuan perubahan data pegawai belum tersedia."
                        />
                    @endif
                @endforelse
            </tbody>
        </table>
    </div>
        <x-table.pagination :paginator="$requests" :show-summary="true" />
    </x-table.card>
</div>
@endsection
