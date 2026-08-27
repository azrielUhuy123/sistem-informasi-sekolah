@extends('layouts.app')

@section('title', 'Dokumen Surat')

@section('content')

@php

    $totalDokumen = $dokumen->count();

@endphp

<div class="container-fluid py-4">

    {{-- HERO --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

        <div class="card-body p-4 p-lg-5 text-white"
             style="background:linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);">

            <div class="row align-items-center">

                <div class="col-lg-8">

                    <h2 class="fw-bold mb-2">

                        Arsip Dokumen

                    </h2>

                    <p class="mb-0 opacity-75">

                        Unduh dan akses seluruh dokumen resmi sekolah sesuai kategori yang tersedia.

                    </p>

                </div>

                <div class="col-lg-4 mt-4 mt-lg-0">

                    <div class="bg-white rounded-4 text-center p-4">

                        <h3 class="fw-bold text-primary mb-1">

                            {{ $totalDokumen }}

                        </h3>

                        <small class="text-muted">

                            Dokumen Tersedia

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

    {{-- FILTER --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-header bg-white border-0 py-3">

            <h5 class="fw-bold mb-0">

                Filter Dokumen

            </h5>

        </div>

        <div class="card-body">

            <form method="GET"
                  action="{{ route('guru.dokumen.surat') }}">

                <div class="row align-items-end">

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">

                            Kategori Dokumen

                        </label>

                        <select
                            name="kategori"
                            class="form-control"
                            onchange="this.form.submit()">

                            <option value="">

                                Semua Kategori

                            </option>

                            @foreach($kategori as $item)

                                <option
                                    value="{{ $item->kategori_surat }}"
                                    {{ request('kategori') == $item->kategori_surat ? 'selected' : '' }}>

                                    {{ $item->kategori_surat }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

            </form>

        </div>

    </div>

    {{-- TABLE --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white border-0 py-4">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="fw-bold mb-1">

                        Daftar Dokumen

                    </h5>

                    <small class="text-muted">

                        Klik tombol unduh untuk mengunduh dokumen yang dibutuhkan.

                    </small>

                </div>

                <span class="badge bg-primary rounded-pill px-2 py-1">

                    {{ $totalDokumen }} Dokumen

                </span>

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

                                Nama Dokumen

                            </th>

                            <th width="220">

                                Kategori

                            </th>

                            <th width="140" class="text-center">

                                Aksi

                            </th>

                        </tr>

                    </thead>

                    <tbody>

    @forelse($dokumen as $i => $row)

        <tr>

            <td>

                <span class="badge bg-light text-dark rounded-circle">

                    {{ $i + 1 }}

                </span>

            </td>

            <td>

                <div class="d-flex align-items-center">

                    <div class="document-icon me-3">

                        <i class="bi bi-file-earmark-text-fill"></i>

                    </div>

                    <div>

                        <div class="fw-bold">

                            {{ $row->nama_surat }}

                        </div>

                        <small class="text-muted">

                            Dokumen Sekolah

                        </small>

                    </div>

                </div>

            </td>

            <td>

                <span class="badge bg-info-subtle text-info border rounded-pill px-3 py-2">

                    {{ $row->kategori_surat }}

                </span>

            </td>

            <td>

                <a
                    href="{{ asset('uploads/dokumen/' . $row->file) }}"
                    download
                    class="btn btn-success rounded-pill px-3">

                    <i class="bi bi-cloud-arrow-down-fill me-1"></i>

                    Unduh

                </a>

            </td>

        </tr>

    @empty

        <tr>

            <td colspan="4">

                <div class="text-center py-5">

                    <i class="bi bi-folder2-open display-3 text-secondary"></i>

                    <h5 class="fw-bold mt-3">

                        Belum Ada Dokumen

                    </h5>

                    <p class="text-muted mb-0">

                        Belum terdapat dokumen yang dapat diunduh.

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

.document-icon{

    width:48px;

    height:48px;

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

.form-select{

    border-radius:12px;

}

.form-select:focus{

    border-color:#2563eb;

    box-shadow:0 0 0 .2rem rgba(37,99,235,.15);

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

@media(max-width:768px){

    .document-icon{

        width:42px;

        height:42px;

        font-size:1rem;

    }

}

</style>

@endsection