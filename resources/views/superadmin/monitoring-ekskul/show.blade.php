@extends('layouts.app')

@section('title', 'Monitoring Jadwal Ekskul')

@section('content')

<div class="container-fluid dt-siswa-page">

    <x-table.page-header
        icon="bi-calendar2-week"
        eyebrow="Monitoring / Ekstrakurikuler"
        title="Monitoring Jadwal Ekskul"
        :description="'Monitoring jadwal kegiatan ekstrakurikuler ' . $ekskul->nama . ', termasuk validasi, persetujuan, dan publikasi.'"
    />

    {{-- ALERT --}}
    <x-alert.flash key="success" type="success"/>
    <x-alert.flash key="error" type="danger"/>

    @php
        $totalJadwal = $jadwal->count();

        $totalAman = $jadwal
            ->where('bentrok', 'aman')
            ->count();

        $totalBentrok = $totalJadwal - $totalAman;

        $statusJadwal = $jadwal
            ->pluck('status')
            ->unique()
            ->implode(', ');
    @endphp

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body">
            <div class="d-flex flex-column flex-md-row justify-content-center justify-content-md-between align-items-center gap-3">                                               
                {{-- RIGHT --}}
                <div class="d-flex flex-wrap gap-2">
                    <div class="badge-status aktif">
                        <div class="me-2">
                            <span class="desc-add">Total Jadwal</span> Aman : 
                        </div>{{ $totalAman }}
                    </div> 
                    <div class="badge-status nonaktif">
                        <div class="me-2">
                            <span class="desc-add">Total Jadwal</span> Bentrok : 
                        </div>{{ $totalBentrok }}
                    </div>                          
                </div>
                
                <div class="d-flex flex-wrap justify-content-center gap-2"> 
                    @if($bisaApprove)                   
                        {{-- REJECT --}}
                        <button
                            type="button"
                            class="btn btn-danger btn-sm rounded-3"
                            data-bs-toggle="modal"
                            data-bs-target="#modalReject"
                        >
                            <i class="bi bi-x-circle me-1"></i>
                            Tolak
                        </button>
                        {{-- APPROVE --}}
                        <form
                            method="POST"
                            action="{{ route('superadmin.monitoring-ekskul.approve', $ekskul->id) }}"
                        >
                            @csrf
                            <button
                                class="btn btn-success btn-sm rounded-3 btn-confirm"
                                data-title="SETUJUI SELURUH JADWAL INI?"
                                data-message="Jadwal yang sudah disetujui tidak akan bisa diubah kembali"
                                data-button="btn-danger">                            
                                <i class="bi bi-check-circle me-1"></i>
                                Setujui
                            </button>
                        </form>                
                        <form
                            method="POST"
                            action="{{ route('superadmin.monitoring-ekskul.publish', $ekskul->id) }}"
                        >
                            @csrf
                            
                            <button
                                type="submit"
                                class="btn btn-primary btn-sm rounded-3 px-3">
                                <i class="bi bi-send-check-fill me-1"></i>
                                Publish
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- TABLE --}}
    <x-table.card
        title="Jadwal {{ $ekskul->nama }}"
        subtitle="Monitoring jadwal kegiatan ekstrakurikuler"
        :count="$totalJadwal"
        count-label="jadwal"
    >

        <x-slot:badge>
            <span class="badge bg-success rounded-pill px-2 py-1">
                {{ $totalAman }}
                <span class="text-badge">Aman</span>
            </span>

            <span class="badge bg-danger rounded-pill px-2 py-1">
                {{ $totalBentrok }}
                <span class="text-badge">Bentrok</span>
            </span>
        </x-slot:badge>

        <x-slot:toolbar>
            <x-table.toolbar
                table-id="monitoring-ekskul-schedule-table"
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
                        <input type="checkbox" data-dt-column="hari">
                        <span>Hari</span>
                    </label>
                    <label class="dt-column-menu__option">
                        <input type="checkbox" data-dt-column="jam_mulai">
                        <span>Jam Mulai</span>
                    </label>
                    <label class="dt-column-menu__option">
                        <input type="checkbox" data-dt-column="jam_selesai">
                        <span>Jam Selesai</span>
                    </label>
                    <label class="dt-column-menu__option">
                        <input type="checkbox" data-dt-column="status">
                        <span>Status</span>
                    </label>
                    <label class="dt-column-menu__option">
                        <input type="checkbox" data-dt-column="bentrok">
                        <span>Bentrok</span>
                    </label>
                    <label class="dt-column-menu__option">
                        <input type="checkbox" data-dt-column="catatan">
                        <span>Catatan</span>
                    </label>
                </x-slot:columnMenu>
            </x-table.toolbar>
        </x-slot:toolbar>
        
        {{-- WARNING BENTROK --}}
        @if($totalBentrok > 0)
            <div class="alert alert-danger mb-4">
                <strong>
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    Terdapat jadwal yang bentrok.
                </strong>
                <div class="mt-1">
                    Jadwal tidak dapat di-approve maupun dipublish
                    sebelum diperbaiki oleh Pembina Ekskul.
                </div>
            </div>
        @endif        

        {{-- TABLE --}}
        <div class="table-responsive dt-siswa-table-wrap">

            <table id="monitoring-ekskul-schedule-table" class="table table-hover align-middle mb-0 dt-table" data-dt-table>

                <thead>
                    <tr>
                        <x-table.heading column="nomor" width="70">No</x-table.heading>
                        <x-table.heading column="hari">Hari</x-table.heading>
                        <x-table.heading column="jam_mulai">Jam Mulai</x-table.heading>
                        <x-table.heading column="jam_selesai">Jam Selesai</x-table.heading>
                        <x-table.heading column="status" width="130">Status</x-table.heading>
                        <x-table.heading column="bentrok" width="170">Bentrok</x-table.heading>
                        <x-table.heading column="catatan">Catatan</x-table.heading>
                    </tr>
                </thead>

                <tbody>

                    @forelse($jadwal as $item)

                        <tr>

                            {{-- NO --}}
                            <td data-dt-column="nomor">
                                <span class="badge bg-light text-dark rounded-circle">
                                    {{ $loop->iteration }}
                                </span>
                            </td>

                            {{-- HARI --}}
                            <td data-dt-column="hari">
                                <span class="badge bg-light text-dark border">
                                    {{ $item->hari->nama ?? '-' }}
                                </span>
                            </td>

                            {{-- JAM MULAI --}}
                            <td data-dt-column="jam_mulai">
                                <span class="text-muted">
                                    {{ substr($item->jam_mulai, 0, 5) }}
                                </span>
                            </td>

                            {{-- JAM SELESAI --}}
                            <td data-dt-column="jam_selesai">
                                <span class="text-muted">
                                    {{ substr($item->jam_selesai, 0, 5) }}
                                </span>
                            </td>

                            {{-- STATUS --}}
                            <td data-dt-column="status">

                                @if($item->status == 'draft')

                                    <span class="badge-status draft">
                                        Draft
                                    </span>

                                @elseif($item->status == 'submitted')

                                    <span class="badge-status pending">
                                        <i class="bi bi-hourglass-split me-2"></i>
                                        Pending
                                    </span>

                                @elseif($item->status == 'approved')

                                    <span class="badge-status aktif">
                                        <i class="bi bi-check-circle-fill me-2"></i>
                                        Disetujui
                                    </span>

                                @elseif($item->status == 'published')

                                    <span class="badge-status verifikasi">
                                        <i class="bi bi-send-check-fill me-2"></i>
                                        Publish
                                    </span>

                                @elseif($item->status == 'rejected')

                                    <span class="badge-status nonaktif">
                                        <i class="bi bi-x-circle-fill me-2"></i>
                                        Ditolak
                                    </span>

                                @else                                    
                                    {{ ucfirst($item->status ?? '-') }}                                    
                                @endif

                            </td>

                            {{-- BENTROK --}}
                            <td data-dt-column="bentrok">

                                @if($item->bentrok == 'aman')

                                    <span class="text-success">
                                        <i class="bi bi-check-circle-fill me-1"></i>
                                        Aman
                                    </span>

                                @elseif($item->bentrok == 'akademik')

                                    <span class="text-danger">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                        Bentrok
                                    </span>

                                @elseif($item->bentrok == 'ekskul')

                                    <span class="text-danger">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                        Bentrok Ekskul
                                    </span>

                                @else

                                    <span class="badge bg-secondary rounded-pill px-3 py-2">
                                        -
                                    </span>

                                @endif

                            </td>

                            {{-- CATATAN --}}
                            <td data-dt-column="catatan">
                                {{ $item->catatan ?? '-' }}
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7">

                                <div class="alert alert-light text-muted mb-0">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Belum ada pengajuan jadwal.
                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- KEMBALI --}}
        <div class="d-flex justify-content-between align-items-center mt-4">

            <a
                href="{{ route('superadmin.monitoring-ekskul.index') }}"
                class="btn btn-outline-secondary rounded-3"
            >
                <i class="bi bi-chevron-left me-1"></i>
                Kembali
            </a>

        </div>

    </x-table.card>

</div>

{{-- MODAL REJECT --}}
<div
    class="modal fade"
    id="modalReject"
    tabindex="-1"
    aria-hidden="true"
>
    <div class="modal-dialog">

        <div class="modal-content border-0 shadow">

            <form
                method="POST"
                action="{{ route('superadmin.monitoring-ekskul.reject', $ekskul->id) }}"
            >

                @csrf

                <div class="modal-header">

                    <h5 class="modal-title fw-bold">
                        Tolak Jadwal Ekskul
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label
                            for="catatan"
                            class="form-label"
                        >
                            Alasan Penolakan
                        </label>

                        <textarea
                            name="catatan"
                            id="catatan"
                            rows="4"
                            class="form-control"
                            required
                        ></textarea>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="btn btn-danger"
                    >
                        Tolak Jadwal
                    </button>

                </div>

            </form>

        </div>

    </div>
</div>
@endsection