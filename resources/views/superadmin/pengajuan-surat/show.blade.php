@extends('layouts.app')

@section('title', 'Detail Validasi Surat')

@section('content')

<div class="container-fluid py-3">

    {{-- ================= HERO ================= --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

        <div
            class="card-body p-4 p-lg-5 text-white"
            style="background:linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);"
        >

            <div class="d-flex justify-content-between align-items-center flex-wrap">

                <div>

                    <h2 class="fw-bold mb-2">

                        Detail Pengajuan Surat

                    </h2>

                    <p class="mb-0 opacity-75">

                        Informasi lengkap pengajuan surat beserta proses validasi
                        yang dilakukan oleh Kepala Sekolah.

                    </p>

                </div>

                <div class="mt-4 mt-lg-0">

                    <a
                        href="{{ route('superadmin.pengajuan-surat.index') }}"
                        class="btn btn-light rounded-pill px-4 shadow-sm"
                    >

                        <i class="bi bi-arrow-left me-2"></i>

                        Kembali

                    </a>

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

    {{-- ================= DETAIL ================= --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-4">

            <div class="row g-4">

                <div class="col-lg-6">

                    <div class="detail-card h-100">

                        <h5 class="section-title">

                            <i class="bi bi-file-earmark-text-fill me-2"></i>

                            Informasi Surat

                        </h5>

                        <table class="table table-borderless table-detail mb-0">

                            <tr>

                                <th width="40%">

                                    Nama Surat

                                </th>

                                <td>

                                    {{ $pengajuan->nama_surat }}

                                </td>

                            </tr>

                            <tr>

                                <th>

                                    Kategori

                                </th>

                                <td>

                                    <span class="badge bg-info-subtle text-info rounded-pill px-3 py-2">

                                        {{ $pengajuan->kategori_surat }}

                                    </span>

                                </td>

                            </tr>

                            <tr>

                                <th>

                                    Perihal

                                </th>

                                <td>

                                    {{ $pengajuan->perihal }}

                                </td>

                            </tr>

                            <tr>

                                <th>

                                    Tujuan Surat

                                </th>

                                <td>

                                    {{ $pengajuan->tujuan_surat }}

                                </td>

                            </tr>

                        </table>

                    </div>

                </div>

                <div class="col-lg-6">

                    <div class="detail-card h-100">

                        <h5 class="section-title">

                            <i class="bi bi-person-badge-fill me-2"></i>

                            Informasi Validasi

                        </h5>

                        <table class="table table-borderless table-detail mb-0">

                            <tr>

                                <th width="40%">

                                    Pengirim

                                </th>

                                <td>

                                    <span class="badge bg-primary rounded-pill px-3 py-2">

                                        {{ strtoupper($pengajuan->pengirim) }}

                                    </span>

                                </td>

                            </tr>

                            <tr>

                                <th>

                                    Status

                                </th>

                                <td>

                                    @if($pengajuan->status=='draft')

                                        <span class="badge bg-secondary rounded-pill px-3 py-2">

                                            Draft

                                        </span>

                                    @elseif($pengajuan->status=='pending')

                                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2">

                                            Pending

                                        </span>

                                    @elseif($pengajuan->status=='disetujui')

                                        <span class="badge bg-success rounded-pill px-3 py-2">

                                            Valid

                                        </span>

                                    @else

                                        <span class="badge bg-danger rounded-pill px-3 py-2">

                                            Ditolak

                                        </span>

                                    @endif

                                </td>

                            </tr>

                            @if($pengajuan->validated_by)

                                <tr>

                                    <th>

                                        Validator

                                    </th>

                                    <td>

                                        {{ $pengajuan->validator->name ?? '-' }}

                                    </td>

                                </tr>

                                <tr>

                                    <th>

                                        Tanggal Validasi

                                    </th>

                                    <td>

                                        {{ $pengajuan->validated_at }}

                                    </td>

                                </tr>

                            @endif

                        </table>

                    </div>

                </div>

            </div>

                        {{-- ================= FILE SURAT ================= --}}
            <div class="row mt-4">

                <div class="col-12">

                    <div class="detail-card">

                        <h5 class="section-title">

                            <i class="bi bi-paperclip me-2"></i>

                            Dokumen Surat

                        </h5>

                        <div class="d-flex justify-content-between align-items-center flex-wrap">

                            <div class="d-flex align-items-center mb-3 mb-lg-0">

                                <div class="file-icon me-3">

                                    <i class="bi bi-file-earmark-pdf-fill"></i>

                                </div>

                                <div>

                                    <div class="fw-bold">

                                        {{ $pengajuan->file_surat }}

                                    </div>

                                    <small class="text-muted">

                                        Dokumen surat yang diajukan untuk divalidasi.

                                    </small>

                                </div>

                            </div>

                            <div class="d-flex gap-2">

                                <a
                                    href="{{ asset('uploads/pengajuan-surat/'.$pengajuan->file_surat) }}"
                                    target="_blank"
                                    class="btn btn-primary rounded-pill px-4"
                                >

                                    <i class="bi bi-eye me-1"></i>

                                    Lihat

                                </a>

                                <a
                                    href="{{ asset('uploads/pengajuan-surat/'.$pengajuan->file_surat) }}"
                                    download
                                    class="btn btn-success rounded-pill px-4"
                                >

                                    <i class="bi bi-download me-1"></i>

                                    Download

                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            {{-- ================= VALIDASI ================= --}}
            @if($pengajuan->status=='pending' || $pengajuan->status=='draft')

                <div class="card border-0 bg-light rounded-4 mt-4">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center flex-wrap">

                            <div>

                                <h5 class="fw-bold mb-1">

                                    Validasi Pengajuan

                                </h5>

                                <p class="text-muted mb-0">

                                    Pilih tindakan untuk menyetujui atau menolak pengajuan surat.

                                </p>

                            </div>

                            <div class="d-flex gap-2 mt-3 mt-lg-0">

                                <form
                                    action="{{ route('superadmin.pengajuan.validasi',$pengajuan->id) }}"
                                    method="POST"
                                >

                                    @csrf
                                    @method('PATCH')

                                    <button
                                        type="submit"
                                        class="btn btn-success rounded-pill px-4"
                                    >

                                        <i class="bi bi-check-circle-fill me-1"></i>

                                        Validasi

                                    </button>

                                </form>

                                <form
                                    action="{{ route('superadmin.pengajuan.tolak',$pengajuan->id) }}"
                                    method="POST"
                                >

                                    @csrf
                                    @method('PATCH')

                                    <button
                                        type="submit"
                                        class="btn btn-danger rounded-pill px-4"
                                    >

                                        <i class="bi bi-x-circle-fill me-1"></i>

                                        Tolak

                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

            @endif

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

.detail-card{

    background:#fff;

    border:1px solid #eef2f7;

    border-radius:18px;

    padding:24px;

    height:100%;

}

.section-title{

    color:#2563eb;

    font-weight:700;

    margin-bottom:20px;

}

.table-detail th{

    width:40%;

    color:#64748b;

    font-weight:600;

    padding:.9rem 1rem;

    border-bottom:1px solid #eef2f7;

}

.table-detail td{

    padding:.9rem 1rem;

    color:#0f172a;

    border-bottom:1px solid #eef2f7;

}

.table-detail tr:last-child th,

.table-detail tr:last-child td{

    border-bottom:none;

}

.file-icon{

    width:58px;

    height:58px;

    border-radius:16px;

    background:linear-gradient(135deg,#dc2626,#ef4444);

    color:#fff;

    display:flex;

    align-items:center;

    justify-content:center;

    font-size:1.5rem;

    flex-shrink:0;

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

.badge{

    font-weight:600;

}

@media(max-width:768px){

    .file-icon{

        width:48px;

        height:48px;

        font-size:1.2rem;

    }

}

</style>

@endsection