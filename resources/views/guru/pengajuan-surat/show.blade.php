@extends('layouts.app')

@section('title', 'Detail Pengajuan Surat')

@section('content')

@php

    $statusClass = match($pengajuan->status){

        'draft' => 'secondary',

        'disetujui' => 'success',

        default => 'danger'

    };

    $statusIcon = match($pengajuan->status){

        'draft' => 'bi-pencil-square',

        'disetujui' => 'bi-check-circle-fill',

        default => 'bi-x-circle-fill'

    };

@endphp

<div class="container-fluid py-4">

    {{-- HERO --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

        <div class="card-body p-4 p-lg-5 text-white"
            style="background:linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);">

            <div class="row align-items-center">

                <div class="col-lg-8">

                    <h2 class="fw-bold mb-2">

                        Detail Pengajuan Surat

                    </h2>

                    <p class="mb-0 opacity-75">

                        Informasi lengkap mengenai pengajuan surat beserta status persetujuannya.

                    </p>

                </div>

                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">

                    <span class="badge bg-white text-{{ $statusClass }} rounded-pill px-4 py-3 fs-6">

                        <i class="bi {{ $statusIcon }} me-2"></i>

                        {{ ucfirst($pengajuan->status) }}

                    </span>

                </div>

            </div>

        </div>

    </div>

    <div class="row g-4">

        {{-- DETAIL --}}
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-header bg-white border-0 py-4">

                    <h5 class="fw-bold mb-0">

                        Informasi Pengajuan

                    </h5>

                </div>

                <div class="card-body">

            {{-- NAMA SURAT --}}
                                <div class="row g-4">

                        <div class="col-md-6">

                            <div class="info-card">

                                <small>Nama Surat</small>

                                <h6>{{ $pengajuan->nama_surat }}</h6>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="info-card">

                                <small>Kategori Surat</small>

                                <span class="badge bg-info-subtle text-info border rounded-pill px-3 py-2">

                                    {{ $pengajuan->kategori_surat }}

                                </span>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="info-card">

                                <small>Perihal</small>

                                <h6>{{ $pengajuan->perihal }}</h6>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="info-card">

                                <small>Tujuan Surat</small>

                                <h6>{{ $pengajuan->tujuan_surat }}</h6>

                            </div>

                        </div>

                        <div class="col-12">

                            <div class="info-card">

                                <small>Status Pengajuan</small>

                                <div class="mt-2">

                                    @if($pengajuan->status=='draft')

                                        <span class="badge bg-secondary rounded-pill px-3 py-2">

                                            <i class="bi bi-pencil-square me-1"></i>

                                            Draft

                                        </span>

                                    @elseif($pengajuan->status=='disetujui')

                                        <span class="badge bg-success rounded-pill px-3 py-2">

                                            <i class="bi bi-check-circle-fill me-1"></i>

                                            Disetujui

                                        </span>

                                    @else

                                        <span class="badge bg-danger rounded-pill px-3 py-2">

                                            <i class="bi bi-x-circle-fill me-1"></i>

                                            Ditolak

                                        </span>

                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- SIDEBAR --}}
        <div class="col-lg-4">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-header bg-white border-0 py-4">

                    <h5 class="fw-bold mb-0">

                        File Surat

                    </h5>

                </div>

                <div class="card-body">

                    <div class="text-center mb-4">

                        <div class="file-icon mx-auto mb-3">

                            <i class="bi bi-file-earmark-pdf-fill"></i>

                        </div>

                        <h6 class="fw-bold mb-1">

                            {{ $pengajuan->nama_surat }}

                        </h6>

                        <small class="text-muted">

                            Siap untuk dilihat atau diunduh

                        </small>

                    </div>

                    <div class="d-grid gap-2">

                        <a href="{{ asset('uploads/pengajuan-surat/'.$pengajuan->file_surat) }}"
                            target="_blank"
                            class="btn btn-primary rounded-pill">

                            <i class="bi bi-eye-fill me-2"></i>

                            Lihat Surat

                        </a>

                        <a href="{{ asset('uploads/pengajuan-surat/'.$pengajuan->file_surat) }}"
                            download
                            class="btn btn-success rounded-pill">

                            <i class="bi bi-download me-2"></i>

                            Download Surat

                        </a>

                        <a href="{{ route('guru.pengajuan-surat.index') }}"
                            class="btn btn-outline-secondary rounded-3 rounded-pill">

                            <i class="bi bi-arrow-left me-2"></i>

                            Kembali

                        </a>

                    </div>

                </div>

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

.info-card{

    background:#f8fafc;

    border-radius:18px;

    padding:18px;

    height:100%;

}

.info-card small{

    display:block;

    color:#64748b;

    margin-bottom:8px;

}

.info-card h6{

    margin:0;

    font-weight:700;

}

.file-icon{

    width:80px;

    height:80px;

    border-radius:24px;

    background:linear-gradient(135deg,#2563eb,#0f4c81);

    color:#fff;

    display:flex;

    align-items:center;

    justify-content:center;

    font-size:2rem;

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

</style>

@endsection