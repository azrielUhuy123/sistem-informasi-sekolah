@extends('layouts.app')

@section('title', 'Monitoring Data Pegawai')

@section('content')
<div class="container-fluid dt-siswa-page">
    <nav aria-label="Breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">Kepala Sekolah</li>
            <li class="breadcrumb-item">Manajemen Kepegawaian</li>
            <li class="breadcrumb-item active" aria-current="page">Data Pegawai</li>
        </ol>
    </nav>
    <x-table.page-header icon="bi-person-lines-fill" eyebrow="Manajemen Kepegawaian" title="Data Pegawai" description="Master data pegawai beserta akses profil dan riwayat perubahan." />
    <x-table.card title="Data Pegawai" :count="$employees->total()" count-label="pegawai">
        <x-slot:toolbar>
            <x-table.local-toolbar
                search-id="searchMonitoringPegawai"
                search-label="Cari data pegawai"
                search-placeholder="Cari pegawai, NIP, atau jabatan..."
                table-id="dt-superadmin-monitoring-pegawai"
                :column-toggle="true"
                :refreshable="true"
                :refresh-url="request()->fullUrl()"
            >
                <x-slot:navigation>
                    <div class="nav nav-pills gap-1" role="tablist">
                        <a class="nav-link active" role="tab" aria-selected="true" href="{{ route('superadmin.pegawai-changes.employees') }}">Data Pegawai</a>
                        <a class="nav-link" role="tab" aria-selected="false" href="{{ route('superadmin.datakepegawaian.approval') }}">Pegawai Baru</a>
                        <a class="nav-link" role="tab" aria-selected="false" href="{{ route('superadmin.pegawai-changes.index') }}">Perubahan Data</a>
                    </div>
                </x-slot:navigation>
                <x-slot:columnMenu>
                    @foreach ([
                        'employee' => 'Pegawai',
                        'nip' => 'NIP',
                        'position' => 'Jabatan',
                        'history' => 'Riwayat perubahan',
                        'action' => 'Aksi',
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
        <table id="dt-superadmin-monitoring-pegawai" class="table table-hover align-middle dt-table">
            <colgroup>
                <col data-dt-column="employee">
                <col data-dt-column="nip">
                <col data-dt-column="position">
                <col data-dt-column="history">
                <col data-dt-column="action">
            </colgroup>
            <thead><tr><th data-dt-column="employee">Pegawai</th><th data-dt-column="nip">NIP</th><th data-dt-column="position">Jabatan</th><th data-dt-column="history">Riwayat perubahan</th><th data-dt-column="action"></th></tr></thead>
            <tbody>
                @forelse ($employees as $employee)
                    <tr>
                        <td data-dt-column="employee">{{ $employee->nama }}</td>
                        <td data-dt-column="nip">{{ $employee->nip ?? '—' }}</td>
                        <td data-dt-column="position">{{ $employee->jabatan ?? '—' }}</td>
                        <td data-dt-column="history">{{ $employee->pegawai_change_requests_count }}</td>
                        <td data-dt-column="action">
                            <div class="d-flex flex-wrap gap-2">
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('superadmin.datakepegawaian.show', $employee) }}">Profil</a>
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
