@extends('layouts.app')

@section('title', 'Absensi Ekskul')

@section('content')

<div class="container-fluid py-4">

    @php

        $totalAbsensi = $hadir + $izin + $sakit + $alpa;

        $persentase = $totalAbsensi
            ? round(($hadir / $totalAbsensi) * 100)
            : 0;

    @endphp

    {{-- ================= HERO ================= --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

        <div class="card-body p-4 p-lg-5 text-white"
             style="background:linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);">

            <div class="row align-items-center">

                <div class="col-lg-8">

                    <h2 class="fw-bold mb-2">

                        Absensi Ekstrakurikuler

                    </h2>

                    <p class="mb-0 opacity-75">

                        Riwayat kehadiran kegiatan

                        <strong>{{ $ekskul->nama }}</strong>

                        selama mengikuti ekstrakurikuler.

                    </p>

                </div>

                <div class="col-lg-4 mt-4 mt-lg-0">

                    <div class="row g-3">

                        <div class="col-6">

                            <div class="bg-white rounded-4 p-3 text-center">

                                <h4 class="fw-bold text-primary mb-1">

                                    {{ $persentase }}%

                                </h4>

                                <small class="text-muted">

                                    Kehadiran

                                </small>

                            </div>

                        </div>

                        <div class="col-6">

                            <div class="bg-white rounded-4 p-3 text-center">

                                <h4 class="fw-bold text-success mb-1">

                                    {{ $totalAbsensi }}

                                </h4>

                                <small class="text-muted">

                                    Total Data

                                </small>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- ================= SUMMARY ================= --}}
    <div class="row g-4 mb-4">

        <div class="col-md-3">

            <div class="summary-card">

                <div class="summary-icon bg-success-subtle text-success">

                    <i class="bi bi-check-circle-fill"></i>

                </div>

                <small class="text-muted">

                    Hadir

                </small>

                <h3 class="fw-bold text-success mb-0">

                    {{ $hadir }}

                </h3>

            </div>

        </div>

        <div class="col-md-3">

            <div class="summary-card">

                <div class="summary-icon bg-primary-subtle text-primary">

                    <i class="bi bi-calendar-check-fill"></i>

                </div>

                <small class="text-muted">

                    Izin

                </small>

                <h3 class="fw-bold text-primary mb-0">

                    {{ $izin }}

                </h3>

            </div>

        </div>

        <div class="col-md-3">

            <div class="summary-card">

                <div class="summary-icon bg-warning-subtle text-warning">

                    <i class="bi bi-heart-pulse-fill"></i>

                </div>

                <small class="text-muted">

                    Sakit

                </small>

                <h3 class="fw-bold text-warning mb-0">

                    {{ $sakit }}

                </h3>

            </div>

        </div>

        <div class="col-md-3">

            <div class="summary-card">

                <div class="summary-icon bg-danger-subtle text-danger">

                    <i class="bi bi-x-circle-fill"></i>

                </div>

                <small class="text-muted">

                    Alpa

                </small>

                <h3 class="fw-bold text-danger mb-0">

                    {{ $alpa }}

                </h3>

            </div>

        </div>

    </div>

    {{-- ================= TABLE ================= --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white border-0 py-4 d-flex justify-content-between align-items-center flex-wrap">

            <div>

                <h5 class="fw-bold mb-1">

                    Riwayat Kehadiran

                </h5>

                <small class="text-muted">

                    Daftar seluruh absensi ekstrakurikuler yang telah tercatat.

                </small>

            </div>

            <span class="badge bg-primary rounded-pill px-3 py-2">

                {{ $absensi->total() }} Data

            </span>

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

                                Tanggal

                            </th>

                            <th width="180">

                                Status

                            </th>

                        </tr>

                    </thead>

                    <tbody>

    @forelse($absensi as $item)

        @php

            switch($item->status){

                case 'hadir':
                    $badge='success';
                    $icon='bi-check-circle-fill';
                    $label='Hadir';
                    break;

                case 'izin':
                    $badge='primary';
                    $icon='bi-calendar-check-fill';
                    $label='Izin';
                    break;

                case 'sakit':
                    $badge='warning';
                    $icon='bi-heart-pulse-fill';
                    $label='Sakit';
                    break;

                default:
                    $badge='danger';
                    $icon='bi-x-circle-fill';
                    $label='Alpa';
                    break;

            }

        @endphp

        <tr>

            <td class="text-center">

                <span class="badge bg-light text-dark rounded-pill px-3 py-2">

                    {{ $absensi->firstItem() + $loop->index }}

                </span>

            </td>

            <td>

                <div class="d-flex align-items-center">

                    <div class="date-icon me-3">

                        <i class="bi bi-calendar-event-fill"></i>

                    </div>

                    <div>

                        <div class="fw-bold">

                            {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}

                        </div>

                        <small class="text-muted">

                            Jadwal Ekstrakurikuler

                        </small>

                    </div>

                </div>

            </td>

            <td>

                <span class="badge bg-{{ $badge }} rounded-pill px-3 py-2">

                    <i class="bi {{ $icon }} me-1"></i>

                    {{ $label }}

                </span>

            </td>

        </tr>

    @empty

        <tr>

            <td colspan="3">

                <div class="text-center py-5">

                    <i class="bi bi-calendar-x display-3 text-secondary"></i>

                    <h5 class="fw-bold mt-3">

                        Belum Ada Data Absensi

                    </h5>

                    <p class="text-muted mb-0">

                        Riwayat absensi ekstrakurikuler akan muncul setelah pembina melakukan pencatatan kehadiran.

                    </p>

                </div>

            </td>

        </tr>

    @endforelse

</tbody>

</table>

</div>

<div class="card-footer bg-white border-0 py-3">

    {{ $absensi->links() }}

</div>

</div>

</div>

<div class="mt-4">

    <a
        href="{{ route('siswa.ekskul.index') }}"
        class="btn btn-outline-primary rounded-pill px-4">

        <i class="bi bi-arrow-left me-2"></i>

        Kembali

    </a>

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

    box-shadow:0 .125rem .5rem rgba(0,0,0,.05);

    transition:.25s;

    height:100%;

}

.summary-card:hover{

    transform:translateY(-4px);

    box-shadow:0 16px 40px rgba(37,99,235,.10);

}

.summary-icon{

    width:56px;

    height:56px;

    border-radius:16px;

    display:flex;

    align-items:center;

    justify-content:center;

    font-size:1.25rem;

    margin-bottom:16px;

}

.date-icon{

    width:48px;

    height:48px;

    border-radius:16px;

    background:linear-gradient(135deg,#2563eb,#0f4c81);

    color:#fff;

    display:flex;

    align-items:center;

    justify-content:center;

    flex-shrink:0;

    font-size:1.15rem;

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

.badge{

    font-weight:600;

    letter-spacing:.3px;

}

.btn{

    font-weight:600;

}

.btn:hover{

    transform:translateY(-2px);

}

.pagination{

    margin-bottom:0;

}

@media(max-width:768px){

    .summary-card{

        padding:18px;

    }

    .summary-icon,

    .date-icon{

        width:44px;

        height:44px;

        font-size:1rem;

    }

}

</style>

@endsection