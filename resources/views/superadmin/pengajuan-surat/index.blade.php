@extends('layouts.app')

@section('title', 'Validasi Surat')

@section('content')

<div class="container-fluid py-3">

    @php

        $total = $pengajuan->count();

        $pending = $pengajuan->where('status','pending')->count();

        $valid = $pengajuan->where('status','disetujui')->count();

        $ditolak = $pengajuan->where('status','ditolak')->count();

    @endphp

    {{-- ================= HERO ================= --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

        <div
            class="card-body p-4 p-lg-5 text-white"
            style="background:linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);"
        >

            <div class="row align-items-center">

                <div class="col-lg-7">

                    <h2 class="fw-bold mb-2">

                        Validasi Pengajuan Surat

                    </h2>

                    <p class="mb-0 opacity-75">

                        Kelola seluruh pengajuan surat dari Tata Usaha maupun Guru.
                        Lakukan pemeriksaan dokumen sebelum proses persetujuan.

                    </p>

                </div>

                <div class="col-lg-5 mt-4 mt-lg-0">

                    <div class="row g-3">

                        <div class="col-3">

                            <div class="bg-white rounded-4 p-3 text-center">

                                <h4 class="fw-bold text-primary mb-1">

                                    {{ $total }}

                                </h4>

                                <small class="text-muted">

                                    Total

                                </small>

                            </div>

                        </div>

                        <div class="col-3">

                            <div class="bg-white rounded-4 p-3 text-center">

                                <h4 class="fw-bold text-warning mb-1">

                                    {{ $pending }}

                                </h4>

                                <small class="text-muted">

                                    Pending

                                </small>

                            </div>

                        </div>

                        <div class="col-3">

                            <div class="bg-white rounded-4 p-3 text-center">

                                <h4 class="fw-bold text-success mb-1">

                                    {{ $valid }}

                                </h4>

                                <small class="text-muted">

                                    Valid

                                </small>

                            </div>

                        </div>

                        <div class="col-3">

                            <div class="bg-white rounded-4 p-3 text-center">

                                <h4 class="fw-bold text-danger mb-1">

                                    {{ $ditolak }}

                                </h4>

                                <small class="text-muted">

                                    Ditolak

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

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex justify-content-between align-items-center flex-wrap">

                <div>

                    <h5 class="fw-bold mb-1">

                        Daftar Pengajuan Surat

                    </h5>

                    <small class="text-muted">

                        Seluruh surat yang memerlukan proses validasi Kepala Sekolah.

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

                                Surat

                            </th>

                            <th>

                                Kategori

                            </th>

                            <th>

                                Pengirim

                            </th>

                            <th>

                                Status

                            </th>

                            <th width="140" class="text-center">

                                Aksi

                            </th>

                        </tr>

                    </thead>

                    <tbody>

    @forelse($pengajuan as $row)

        @php

            $statusColor = match($row->status){

                'draft' => 'secondary',

                'pending' => 'warning',

                'disetujui' => 'success',

                default => 'danger'

            };

            $statusIcon = match($row->status){

                'draft' => 'bi-pencil-square',

                'pending' => 'bi-hourglass-split',

                'disetujui' => 'bi-check-circle-fill',

                default => 'bi-x-circle-fill'

            };

            $statusLabel = match($row->status){

                'draft' => 'Draft',

                'pending' => 'Pending',

                'disetujui' => 'Valid',

                default => 'Ditolak'

            };

            $senderColor = strtolower($row->pengirim) == 'guru'
                ? 'success'
                : 'primary';

        @endphp

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

                            {{ $row->nama_surat }}

                        </div>

                        <small class="text-muted">

                            Dokumen Pengajuan

                        </small>

                    </div>

                </div>

            </td>

            <td>

                <span class="badge bg-info-subtle text-info rounded-pill px-3 py-2">

                    {{ $row->kategori_surat }}

                </span>

            </td>

            <td>

                <span class="badge bg-{{ $senderColor }} rounded-pill px-3 py-2">

                    <i class="bi bi-person-fill me-1"></i>

                    {{ strtoupper($row->pengirim) }}

                </span>

            </td>

            <td>

                <span class="badge bg-{{ $statusColor }} rounded-pill px-3 py-2">

                    <i class="bi {{ $statusIcon }} me-1"></i>

                    {{ $statusLabel }}

                </span>

            </td>

            <td class="text-center">

                <a
                    href="{{ route('superadmin.pengajuan-surat.show',$row->id) }}"
                    class="btn btn-primary btn-sm rounded-pill px-4"
                >

                    <i class="bi bi-eye-fill me-1"></i>

                    Detail

                </a>

            </td>

        </tr>

    @empty

        <tr>

            <td colspan="6">

                <div class="text-center py-5">

                    <i class="bi bi-envelope-x display-3 text-secondary"></i>

                    <h5 class="fw-bold mt-3">

                        Belum Ada Pengajuan Surat

                    </h5>

                    <p class="text-muted mb-0">

                        Belum terdapat surat yang diajukan.

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

    .surat-icon{

        width:44px;

        height:44px;

        font-size:1rem;

    }

}

</style>

@endsection