@extends('layouts.app')

@section('title', 'Pengajuan Surat')

@section('content')

@php

    $totalPengajuan = $pengajuan->count();

    $draft = $pengajuan->where('status','draft')->count();

    $disetujui = $pengajuan->where('status','disetujui')->count();

    $ditolak = $pengajuan->where('status','ditolak')->count();

@endphp

<div class="container-fluid py-4">

    {{-- HERO --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

        <div class="card-body p-4 p-lg-5 text-white"
             style="background:linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);">

            <div class="row align-items-center">

                <div class="col-lg-8">

                    <h2 class="fw-bold mb-2">

                        Pengajuan Surat

                    </h2>

                    <p class="mb-0 opacity-75">

                        Kelola seluruh pengajuan surat yang diajukan oleh guru serta pantau status persetujuannya.

                    </p>

                </div>

                <div class="col-lg-4">

                    <div class="bg-white rounded-4 text-center p-4">

                        <h3 class="fw-bold text-primary mb-1">

                            {{ $totalPengajuan }}

                        </h3>

                        <small class="text-muted">

                            Total Pengajuan

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

    {{-- SUMMARY --}}
    <div class="row g-3 mb-4">

        <div class="col-lg-3">

            <div class="summary-card">

                <div class="summary-icon bg-primary-subtle text-primary">

                    <i class="bi bi-file-earmark-text-fill"></i>

                </div>

                <h3>{{ $totalPengajuan }}</h3>

                <small>Total</small>

            </div>

        </div>

        <div class="col-lg-3">

            <div class="summary-card">

                <div class="summary-icon bg-secondary-subtle text-secondary">

                    <i class="bi bi-pencil-square"></i>

                </div>

                <h3>{{ $draft }}</h3>

                <small>Draft</small>

            </div>

        </div>

        <div class="col-lg-3">

            <div class="summary-card">

                <div class="summary-icon bg-success-subtle text-success">

                    <i class="bi bi-check-circle-fill"></i>

                </div>

                <h3>{{ $disetujui }}</h3>

                <small>Disetujui</small>

            </div>

        </div>

        <div class="col-lg-3">

            <div class="summary-card">

                <div class="summary-icon bg-danger-subtle text-danger">

                    <i class="bi bi-x-circle-fill"></i>

                </div>

                <h3>{{ $ditolak }}</h3>

                <small>Ditolak</small>

            </div>

        </div>

    </div>

    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white border-0 py-4">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                <div>

                    <h5 class="fw-bold mb-1">

                        Daftar Pengajuan Surat

                    </h5>

                    <small class="text-muted">

                        Kelola seluruh pengajuan surat yang telah dibuat.

                    </small>

                </div>

                <a href="{{ route('guru.pengajuan-surat.create') }}"
                    class="btn btn-primary rounded-pill px-4">

                    <i class="bi bi-plus-circle-fill me-2"></i>

                    Buat Pengajuan

                </a>

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

                                Nama Surat

                            </th>

                            <th width="220">

                                Kategori

                            </th>

                            <th width="170">

                                Status

                            </th>

                            <th width="260" class="text-center">

                                Aksi

                            </th>

                        </tr>

                    </thead>

                <tbody>

    @forelse($pengajuan as $i => $row)

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

                            Pengajuan Surat Guru

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

                @if($row->status == 'draft')

                    <span class="badge bg-secondary rounded-pill px-3 py-2">

                        <i class="bi bi-pencil-square me-1"></i>

                        Draft

                    </span>

                @elseif($row->status == 'disetujui')

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

            </td>

            <td>

                <div class="d-flex justify-content-center gap-2 flex-wrap">

                    <a href="{{ route('guru.pengajuan-surat.show',$row->id) }}"
                        class="btn btn-primary btn-sm rounded-pill">

                        <i class="bi bi-eye-fill"></i>

                    </a>

                    <a href="{{ route('guru.pengajuan-surat.edit',$row->id) }}"
                        class="btn btn-warning btn-sm rounded-pill">

                        <i class="bi bi-pencil-fill"></i>

                    </a>

                    <form
                        action="{{ route('guru.pengajuan-surat.destroy',$row->id) }}"
                        method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus pengajuan ini?')">

                        @csrf
                        @method('DELETE')

                        <button class="btn btn-danger btn-sm rounded-pill">

                            <i class="bi bi-trash-fill"></i>

                        </button>

                    </form>

                </div>

            </td>

        </tr>

    @empty

        <tr>

            <td colspan="5">

                <div class="text-center py-5">

                    <i class="bi bi-folder2-open display-3 text-secondary"></i>

                    <h5 class="fw-bold mt-3">

                        Belum Ada Pengajuan

                    </h5>

                    <p class="text-muted mb-0">

                        Silakan membuat pengajuan surat baru.

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

.summary-card{

    background:#fff;

    border-radius:20px;

    padding:24px;

    text-align:center;

    box-shadow:0 .125rem .5rem rgba(0,0,0,.05);

    transition:.25s;

    height:100%;

}

.summary-card:hover{

    transform:translateY(-4px);

}

.summary-icon{

    width:60px;

    height:60px;

    border-radius:18px;

    display:flex;

    align-items:center;

    justify-content:center;

    margin:0 auto 16px;

    font-size:1.35rem;

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