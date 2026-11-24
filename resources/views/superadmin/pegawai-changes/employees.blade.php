@extends('layouts.app')

@section('title', 'Monitoring Data Pegawai')

@section('content')
<div class="container-fluid dt-siswa-page">
    <x-table.page-header icon="bi-person-lines-fill" eyebrow="Kepala Sekolah / Pegawai" title="Monitoring Data Pegawai" description="Pemantauan seluruh data dan riwayat perubahan; halaman ini tidak menyediakan approval kedua." />
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
                <a href="{{ route('superadmin.pegawai-changes.index') }}" class="btn btn-outline-secondary dt-toolbar__button">Semua Pengajuan</a>
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
                        <td data-dt-column="action"><a href="{{ route('superadmin.pegawai-changes.employee', $employee) }}">Lihat detail</a></td>
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
