@extends('layouts.app')

@section('title', 'Nomor Surat')

@section('content')

@php

    $totalNomor = $nomorSurat->count();

@endphp

<div class="container-fluid py-4">

    {{-- HERO --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

        <div class="card-body p-4 p-lg-5 text-white"
            style="background:linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);">

            <div class="row align-items-center">

                <div class="col-lg-8">

                    <h2 class="fw-bold mb-2">

                        Nomor Surat

                    </h2>

                    <p class="mb-0 opacity-75">

                        Kelola nomor surat resmi yang telah diterbitkan oleh sekolah.

                    </p>

                </div>

                <div class="col-lg-4 mt-4 mt-lg-0">

                    <div class="bg-white rounded-4 text-center p-4">

                        <h3 class="fw-bold text-primary mb-1">

                            {{ $totalNomor }}

                        </h3>

                        <small class="text-muted">

                            Nomor Surat

                        </small>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- ALERT --}}
    @if(session('success'))

        <div class="alert alert-success border-0 shadow-sm rounded-4">

            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

        </div>

    @endif

    @if(session('error'))

        <div class="alert alert-danger border-0 shadow-sm rounded-4">

            <i class="bi bi-exclamation-triangle-fill me-2"></i>

            {{ session('error') }}

        </div>

    @endif

    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white border-0 py-4">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                <div>

                    <h5 class="fw-bold mb-1">

                        Daftar Nomor Surat

                    </h5>

                    <small class="text-muted">

                        Nomor surat yang telah dibuat dan digunakan.

                    </small>

                </div>

                <form
                    action="{{ route('guru.nomor-surat.generate') }}"
                    method="POST">

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-primary rounded-pill px-4">

                        <i class="bi bi-plus-circle-fill me-2"></i>

                        Generate Nomor

                    </button>

                </form>

            </div>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>

                        <tr>

                            <th width="70">

                                No

                            </th>

                            <th>

                                Nomor Surat

                            </th>

                            <th width="220">

                                Tanggal Dibuat

                            </th>

                            <th width="180">

                                Status

                            </th>

                        </tr>

                    </thead>

                <tbody>

    @forelse($nomorSurat as $i => $row)

        <tr>

            <td>

                <span class="badge bg-light text-dark rounded-pill px-3 py-2">

                    {{ $i + 1 }}

                </span>

            </td>

            <td>

                <div class="d-flex align-items-center">

                    <div class="number-icon me-3">

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

                <span class="text-muted">

                    <i class="bi bi-calendar-event me-1"></i>

                    {{ $row->created_at->translatedFormat('d F Y') }}

                </span>

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

                    <i class="bi bi-inbox display-3 text-secondary"></i>

                    <h5 class="fw-bold mt-3">

                        Belum Ada Nomor Surat

                    </h5>

                    <p class="text-muted mb-0">

                        Silakan generate nomor surat terlebih dahulu.

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

<style>

.card{

    transition:.25s;

}

.card:hover{

    box-shadow:0 18px 45px rgba(37,99,235,.08)!important;

}

.number-icon{

    width:50px;

    height:50px;

    border-radius:16px;

    background:linear-gradient(135deg,#2563eb,#0f4c81);

    color:#fff;

    display:flex;

    align-items:center;

    justify-content:center;

    font-size:1.25rem;

    flex-shrink:0;

}

.table thead th{

    background:#f8fafc;

    border:0;

    color:#64748b;

    text-transform:uppercase;

    font-size:.82rem;

    letter-spacing:.5px;

    padding:18px;

}

.table tbody td{

    padding:18px;

    vertical-align:middle;

    border-color:#eef2f7;

}

.table-hover tbody tr:hover{

    background:#f8fbff;

}

.badge{

    font-weight:600;

}

.btn{

    font-weight:600;

    transition:.2s;

}

.btn:hover{

    transform:translateY(-2px);

}

@media (max-width:768px){

    .number-icon{

        width:44px;

        height:44px;

        font-size:1rem;

    }

}

</style>

@endsection