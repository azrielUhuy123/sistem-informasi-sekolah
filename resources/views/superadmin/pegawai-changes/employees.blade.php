@extends('layouts.app')

@section('title', 'Monitoring Data Pegawai')

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
                'label' => 'Monitoring Data Pegawai',
                'url' => route('superadmin.pegawai-changes.employees'),
            ],
        ]"
    />
    <x-table.page-header icon="bi-person-lines-fill" eyebrow="Manajemen Kepegawaian" title="Monitoring Data Pegawai" description="Master data pegawai beserta akses profil dan riwayat perubahan." />
    <x-table.card 
        title="Data Pegawai" 
        subtitle="Informasi data pegawai"
        :count="$employees->total()" 
        count-label="pegawai">
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
                table-id="dt-superadmin-monitoring-pegawai"
                :searchable="true"
                search-name="search"
                :search-value="$search"
                search-label="Cari data pegawai"
                search-placeholder="Cari pegawai, NIP, atau jabatan..."
                :filters="false"
                :filter-options="$jabatanOptions"
                filter-name="jabatan"
                filter-label="Filter"
                filter-menu-title="Jabatan"
                :column-toggle="true"
                :refreshable="true"
                :refresh-url="request()->fullUrl()"
            >               
                <x-slot:columnMenu>
                    @foreach ([
                        'nip' => 'NIP',
                        'employee' => 'Pegawai',
                        'position' => 'Jabatan',
                        'history' => 'Riwayat perubahan',
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
            :criteria="[
                [
                    'name' => 'search',
                    'label' => 'Search',
                    'value' => request('search'),
                ],
                [
                    'name' => 'jabatan',
                    'label' => 'Jabatan',
                    'value' => $selectedJabatan,
                ],
            ]"
        />



    <div class="table-responsive dt-siswa-table-wrap">
        <table id="dt-superadmin-monitoring-pegawai" class="table table-hover align-middle dt-table">
            <colgroup>
                <col data-dt-column="nip">
                <col data-dt-column="employee">                
                <col data-dt-column="position">
                <col data-dt-column="history">
                <col data-dt-column="action">
            </colgroup>
            <thead><tr><th data-dt-column="nip">NIP</th><th data-dt-column="employee">Pegawai</th><th data-dt-column="position">Jabatan</th><th data-dt-column="history">Riwayat perubahan</th><th data-dt-column="action">Aksi</th></tr></thead>
            <tbody>
                @forelse ($employees as $employee)
                    <tr>
                        <td data-dt-column="nip">{{ $employee->nip ?? '—' }}</td>
                        <td data-dt-column="employee">{{ $employee->nama }}</td>                        
                        <td data-dt-column="position">{{ $employee->jabatan ?? '—' }}</td>
                        <td data-dt-column="history">{{ $employee->pegawai_change_requests_count }}</td>
                        <td data-dt-column="action">
                            <div class="d-flex flex-wrap gap-2">
                                <a class="btn btn-sm btn-outline-primary dt-siswa-detail" href="{{ route('superadmin.datakepegawaian.show', [
                                        $employee,
                                        'from' => 'data-pegawai',
                                    ]) }}">Detail <i class="bi bi-arrow-up-right" aria-hidden="true"></i></a>
                                <a class="btn btn-sm btn-outline-secondary" href="{{ route('superadmin.pegawai-changes.employee', $employee) }}">Riwayat</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <x-table.empty :colspan="5" title="Belum ada data pegawai." />
                @endforelse
            </tbody>
        </table>
    </div>
        <x-table.pagination :paginator="$employees" :show-summary="true" />
    </x-table.card>
</div>
@endsection
