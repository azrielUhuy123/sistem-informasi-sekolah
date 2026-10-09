@extends('layouts.app')

@section('title', 'Status Validasi')

@section('content')

<div class="container-fluid">

    @php

        $total = $jadwal->count();

    @endphp

    {{-- HEADER --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">

            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">

                <div>
                    <h3 class="fw-bold mb-2">
                        Status Validasi Jadwal Ekskul
                    </h3>

                    <p class="mb-0 opacity-75">
                        Pantau proses validasi jadwal ekstrakurikuler yang telah diajukan kepada Kepala Sekolah.
                    </p>
                </div>

                <div class="badge bg-white text-primary px-3 py-2 fs-6">
                    <i class="bi bi-shield-check me-2"></i>
                    Sinergi-MJ
                </div>

            </div>

        </div>
    </div>

    {{-- ALERT --}}
    <x-alert.flash key="success" type="success"/>
    <x-alert.flash key="error" type="danger"/>

    {{-- TABLE --}}
    <x-table.card
        title="Riwayat Validasi Jadwal Ekskul"
        subtitle="Status persetujuan setiap jadwal ekstrakurikuler."
    >

        <x-slot:badge>
            <span class="badge bg-primary rounded-pill px-2 py-1">
                {{ $total }}
                <span class="text-badge">Jadwal</span>
            </span>
        </x-slot:badge>

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead>
                    <tr>

                        <th>
                            Hari
                        </th>

                        <th>
                            Jam
                        </th>

                        <th>
                            Program
                        </th>

                        <th>
                            Status
                        </th>
                        <th>
                            Catatan
                        </th>

                    </tr>
                </thead>
                <tbody>
                    @forelse($jadwal as $item)                        
                        <tr>                            
                            <td>       
                                <span class="fw-semibold small text-muted">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    {{ $item->hari->nama ?? '-' }}
                                </span>                                                                                                                                     
                            </td>
                            
                            <td>                                
                                <div class="fw-semibold text-dark">
                                    {{ substr($item->jam_mulai,0,5) }}
                                    -
                                    {{ substr($item->jam_selesai,0,5) }}
                                </div>
                                <div class="small text-muted">Waktu pelaksanaan</div>
                            </td>
                            {{-- JAM --}}
                            <td> 
                                
                                <div class="d-flex align-items-center text-muted">
                                    <i class="bi bi-star-fill text-primary me-2"></i>
                                    {{ $item->ekskul->nama }}
                                </div>                                
                            </td>
                            {{-- STATUS --}}
                            <td>
                                @if($item->status === 'approved')                                        
                                        <span class="badge-status aktif">
                                            <i class="bi bi-check-circle-fill me-2"></i>
                                            Disetujui
                                        </span>
                                    @elseif($item->status === 'submitted')                                        
                                        <span class="badge-status pending">
                                            <i class="bi bi-hourglass-split me-2"></i>
                                            Pending
                                        </span>
                                    @elseif($item->status === 'rejected')                                        
                                        <span class="badge-status nonaktif">
                                            <i class="bi bi-x-circle-fill me-2"></i>
                                            Ditolak
                                        </span>
                                    @elseif($item->status === 'published')                                        
                                        <span class="badge-status verifikasi">
                                            <i class="bi bi-send-check-fill me-2"></i>
                                            Publish
                                        </span>
                                    @elseif($item->status === 'draft')                                        
                                        <span class="badge-status pending">
                                            <i class="bi bi-hourglass-split me-2"></i>
                                            Pending
                                        </span>
                                    @else
                                        <span class="badge-status draft">Draft</span>
                                    @endif
                            </td>                            
                            <td>
                                @if($item->catatan)
                                    <div class="note-box">
                                        <i class="bi bi-chat-left-text-fill me-2 text-danger"></i>
                                        {{ $item->catatan }}
                                    </div>
                                @else
                                    <span class="text-muted">
                                        -
                                    </span>
                                @endif
                            </td>                            
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="bi bi-clipboard-x display-5 text-secondary"></i>
                                <p class="mt-3 mb-0 text-muted">
                                    Belum ada data validasi jadwal.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-table.card>
</div>

@endsection