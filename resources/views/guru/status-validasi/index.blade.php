@extends('layouts.app')

@section('title', 'Status Validasi')

@section('content')

<div class="container-fluid py-4">

    @php

        $total = $jadwals->count();

        $approved = $jadwals->where('status','approved')->count();

        $submitted = $jadwals->where('status','submitted')->count();

        $published = $jadwals->where('status','published')->count();

        $rejected = $jadwals->where('status','rejected')->count();

    @endphp

    {{-- HERO --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

        <div class="card-body p-4 p-lg-5 text-white"
            style="background:linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);">

            <div class="row align-items-center">

                <div class="col-lg-7">

                    <h2 class="fw-bold mb-2">

                        Status Validasi Jadwal

                    </h2>

                    <p class="opacity-75 mb-0">

                        Pantau proses validasi jadwal pelajaran yang telah diajukan kepada Kepala Sekolah.

                    </p>

                </div>

                <div class="col-lg-5 mt-4 mt-lg-0">

                    <div class="row g-3">

                        <div class="col-6">

                            <div class="bg-white rounded-4 text-center p-3">

                                <h4 class="fw-bold text-primary mb-1">

                                    {{ $total }}

                                </h4>

                                <small class="text-muted">

                                    Total Jadwal

                                </small>

                            </div>

                        </div>

                        <div class="col-6">

                            <div class="bg-white rounded-4 text-center p-3">

                                <h4 class="fw-bold text-success mb-1">

                                    {{ $approved }}

                                </h4>

                                <small class="text-muted">

                                    Approved

                                </small>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- SUMMARY --}}
    <div class="row g-3 mb-4">

        <div class="col-lg-3 col-6">

            <div class="summary-card">

                <div class="summary-icon bg-warning-subtle text-warning">

                    <i class="bi bi-send-check-fill"></i>

                </div>

                <h3>{{ $submitted }}</h3>

                <small class="text-muted">

                    Submitted

                </small>

            </div>

        </div>

        <div class="col-lg-3 col-6">

            <div class="summary-card">

                <div class="summary-icon bg-success-subtle text-success">

                    <i class="bi bi-check-circle-fill"></i>

                </div>

                <h3>{{ $approved }}</h3>

                <small class="text-muted">

                    Approved

                </small>

            </div>

        </div>

        <div class="col-lg-3 col-6">

            <div class="summary-card">

                <div class="summary-icon bg-primary-subtle text-primary">

                    <i class="bi bi-megaphone-fill"></i>

                </div>

                <h3>{{ $published }}</h3>

                <small class="text-muted">

                    Published

                </small>

            </div>

        </div>

        <div class="col-lg-3 col-6">

            <div class="summary-card">

                <div class="summary-icon bg-danger-subtle text-danger">

                    <i class="bi bi-x-circle-fill"></i>

                </div>

                <h3>{{ $rejected }}</h3>

                <small class="text-muted">

                    Rejected

                </small>

            </div>

        </div>

    </div>

    {{-- ALERT SUCCESS --}}
    @if(session('success'))

        <div class="alert alert-success border-0 shadow-sm rounded-4">

            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

        </div>

    @endif

    {{-- ALERT ERROR --}}
    @if(session('error'))

        <div class="alert alert-danger border-0 shadow-sm rounded-4">

            <i class="bi bi-exclamation-triangle-fill me-2"></i>

            {{ session('error') }}

        </div>

    @endif

    {{-- TABLE --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white border-0 py-4">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="fw-bold mb-1">

                        Riwayat Validasi Jadwal

                    </h5>

                    <small class="text-muted">

                        Status persetujuan setiap jadwal pelajaran.

                    </small>

                </div>

                <span class="badge bg-primary rounded-pill px-3 py-2">

                    {{ $total }} Jadwal

                </span>

            </div>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>

                        <tr>

                            <th>Kelas</th>

                            <th>Hari</th>

                            <th>Jam</th>

                            <th>Mata Pelajaran</th>

                            <th>Status</th>

                            <th>Catatan</th>

                        </tr>

                    </thead>

                    <tbody>

    @forelse($jadwals as $item)

        @php

            switch($item->status){

                case 'submitted':
                    $badge='warning';
                    $icon='bi-send-check-fill';
                    $label='Submitted';
                    break;

                case 'approved':
                    $badge='success';
                    $icon='bi-check-circle-fill';
                    $label='Approved';
                    break;

                case 'rejected':
                    $badge='danger';
                    $icon='bi-x-circle-fill';
                    $label='Rejected';
                    break;

                case 'published':
                    $badge='primary';
                    $icon='bi-megaphone-fill';
                    $label='Published';
                    break;

                default:
                    $badge='secondary';
                    $icon='bi-pencil-square';
                    $label='Draft';
                    break;

            }

        @endphp

        <tr>

            <td>

                <div class="d-flex align-items-center">

                    <div class="table-icon me-3">

                        <i class="bi bi-door-open-fill"></i>

                    </div>

                    <div>

                        <div class="fw-bold">

                            {{ $item->kelas->nama_kelas_label ?? '-' }}

                        </div>

                        <small class="text-muted">

                            Kelas

                        </small>

                    </div>

                </div>

            </td>

            <td>

                <span class="badge bg-light text-dark rounded-pill px-3 py-2">

                    <i class="bi bi-calendar3 me-1"></i>

                    {{ $item->hari->nama ?? '-' }}

                </span>

            </td>

            <td>

                <div class="fw-bold">

                    Jam {{ $item->jamPelajaran->jam_ke }}

                </div>

                <small class="text-muted">

                    {{ substr($item->jamPelajaran->mulai,0,5) }}

                    -

                    {{ substr($item->jamPelajaran->selesai,0,5) }}

                </small>

            </td>

            <td>

                <div class="d-flex align-items-center">

                    <i class="bi bi-book-half text-primary me-2"></i>

                    <span class="fw-semibold">

                        {{ $item->mapel->nama_mapel ?? '-' }}

                    </span>

                </div>

            </td>

            <td>

                <span class="badge bg-{{ $badge }} rounded-pill px-3 py-2">

                    <i class="bi {{ $icon }} me-1"></i>

                    {{ $label }}

                </span>

            </td>

            <td>

                @if($item->catatan)

                    <div class="note-box">

                        <i class="bi bi-chat-left-text-fill me-2 text-danger"></i>

                        {{ $item->catatan }}

                    </div>

                @else

                    <span class="text-muted">

                        -

                    </span>

                @endif

            </td>

        </tr>

    @empty

        <tr>

            <td colspan="6">

                <div class="text-center py-5">

                    <i class="bi bi-clipboard-x display-3 text-secondary"></i>

                    <h5 class="fw-bold mt-3">

                        Belum Ada Data Validasi

                    </h5>

                    <p class="text-muted mb-0">

                        Jadwal yang diajukan akan muncul pada halaman ini setelah proses pengajuan dilakukan.

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

    box-shadow:0 16px 40px rgba(37,99,235,.10);

}

.summary-icon{

    width:60px;

    height:60px;

    border-radius:18px;

    display:flex;

    align-items:center;

    justify-content:center;

    margin:0 auto 15px;

    font-size:1.35rem;

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

    border-color:#eef2f7;

    vertical-align:middle;

}

.table-hover tbody tr{

    transition:.2s;

}

.table-hover tbody tr:hover{

    background:#f8fbff;

}

.table-icon{

    width:48px;

    height:48px;

    border-radius:15px;

    background:linear-gradient(135deg,#2563eb,#0f4c81);

    color:#fff;

    display:flex;

    align-items:center;

    justify-content:center;

    font-size:1.1rem;

    flex-shrink:0;

}

.note-box{

    background:#fff5f5;

    border-left:4px solid #dc3545;

    padding:10px 14px;

    border-radius:10px;

    font-size:.92rem;

}

.badge{

    font-weight:600;

    letter-spacing:.3px;

}

.alert{

    border-radius:18px;

}

@media(max-width:768px){

    .summary-card{

        padding:18px;

    }

    .summary-icon,

    .table-icon{

        width:45px;

        height:45px;

        font-size:1rem;

    }

}

</style>

@endsection