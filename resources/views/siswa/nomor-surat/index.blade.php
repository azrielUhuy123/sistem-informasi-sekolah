@extends('layouts.app')

@section('title', 'Nomor Surat')

@section('content')

<div class="container-fluid py-4">

    @php

        $totalNomor = $nomorSurat->count();

        $terakhir = $nomorSurat->first();

    @endphp

    {{-- ALERT --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4">

            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif

    {{-- ================= HERO ================= --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

        <div class="card-body p-4 p-lg-5 text-white"
             style="background:linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);">

            <div class="row align-items-center">

                <div class="col-lg-8">

                    <h2 class="fw-bold mb-2">

                        Nomor Surat

                    </h2>

                    <p class="mb-0 opacity-75">

                        Kelola dan lihat riwayat nomor surat yang telah dibuat
                        untuk keperluan administrasi siswa.

                    </p>

                </div>

                <div class="col-lg-4 mt-4 mt-lg-0">

                    <div class="row g-3">

                        <div class="col-6">

                            <div class="bg-white rounded-4 p-3 text-center">

                                <h4 class="fw-bold text-primary mb-1">

                                    {{ $totalNomor }}

                                </h4>

                                <small class="text-muted">

                                    Nomor Surat

                                </small>

                            </div>

                        </div>

                        <div class="col-6">

                            <div class="bg-white rounded-4 p-3 text-center">

                                <h4 class="fw-bold text-success mb-1">

                                    {{ $terakhir ? $terakhir->created_at->format('Y') : '-' }}

                                </h4>

                                <small class="text-muted">

                                    Tahun

                                </small>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- ================= TABLE ================= --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white border-0 py-4">

            <div class="d-flex justify-content-between align-items-center flex-wrap">

                <div>

                    <h5 class="fw-bold mb-1">

                        Riwayat Nomor Surat

                    </h5>

                    <small class="text-muted">

                        Seluruh nomor surat yang pernah dibuat.

                    </small>

                </div>

                <form
                    action="{{ route('siswa.nomor-surat.generate') }}"
                    method="POST">

                    @csrf

                    <button
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

                            <th width="70" class="text-center">

                                No

                            </th>

                            <th>

                                Nomor Surat

                            </th>

                            <th width="220">

                                Tanggal Dibuat

                            </th>

                            <th width="170">

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

                        <i class="bi bi-file-earmark-text-fill"></i>

                    </div>

                    <div>

                        <div class="fw-bold">

                            {{ $row->nomor_surat }}

                        </div>

                        <small class="text-muted">

                            Nomor Surat Resmi

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

                    <i class="bi bi-file-earmark-x display-3 text-secondary"></i>

                    <h5 class="fw-bold mt-3">

                        Belum Ada Nomor Surat

                    </h5>

                    <p class="text-muted mb-0">

                        Silakan generate nomor surat baru untuk mulai melakukan administrasi surat.

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

.table thead th{

    background:#f8fafc;

    color:#64748b;

    text-transform:uppercase;

    font-size:.82rem;

    font-weight:700;

    letter-spacing:.5px;

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

.badge{

    font-weight:600;

    letter-spacing:.3px;

}

.btn{

    font-weight:600;

}

.btn-primary{

    box-shadow:0 8px 20px rgba(37,99,235,.20);

}

.btn-primary:hover{

    transform:translateY(-2px);

}

.alert{

    border-radius:18px;

}

@media(max-width:768px){

    .surat-icon{

        width:42px;

        height:42px;

        font-size:1rem;

    }

}

</style>

@endsection