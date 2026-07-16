@extends('layouts.app')

@section('title', 'Status Validasi')

@section('content')

<div class="container-fluid py-4">

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2">Manajemen Status Validasi</h3>
                    <p class="mb-0 opacity-75">Kelola status validasi jadwal ekskul</p>
                </div>
                <div class="badge bg-white text-primary px-3 py-2 fs-6">
                    <i class="bi bi-shield-check me-2"></i> Sistem Terhubung
                </div>
            </div>
        </div>
    </div>


    {{-- ALERT SUCCESS --}}
    @if(session('success'))

        <div class="alert alert-success border-0 shadow-sm">

            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}

        </div>

    @endif

    {{-- ALERT ERROR --}}
    @if(session('error'))

        <div class="alert alert-danger border-0 shadow-sm">

            <i class="bi bi-exclamation-circle-fill me-2"></i>
            {{ session('error') }}

        </div>

    @endif

        
    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>

                        <tr>

                            <th width="60">

                                No

                            </th>

                            <th>

                                Hari

                            </th>

                            <th>

                                Jam

                            </th>

                            <th>

                                Status

                            </th>

                            <th>

                                Catatan Kepala Sekolah

                            </th>

                            <th width="120">

                                Aksi

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($jadwal as $i => $j)

                            <tr>

                                <td>

                                    {{ $loop->iteration }}

                                </td>

                                <td>

                                    {{ $j->hari->nama }}

                                </td>

                                <td>

                                    {{ substr($j->jam_mulai,0,5) }}
                                    -
                                    {{ substr($j->jam_selesai,0,5) }}

                                </td>

                                <td>

                                    @switch($j->status)

                                        @case('draft')

                                            <span class="badge bg-secondary">

                                                Draft

                                            </span>

                                        @break

                                        @case('submitted')

                                            <span class="badge bg-warning text-dark">

                                                Menunggu Validasi

                                            </span>

                                        @break

                                        @case('approved')

                                            <span class="badge bg-success">

                                                Disetujui

                                            </span>

                                        @break

                                        @case('published')

                                            <span class="badge bg-primary">

                                                Dipublish

                                            </span>

                                        @break

                                        @case('rejected')

                                            <span class="badge bg-danger">

                                                Ditolak

                                            </span>

                                        @break

                                    @endswitch

                                </td>

                                <td>

                                    @if($j->status == 'rejected')

                                        <div class="text-danger fw-semibold">

                                            {{ $j->catatan }}

                                        </div>

                                    @else

                                        <span class="text-muted">

                                            -

                                        </span>

                                    @endif

                                </td>

                                <td>

                                    @if($j->status == 'rejected')

                                        <a
                                            href="{{ route('guru.ekskul.jadwal.edit', $j->id) }}"
                                            class="btn btn-warning btn-sm"
                                        >

                                            Edit

                                        </a>

                                    @else

                                        <span class="text-muted">

                                            -

                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="text-center text-muted py-4"
                                >

                                    Belum ada pengajuan jadwal

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>
        </div>

    </div>

</div>

@endsection