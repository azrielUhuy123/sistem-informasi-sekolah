@extends('layouts.app')

@section('title', 'Monitoring Pengajuan Perubahan Pegawai')

@section('content')
<div class="container-fluid dt-siswa-page">
    <nav aria-label="Breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">Kepala Sekolah</li>
            <li class="breadcrumb-item">Manajemen Kepegawaian</li>
            <li class="breadcrumb-item active" aria-current="page">Perubahan Data</li>
        </ol>
    </nav>
    <x-table.page-header icon="bi-arrow-repeat" eyebrow="Manajemen Kepegawaian" title="Perubahan Data" description="Pantau pengajuan perubahan data pegawai tanpa persetujuan tahap kedua." />
    <x-table.card title="Pengajuan Perubahan Data" :count="$requests->total()" count-label="pengajuan">
        <x-slot:toolbar>
            <x-table.local-toolbar
                search-id="searchPegawaiChanges"
                search-label="Cari pengajuan perubahan"
                search-placeholder="Cari pegawai atau status..."
                table-id="dt-superadmin-pegawai-changes"
                :column-toggle="true"
                :refreshable="true"
                :refresh-url="request()->fullUrl()"
            >
                <div class="nav nav-pills gap-1" role="tablist">
                    <a class="btn btn-outline-secondary dt-toolbar__button" role="tab" aria-selected="true" href="{{ route('superadmin.pegawai-changes.employees') }}">Data Pegawai</a>
                    <a class="btn btn-outline-secondary dt-toolbar__button" role="tab" aria-selected="false" href="{{ route('superadmin.datakepegawaian.approval') }}">Pegawai Baru</a>
                    <a class="btn btn-primary dt-toolbar__button" role="tab" aria-selected="false" href="{{ route('superadmin.pegawai-changes.index') }}">Perubahan Data</a>
                </div>  
                <x-slot:columnMenu>
                    @foreach ([
                        'created' => 'Tanggal',
                        'employee' => 'Pegawai',
                        'items' => 'Jumlah item',
                        'status' => 'Status',
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
                        <td data-dt-column="items">{{ $changeRequest->items->count() }}</td>
                        <td data-dt-column="status">{{ str_replace('_', ' ', ucfirst($changeRequest->status)) }}</td>
                        <td data-dt-column="action"><a class="btn btn-sm btn-outline-primary dt-siswa-detail" href="{{ route('superadmin.pegawai-changes.show', $changeRequest) }}">Pantau <i class="bi bi-activity"></i></a></td>
                    </tr>
                @empty
                    <x-table.empty :colspan="5" title="Belum ada pengajuan perubahan data." />
                @endforelse
            </tbody>
        </table>
    </div>
        <x-table.pagination :paginator="$requests" :show-summary="true" />
    </x-table.card>
</div>
@endsection
