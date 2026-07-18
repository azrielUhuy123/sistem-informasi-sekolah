@extends('layouts.app')

@section('title', 'Absensi')

@section('content')

<div class="container-fluid py-3">

    @php

        $totalAbsensi = $hadir + $izin + $sakit + $alpha;

    @endphp

    {{-- ================= HERO ================= --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

        <div class="card-body p-4 p-lg-5 text-white"
             style="background:linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);">

            <div class="row align-items-center">

                <div class="col-lg-8">

                    <h2 class="fw-bold mb-2">

                        Riwayat Absensi

                    </h2>

                    <p class="mb-0 opacity-75">

                        Pantau riwayat kehadiran, izin, sakit, maupun alpha
                        selama semester aktif.

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

    {{-- ================= FILTER ================= --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-body">

            <form method="GET">

                <div class="row g-3 align-items-end">

                    <div class="col-lg-5">

                        <label class="form-label fw-semibold">

                            Mata Pelajaran

                        </label>

                        <select
                            name="mapel_id"
                            class="form-select rounded-3">

                            <option value="">

                                Semua Mata Pelajaran

                            </option>

                            @foreach($mapels as $m)

                                <option
                                    value="{{ $m->id }}"
                                    {{ $mapelId == $m->id ? 'selected' : '' }}>

                                    {{ $m->nama_mapel }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-lg-4">

                        <label class="form-label fw-semibold">

                            Bulan

                        </label>

                        <input
                            type="month"
                            name="bulan"
                            class="form-control rounded-3"
                            value="{{ $bulanInput ?? now()->format('Y-m') }}">

                    </div>

                    <div class="col-lg-3">

                        <button class="btn btn-primary rounded-pill w-100">

                            <i class="bi bi-funnel-fill me-2"></i>

                            Filter Data

                        </button>

                    </div>

                </div>

            </form>

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

                <div class="summary-icon bg-warning-subtle text-warning">

                    <i class="bi bi-clock-history"></i>

                </div>

                <small class="text-muted">

                    Izin

                </small>

                <h3 class="fw-bold text-warning mb-0">

                    {{ $izin }}

                </h3>

            </div>

        </div>

        <div class="col-md-3">

            <div class="summary-card">

                <div class="summary-icon bg-info-subtle text-info">

                    <i class="bi bi-heart-pulse-fill"></i>

                </div>

                <small class="text-muted">

                    Sakit

                </small>

                <h3 class="fw-bold text-info mb-0">

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

                    Alpha

                </small>

                <h3 class="fw-bold text-danger mb-0">

                    {{ $alpha }}

                </h3>

            </div>

        </div>

    </div>

    {{-- ================= PERSENTASE ================= --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center flex-wrap">

                <div>

                    <h5 class="fw-bold mb-1">

                        Persentase Kehadiran

                    </h5>

                    <small class="text-muted">

                        {{ $kesimpulan }}

                    </small>

                </div>

                <div class="text-end">

                    <h2 class="fw-bold text-primary mb-2">

                        {{ $persentase }}%

                    </h2>

                    <div class="progress rounded-pill" style="width:220px;height:10px;">

                        <div class="progress-bar bg-success"

                             style="width:{{ $persentase }}%">

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- ================= TABLE ================= --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white border-0 py-4">

            <h5 class="fw-bold mb-1">

                Detail Riwayat Absensi

            </h5>

            <small class="text-muted">

                Seluruh riwayat kehadiran berdasarkan filter yang dipilih.

            </small>

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

                            <th>

                                Jam

                            </th>

                            <th>

                                Mata Pelajaran

                            </th>

                            <th>

                                Guru

                            </th>

                            <th width="140">

                                Status

                            </th>

                        </tr>

                    </thead>

                    <tbody>

    @forelse($absensi as $i => $a)

        @php

            switch($a->status){

                case 'hadir':
                    $badge='success';
                    $icon='bi-check-circle-fill';
                    $label='Hadir';
                    break;

                case 'izin':
                    $badge='warning';
                    $icon='bi-clock-history';
                    $label='Izin';
                    break;

                case 'sakit':
                    $badge='primary';
                    $icon='bi-heart-pulse-fill';
                    $label='Sakit';
                    break;

                default:
                    $badge='danger';
                    $icon='bi-x-circle-fill';
                    $label='Alpha';
                    break;

            }

        @endphp

        <tr>

            <td class="text-center">

                <span class="badge bg-light text-dark rounded-pill px-3 py-2">

                    {{ $i+1 }}

                </span>

            </td>

            <td>

                <div class="fw-semibold">

                    {{ \Carbon\Carbon::parse($a->tanggal)->translatedFormat('d F Y') }}

                </div>

            </td>

            <td>

                <span class="badge bg-secondary rounded-pill px-3 py-2">

                    {{ $a->updated_at->format('H:i') }}

                </span>

            </td>

            <td>

                <div class="d-flex align-items-center">

                    <div class="mapel-icon me-3">

                        <i class="bi bi-book-fill"></i>

                    </div>

                    <div>

                        <div class="fw-bold">

                            {{ $a->jadwal->mapel->nama_mapel ?? '-' }}

                        </div>

                        <small class="text-muted">

                            Mata Pelajaran

                        </small>

                    </div>

                </div>

            </td>

            <td>

                {{ $a->jadwal->guru->pegawai->nama ?? '-' }}

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

            <td colspan="6">

                <div class="text-center py-5">

                    <i class="bi bi-calendar-x display-3 text-secondary"></i>

                    <h5 class="fw-bold mt-3">

                        Belum Ada Data Absensi

                    </h5>

                    <p class="text-muted mb-0">

                        Riwayat absensi akan muncul setelah guru melakukan pencatatan kehadiran.

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

    width:54px;

    height:54px;

    border-radius:16px;

    display:flex;

    align-items:center;

    justify-content:center;

    font-size:1.3rem;

    margin-bottom:16px;

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

.mapel-icon{

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

.badge{

    font-weight:600;

    letter-spacing:.3px;

}

.progress{

    background:#e9ecef;

}

@media(max-width:768px){

    .summary-card{

        padding:18px;

    }

    .mapel-icon{

        width:42px;

        height:42px;

        font-size:1rem;

    }

}

</style>

@endsection
