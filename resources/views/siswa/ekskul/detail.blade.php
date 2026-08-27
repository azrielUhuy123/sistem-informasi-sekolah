@extends('layouts.app')

@section('title', 'Detail Ekskul')

@section('content')

<div class="container-fluid py-4">

    {{-- ================= HERO ================= --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

        <div
            class="card-body p-4 p-lg-5 text-white"
            style="background:linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);"
        >

            <div class="row align-items-center">

                <div class="col-lg-8">

                    <h2 class="fw-bold mb-2">

                        Detail Ekstrakurikuler

                    </h2>

                    <p class="mb-0 opacity-75">

                        Informasi lengkap kegiatan ekstrakurikuler yang sedang
                        Anda ikuti beserta statistik kehadiran.

                    </p>

                </div>

                <div class="col-lg-4 mt-4 mt-lg-0">

                    <div class="row g-3">

                        <div class="col-4">

                            <div class="bg-white rounded-4 p-3 text-center">

                                <h4 class="fw-bold text-primary mb-1">

                                    {{ $anggota }}

                                </h4>

                                <small class="text-muted">

                                    Anggota

                                </small>

                            </div>

                        </div>

                        <div class="col-4">

                            <div class="bg-white rounded-4 p-3 text-center">

                                <h4 class="fw-bold text-success mb-1">

                                    {{ $hadir }}

                                </h4>

                                <small class="text-muted">

                                    Hadir

                                </small>

                            </div>

                        </div>

                        <div class="col-4">

                            <div class="bg-white rounded-4 p-3 text-center">

                                <h4 class="fw-bold text-warning mb-1">

                                    {{ $persentase }}%

                                </h4>

                                <small class="text-muted">

                                    Kehadiran

                                </small>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="row g-4">

        {{-- ================= INFORMASI ================= --}}
        <div class="col-lg-7">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-header bg-white border-0 py-4">

                    <div class="d-flex align-items-center">

                        <div class="ekskul-icon me-3">

                            <i class="bi bi-trophy-fill"></i>

                        </div>

                        <div>

                            <h5 class="fw-bold mb-1">

                                {{ $ekskul->nama }}

                            </h5>

                            <small class="text-muted">

                                Informasi Ekstrakurikuler

                            </small>

                        </div>

                    </div>

                </div>

                <div class="card-body">

                    <table class="table table-borderless align-middle mb-0">

    <tr>
        <th width="220" class="text-muted">
            Nama Ekskul
        </th>
        <td class="fw-semibold">
            {{ $ekskul->nama }}
        </td>
    </tr>

    <tr>
        <th class="text-muted">
            Pembina
        </th>
        <td>
            {{ $ekskul->pembina->guru->pegawai->nama ?? '-' }}
        </td>
    </tr>

    <tr>
        <th class="text-muted">
            Hari Latihan
        </th>
        <td>
            <span class="badge bg-primary rounded-pill px-2 py-1">

                {{ $ekskul->hari }}

            </span>
        </td>
    </tr>

    <tr>
        <th class="text-muted">
            Jadwal
        </th>
        <td>

            <i class="bi bi-clock-fill text-primary me-2"></i>

            {{ substr($ekskul->jam_mulai,0,5) }}
            -
            {{ substr($ekskul->jam_selesai,0,5) }}

        </td>
    </tr>

    <tr>
        <th class="text-muted">
            Jumlah Anggota
        </th>
        <td>

            <span class="badge bg-success rounded-pill px-3 py-2">

                {{ $anggota }} Siswa

            </span>

        </td>
    </tr>

    <tr>
        <th class="text-muted">
            Status
        </th>
        <td>

            <span class="badge bg-success rounded-pill px-3 py-2">

                <i class="bi bi-check-circle-fill me-1"></i>

                Aktif

            </span>

        </td>
    </tr>

</table>

</div>

</div>

</div>

{{-- ================= STATISTIK ================= --}}
<div class="col-lg-5">

    <div class="card border-0 shadow-sm rounded-4 h-100">

        <div class="card-header bg-white border-0 py-4">

            <h5 class="fw-bold mb-1">

                Statistik Kehadiran

            </h5>

            <small class="text-muted">

                Ringkasan kehadiran selama mengikuti ekstrakurikuler.

            </small>

        </div>

        <div class="card-body">

            <div class="stat-box">

                <div class="stat-icon bg-primary-subtle text-primary">

                    <i class="bi bi-calendar-event-fill"></i>

                </div>

                <div>

                    <small class="text-muted">

                        Total Pertemuan

                    </small>

                    <h3 class="fw-bold mb-0">

                        {{ $totalPertemuan }}

                    </h3>

                </div>

            </div>

            <div class="stat-box">

                <div class="stat-icon bg-success-subtle text-success">

                    <i class="bi bi-check-circle-fill"></i>

                </div>

                <div>

                    <small class="text-muted">

                        Total Hadir

                    </small>

                    <h3 class="fw-bold text-success mb-0">

                        {{ $hadir }}

                    </h3>

                </div>

            </div>

            <div class="stat-box mb-4">

                <div class="stat-icon bg-warning-subtle text-warning">

                    <i class="bi bi-bar-chart-fill"></i>

                </div>

                <div>

                    <small class="text-muted">

                        Persentase Kehadiran

                    </small>

                    <h3 class="fw-bold text-primary mb-0">

                        {{ $persentase }}%

                    </h3>

                </div>

            </div>

            <div class="progress rounded-pill" style="height:10px;">

                <div
                    class="progress-bar bg-success"
                    style="width:{{ $persentase }}%">
                </div>

            </div>

        </div>

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

.ekskul-icon{

    width:56px;

    height:56px;

    border-radius:18px;

    display:flex;

    align-items:center;

    justify-content:center;

    background:linear-gradient(135deg,#2563eb,#0f4c81);

    color:#fff;

    font-size:1.3rem;

    flex-shrink:0;

}

.table th{

    font-weight:600;

    color:#64748b;

    padding:14px 0;

}

.table td{

    padding:14px 0;

}

.stat-box{

    display:flex;

    align-items:center;

    gap:18px;

    padding:18px;

    border:1px solid #eef2f7;

    border-radius:18px;

    margin-bottom:18px;

    transition:.25s;

}

.stat-box:hover{

    background:#f8fbff;

    border-color:#dbeafe;

}

.stat-icon{

    width:56px;

    height:56px;

    border-radius:16px;

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

.progress{

    background:#e9ecef;

}

.btn{

    font-weight:600;

}

.btn:hover{

    transform:translateY(-2px);

}

@media(max-width:768px){

    .ekskul-icon,

    .stat-icon{

        width:48px;

        height:48px;

        font-size:1.1rem;

    }

}

</style>

@endsection