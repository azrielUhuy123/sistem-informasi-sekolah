@extends('layouts.app')

@section('title', 'Nomor Surat')

@section('content')

<div class="container-fluid py-3">

    @php

        $total = $nomorSurat->count();

    @endphp

    {{-- ================= HERO ================= --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

        <div class="card-body p-4 p-lg-5 text-white"
             style="background:linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);">

            <div class="d-flex justify-content-between align-items-center flex-wrap">

                <div>

                    <h2 class="fw-bold mb-2">

                        Manajemen Nomor Surat

                    </h2>

                    <p class="mb-0 opacity-75">

                        Kelola seluruh nomor surat resmi sekolah yang telah diterbitkan
                        dan digunakan pada administrasi surat menyurat.

                    </p>

                </div>

                <div class="mt-4 mt-lg-0">

                    <form action="{{ route('superadmin.nomor-surat.generate') }}"
                          method="POST">

                        @csrf

                        <button
                            type="submit"
                            class="btn btn-light rounded-pill px-4 shadow-sm">

                            <i class="bi bi-plus-circle-fill me-2"></i>

                            Generate Nomor

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

    {{-- ALERT --}}
    @if(session('success'))

        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">

            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

        </div>

    @endif

    {{-- ================= SUMMARY ================= --}}
    <div class="row g-4 mb-4">

        <div class="col-md-4">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="summary-icon bg-primary-subtle text-primary me-3">

                            <i class="bi bi-hash"></i>

                        </div>

                        <div>

                            <small class="text-muted">

                                Total Nomor Surat

                            </small>

                            <h3 class="fw-bold mb-0">

                                {{ $total }}

                            </h3>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="summary-icon bg-success-subtle text-success me-3">

                            <i class="bi bi-patch-check-fill"></i>

                        </div>

                        <div>

                            <small class="text-muted">

                                Status

                            </small>

                            <h5 class="fw-bold mb-0">

                                Seluruh Nomor Aktif

                            </h5>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="summary-icon bg-warning-subtle text-warning me-3">

                            <i class="bi bi-calendar-event-fill"></i>

                        </div>

                        <div>

                            <small class="text-muted">

                                Terakhir Dibuat

                            </small>

                            <h6 class="fw-bold mb-0">

                                {{ $nomorSurat->first()?->created_at?->translatedFormat('d F Y') ?? '-' }}

                            </h6>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- ================= TABLE ================= --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex justify-content-between align-items-center flex-wrap">

                <div>

                    <h5 class="fw-bold mb-1">

                        Riwayat Nomor Surat

                    </h5>

                    <small class="text-muted">

                        Daftar seluruh nomor surat yang telah dibuat oleh sistem.

                    </small>

                </div>

            </div>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>

                        <tr>

                            <th width="70" class="text-center">

                                No

                            </th>

                            <th>

                                Nomor Surat

                            </th>

                            <th>

                                Tanggal Dibuat

                            </th>

                            <th width="180">

                                Status

                            </th>

                        </tr>

                    </thead>

                <tbody>

    @forelse($nomorSurat as $row)

        <tr>

            <td class="text-center">

                <span class="badge bg-light text-dark rounded-pill px-3 py-2">

                    {{ $loop->iteration }}

                </span>

            </td>

            <td>

                <div class="d-flex align-items-center">

                    <div class="surat-icon me-3">

                        <i class="bi bi-hash"></i>

                    </div>

                    <div>

                        <div class="fw-bold">

                            {{ $row->nomor_surat }}

                        </div>

                        <small class="text-muted">

                            Nomor Surat Resmi Sekolah

                        </small>

                    </div>

                </div>

            </td>

            <td>

                <div class="fw-semibold">

                    {{ $row->created_at->translatedFormat('d F Y') }}

                </div>

                <small class="text-muted">

                    {{ $row->created_at->format('H:i') }} WIB

                </small>

            </td>

            <td>

                <span class="badge bg-success rounded-pill px-3 py-2">

                    <i class="bi bi-check-circle-fill me-1"></i>

                    Digunakan

                </span>

            </td>

        </tr>

    @empty

        <tr>

            <td colspan="4">

                <div class="text-center py-5">

                    <i class="bi bi-folder2-open display-3 text-secondary"></i>

                    <h5 class="fw-bold mt-3">

                        Belum Ada Nomor Surat

                    </h5>

                    <p class="text-muted mb-0">

                        Klik tombol <strong>Generate Nomor</strong> untuk membuat nomor surat baru.

                    </p>

                </div>

            </td>

        </tr>

    @endforelse

</tbody>

</table>

</div>

</div>

</div>

</div>

<style>

.card{

    transition:.25s;

}

.card:hover{

    box-shadow:0 18px 45px rgba(37,99,235,.08)!important;

}

.summary-icon{

    width:60px;

    height:60px;

    border-radius:18px;

    display:flex;

    align-items:center;

    justify-content:center;

    font-size:1.35rem;

    flex-shrink:0;

}

.surat-icon{

    width:50px;

    height:50px;

    border-radius:16px;

    background:linear-gradient(135deg,#2563eb,#0f4c81);

    color:#fff;

    display:flex;

    align-items:center;

    justify-content:center;

    font-size:1.2rem;

    flex-shrink:0;

}

.table thead th{

    background:#f8fafc;

    color:#64748b;

    text-transform:uppercase;

    font-size:.82rem;

    letter-spacing:.5px;

    font-weight:700;

    border:0;

    padding:18px;

}

.table tbody td{

    padding:18px;

    vertical-align:middle;

    border-color:#eef2f7;

}

.table-hover tbody tr{

    transition:.2s;

}

.table-hover tbody tr:hover{

    background:#f8fbff;

}

.badge{

    font-weight:600;

    letter-spacing:.3px;

}

.btn{

    transition:.2s;

}

.btn:hover{

    transform:translateY(-2px);

}

.btn-primary{

    border:none;

    background:linear-gradient(135deg,#2563eb,#0f4c81);

}

@media(max-width:768px){

    .summary-icon{

        width:52px;

        height:52px;

        font-size:1.15rem;

    }

    .surat-icon{

        width:44px;

        height:44px;

        font-size:1rem;

    }

}

</style>

@endsection