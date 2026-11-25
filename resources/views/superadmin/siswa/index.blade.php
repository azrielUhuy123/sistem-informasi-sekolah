@extends('layouts.app')

@section('title', 'Manajemen Data Siswa')

@section('content')
<div class="container-fluid dt-siswa-page">
    <x-table.breadcrumb
        :items="[
            [
                'label' => 'Kepala Sekolah',                                
            ],
            [
                'label' => 'Master Data',                
            ],
            [
                'label' => 'Data Siswa',                
            ],
        ]"
    />
    <x-table.page-header        
        title=" Data Siswa"
        description="Telusuri data siswa, rombongan belajar, dan status keaktifan."
    />   

    <x-alert.flash key="success" type="success" />
    <x-alert.flash key="error" type="danger" />

    <x-table.card
        title="Daftar Siswa"
        subtitle="Informasi siswa dan penempatan aktif"
        :count="$siswa->total()"
        count-label="data"
    >
        <x-slot:toolbar>
            <x-table.toolbar
                table-id="dt-siswa-table"
                action="{{ route('superadmin.siswa.index') }}"
                :searchable="true"
                search-name="search"
                search-value="{{ request('search') }}"
                search-label="Cari nama atau NIS siswa"
                search-placeholder="Nama atau NIS"
                filter-name="status"
                filter-value="{{ request('status') }}"
                filter-label="Status"
                filter-menu-title="Filter status"
                :filter-options="[
                    ['value' => 'aktif', 'label' => 'Aktif'],
                    ['value' => 'nonaktif', 'label' => 'Nonaktif'],
                ]"
                :column-toggle="true"
                :refreshable="true"
                refresh-url="{{ request()->fullUrl() }}"
            >
                <x-slot:columnMenu>
                    @foreach ([
                        'nis' => 'NIS',
                        'name' => 'Siswa',
                        'nisn' => 'NISN',
                        'class' => 'Rombel',
                        'year' => 'Angkatan',
                        'status' => 'Status',
                        'action' => 'Aksi',
                    ] as $column => $label)
                        <label class="dt-column-menu__option">
                            <input type="checkbox" data-dt-column="{{ $column }}" checked>
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
                </x-slot:columnMenu>
            </x-table.toolbar>
        </x-slot:toolbar>

        <x-table.chips
            :criteria="[
                ['name' => 'search', 'label' => 'Search', 'value' => request('search')],
                [
                    'name' => 'status',
                    'label' => 'Status',
                    'value' => in_array(request('status'), ['aktif', 'nonaktif'], true) ? request('status') : null,
                    'display' => request('status') === 'aktif' ? 'Aktif' : 'Nonaktif',
                ],
            ]"
        />

        <p class="dt-siswa-scroll-hint" aria-hidden="true">
            <i class="bi bi-arrow-left-right me-1"></i>
            Geser tabel ke samping untuk melihat kolom lainnya.
        </p>

        <div
            class="table-responsive dt-siswa-table-wrap"
            role="region"
            aria-label="Daftar data siswa"
            tabindex="0"
        >
            <table id="dt-siswa-table" class="table table-hover align-middle dt-table dt-siswa-table">
                <caption class="visually-hidden">Data siswa, nomor induk, penempatan, status, dan tautan detail</caption>
                <colgroup>
                    <col class="dt-siswa-col-action" data-dt-column="nis">
                    <col class="dt-siswa-col-name" data-dt-column="name">
                    <col class="dt-siswa-col-nisn" data-dt-column="nisn">
                    <col class="dt-siswa-col-class" data-dt-column="class">
                    <col class="dt-siswa-col-year" data-dt-column="year">
                    <col class="dt-siswa-col-status" data-dt-column="status">
                    <col class="dt-siswa-col-action" data-dt-column="action">
                </colgroup>
                <thead>
                    <tr>
                        <x-table.heading column="nis">NIS</x-table.heading>
                        <x-table.heading width="21rem" column="name">Siswa</x-table.heading>
                        <x-table.heading width="8.5rem" column="nisn">NISN</x-table.heading>
                        <x-table.heading width="8rem" column="class">Rombel</x-table.heading>
                        <x-table.heading width="7.5rem" column="year">Angkatan</x-table.heading>
                        <x-table.heading width="8rem" column="status">Status</x-table.heading>
                        <x-table.heading width="7.5rem" column="action">Aksi</x-table.heading>
                    </tr>
                </thead>
                <tbody id="tableSiswa">
                    @forelse ($siswa as $row)
                        <tr>
                            <td class="dt-siswa-row-number" data-dt-column="nis">
                                {{ $row->nis }}
                            </td>
                            <th scope="row" class="dt-siswa-identity-cell" data-dt-column="name">
                                <div class="dt-siswa-identity">
                                    <span class="dt-siswa-avatar" aria-hidden="true">
                                        <i class="bi bi-person-fill"></i>
                                    </span>
                                    {{ $row->nama }}
                                </div>
                            </th>
                            <td class="dt-siswa-identifier" data-dt-column="nisn">{{ $row->nisn ?? '-' }}</td>
                            <td class="dt-siswa-class" data-dt-column="class">
                                {{ $row->penempatanAktif?->kelas?->nama_kelas_label ?? '-' }}
                            </td>
                            <td class="dt-siswa-year" data-dt-column="year">
                                {{ $row->penempatanAktif?->kelas?->kelasGrup?->angkatan?->tahun ?? '-' }}
                            </td>
                            <td data-dt-column="status">
                                @if ($row->trashed())
                                    <span class="badge-status nonaktif dt-siswa-status">                                        
                                        Nonaktif
                                    </span>
                                @else
                                    <span class="badge-status aktif dt-siswa-status">                                        
                                        Aktif
                                    </span>
                                @endif
                            </td>
                            <td data-dt-column="action">
                                @if ($row->trashed())
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-secondary dt-siswa-detail"
                                        disabled
                                        title="Detail tidak tersedia untuk siswa nonaktif"
                                    >
                                        Detail
                                    </button>
                                @else
                                    <a
                                        href="{{ route('superadmin.siswa.show', $row) }}"
                                        class="btn btn-sm btn-outline-primary dt-siswa-detail"
                                    >
                                        Detail
                                        <i class="bi bi-arrow-up-right" aria-hidden="true"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        @if (request()->filled('search') || request()->filled('status'))
                            <x-table.empty
                                :colspan="7"
                                state="no-results"
                                title="Tidak ada siswa yang cocok"
                                description="Ubah status atau kata kunci pencarian, lalu coba lagi."
                            />
                        @else
                            <x-table.empty
                                :colspan="7"
                                state="empty"
                                title="Belum ada data siswa"
                                description="Data siswa belum tersedia."
                            />
                        @endif
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="dt-siswa-pagination">
            <x-table.pagination :paginator="$siswa" :show-summary="true" />
        </div>
    </x-table.card>
</div>
@endsection
