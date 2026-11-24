@extends('layouts.app')

@section('title', 'Monitoring Pengajuan Perubahan Pegawai')

@section('content')
<div class="container-fluid dt-siswa-page">
    <x-table.page-header icon="bi-arrow-repeat" eyebrow="Kepala Sekolah / Pegawai" title="Monitoring Pengajuan Perubahan Data" description="Kepala Sekolah memantau progres tanpa persetujuan tahap kedua." />
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
            <thead><tr><th data-dt-column="created">Tanggal</th><th data-dt-column="employee">Pegawai</th><th data-dt-column="items">Jumlah item</th><th data-dt-column="status">Status</th><th data-dt-column="action"></th></tr></thead>
            <tbody>
                @forelse ($requests as $changeRequest)
                    <tr>
                        <td data-dt-column="created">{{ $changeRequest->created_at->format('d-m-Y H:i') }}</td>
                        <td data-dt-column="employee">{{ $changeRequest->pegawai->nama }}</td>
                        <td data-dt-column="items">{{ $changeRequest->items->count() }}</td>
                        <td data-dt-column="status">{{ str_replace('_', ' ', ucfirst($changeRequest->status)) }}</td>
                        <td data-dt-column="action"><a href="{{ route('superadmin.pegawai-changes.show', $changeRequest) }}">Pantau</a></td>
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
