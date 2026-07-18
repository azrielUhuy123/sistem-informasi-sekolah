@extends('layouts.app')

@section('title', 'Dokumen Surat')

@section('content')

<div class="container-fluid py-3">

    @php

        $totalDokumen = $dokumen->count();

    @endphp

    {{-- ================= HERO ================= --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

        <div
            class="card-body p-4 p-lg-5 text-white"
            style="background:linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);"
        >

            <div class="row align-items-center">

                <div class="col-lg-8">

                    <h2 class="fw-bold mb-2">

                        Template Dokumen

                    </h2>

                    <p class="mb-0 opacity-75">

                        Download berbagai template dokumen resmi sekolah yang digunakan
                        untuk administrasi, surat menyurat, dan kebutuhan operasional.

                    </p>

                </div>

                <div class="col-lg-4 mt-4 mt-lg-0">

                    <div class="bg-white rounded-4 p-4 text-center">

                        <h2 class="fw-bold text-primary mb-1">

                            {{ $totalDokumen }}

                        </h2>

                        <small class="text-muted">

                            Total Dokumen

                        </small>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- ================= TABLE ================= --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                <div>

                    <h5 class="fw-bold mb-1">

                        Arsip Template Dokumen

                    </h5>

                    <small class="text-muted">

                        Pilih kategori untuk mempermudah pencarian dokumen yang tersedia.

                    </small>

                </div>

                <form
                    method="GET"
                    action="{{ route('superadmin.dokumen.surat') }}"
                    class="d-flex"
                >

                    <select
                        name="kategori"
                        class="form-select rounded-pill px-4"
                        onchange="this.form.submit()"
                    >

                        <option value="">

                            Semua Kategori

                        </option>

                        @foreach($kategori as $item)

                            <option
                                value="{{ $item->kategori_surat }}"
                                {{ request('kategori') == $item->kategori_surat ? 'selected' : '' }}
                            >

                                {{ $item->kategori_surat }}

                            </option>

                        @endforeach

                    </select>

                </form>

            </div>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>

                        <tr>

                            <th width="80" class="text-center">

                                No

                            </th>

                            <th>

                                Dokumen

                            </th>

                            <th width="170" class="text-center">

                                Aksi

                            </th>

                        </tr>

                    </thead>

                    <tbody>

    @forelse($dokumen as $row)

        <tr>

            <td class="text-center">

                <span class="badge bg-light text-dark rounded-pill px-3 py-2">

                    {{ $loop->iteration }}

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

                        @if(!empty($row->kategori_surat))

                            <span class="badge bg-primary-subtle text-primary mt-2 rounded-pill px-3 py-2">

                                {{ $row->kategori_surat }}

                            </span>

                        @endif

                    </div>

                </div>

            </td>

            <td class="text-center">

                <a
                    href="{{ asset('uploads/dokumen/'.$row->file) }}"
                    download
                    class="btn btn-success rounded-pill px-4"
                >

                    <i class="bi bi-cloud-arrow-down-fill me-2"></i>

                    Download

                </a>

            </td>

        </tr>

    @empty

        <tr>

            <td colspan="3">

                <div class="text-center py-5">

                    <i class="bi bi-folder2-open display-3 text-secondary"></i>

                    <h5 class="fw-bold mt-3">

                        Belum Ada Dokumen

                    </h5>

                    <p class="text-muted mb-0">

                        Template dokumen belum tersedia.

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

.document-icon{

    width:52px;

    height:52px;

    border-radius:16px;

    background:linear-gradient(135deg,#2563eb,#0f4c81);

    color:#fff;

    display:flex;

    align-items:center;

    justify-content:center;

    font-size:1.3rem;

    flex-shrink:0;

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

.btn-success{

    background:linear-gradient(135deg,#16a34a,#15803d);

    border:none;

}

.form-select{

    min-width:220px;

    border-radius:999px;

    border:1px solid #dbeafe;

    box-shadow:none;

}

.form-select:focus{

    border-color:#2563eb;

    box-shadow:0 0 0 .2rem rgba(37,99,235,.15);

}

@media(max-width:768px){

    .document-icon{

        width:46px;

        height:46px;

        font-size:1.1rem;

    }

}

</style>

@endsection