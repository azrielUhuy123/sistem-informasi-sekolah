@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')

<div class="container-fluid py-3">

    {{-- ================= HERO ================= --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

        <div class="card-body p-4 p-lg-5 text-white"
             style="background:linear-gradient(135deg,#2563eb 0%,#1d4ed8 45%,#0f4c81 100%);">

            <div class="row align-items-center">

                <div class="col-lg-8">

                    <div class="d-flex align-items-center">

                        <div class="student-avatar me-4">

                            {{ strtoupper(substr($siswa->nama,0,2)) }}

                        </div>

                        <div>

                            <h2 class="fw-bold mb-2">

                                Selamat Datang,

                                {{ $siswa->nama }}

                            </h2>

                            <p class="mb-2 opacity-75">

                                Semoga harimu menyenangkan dan tetap semangat
                                mengikuti seluruh kegiatan pembelajaran hari ini.

                            </p>

                            <span class="badge bg-light text-primary rounded-pill px-3 py-2">

                                Dashboard Akademik Siswa

                            </span>

                        </div>

                    </div>

                </div>

                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">

                    <div class="text-white-50">

                        <small>

                            Hari Ini

                        </small>

                    </div>

                    <h4 class="fw-bold mb-0">

                        {{ now()->translatedFormat('l') }}

                    </h4>

                    <div>

                        {{ now()->translatedFormat('d F Y') }}

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- ================= STATISTIK ================= --}}
    <div class="row g-4 mb-4">

        <div class="col-md-6 col-xl-3">

            <div class="dashboard-card">

                <div class="icon-box bg-primary-subtle text-primary">

                    <i class="bi bi-person-check-fill"></i>

                </div>

                <small class="text-muted">

                    Kehadiran

                </small>

                <h2 class="fw-bold mt-2 mb-1">

                    {{ $persentaseKehadiran }}%

                </h2>

                <small class="text-success">

                    Tingkat Kehadiran

                </small>

            </div>

        </div>

        <div class="col-md-6 col-xl-3">

            <div class="dashboard-card">

                <div class="icon-box bg-success-subtle text-success">

                    <i class="bi bi-award-fill"></i>

                </div>

                <small class="text-muted">

                    Rata-rata Nilai

                </small>

                <h2 class="fw-bold mt-2 mb-1">

                    {{ $rataNilai }}

                </h2>

                <small class="text-success">

                    Prestasi Akademik

                </small>

            </div>

        </div>

        <div class="col-md-6 col-xl-3">

            <div class="dashboard-card">

                <div class="icon-box bg-warning-subtle text-warning">

                    <i class="bi bi-book-fill"></i>

                </div>

                <small class="text-muted">

                    Mata Pelajaran

                </small>

                <h2 class="fw-bold mt-2 mb-1">

                    {{ $totalMapel }}

                </h2>

                <small class="text-warning">

                    Semester Aktif

                </small>

            </div>

        </div>

        <div class="col-md-6 col-xl-3">

            <div class="dashboard-card">

                <div class="icon-box bg-info-subtle text-info">

                    <i class="bi bi-mortarboard-fill"></i>

                </div>

                <small class="text-muted">

                    Status Akademik

                </small>

                <h5 class="fw-bold mt-2 mb-1">

                    {{ $statusAkademik }}

                </h5>

                <small class="text-info">

                    Tahun Ajaran Aktif

                </small>

            </div>

        </div>

    </div>

    <div class="row g-4">

    {{-- ================= JADWAL ================= --}}
    <div class="col-lg-8">

        <div class="card border-0 shadow-sm rounded-4 h-100">

            <div class="card-header bg-white border-0 py-4">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h5 class="fw-bold mb-1">

                            Jadwal Pelajaran Hari Ini

                        </h5>

                        <small class="text-muted">

                            Mata pelajaran yang akan diikuti hari ini.

                        </small>

                    </div>

                    <div class="schedule-icon">

                        <i class="bi bi-calendar-week-fill"></i>

                    </div>

                </div>

            </div>

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead>

                            <tr>

                                <th>Jam</th>

                                <th>Mata Pelajaran</th>

                                <th>Guru</th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($jadwalHariIni as $j)

                                <tr>

                                    <td>

                                        <span class="badge bg-primary rounded-pill px-3 py-2">

                                            {{ \Carbon\Carbon::parse($j->jam->mulai)->format('H:i') }}

                                            -

                                            {{ \Carbon\Carbon::parse($j->jam->selesai)->format('H:i') }}

                                        </span>

                                    </td>

                                    <td>

                                        <div class="fw-bold">

                                            {{ $j->mapel->nama_mapel }}

                                        </div>

                                    </td>

                                    <td>

                                        {{ $j->guru->pegawai->nama ?? '-' }}

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="3">

                                        <div class="text-center py-5">

                                            <i class="bi bi-calendar-x display-4 text-secondary"></i>

                                            <h6 class="fw-bold mt-3">

                                                Tidak Ada Jadwal Hari Ini

                                            </h6>

                                            <small class="text-muted">

                                                Nikmati waktu belajarmu hari ini.

                                            </small>

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

    {{-- ================= ABSENSI ================= --}}
    <div class="col-lg-4">

        <div class="card border-0 shadow-sm rounded-4 h-100">

            <div class="card-header bg-white border-0 py-4">

                <h5 class="fw-bold mb-0">

                    Ringkasan Absensi

                </h5>

            </div>

            <div class="card-body">

                <div class="attendance-item">

                    <span>Hadir</span>

                    <span class="badge bg-success rounded-pill">

                        {{ $hadir }}

                    </span>

                </div>

                <div class="attendance-item">

                    <span>Izin</span>

                    <span class="badge bg-warning text-dark rounded-pill">

                        {{ $izin }}

                    </span>

                </div>

                <div class="attendance-item">

                    <span>Sakit</span>

                    <span class="badge bg-info rounded-pill">

                        {{ $sakit }}

                    </span>

                </div>

                <div class="attendance-item mb-4">

                    <span>Alpha</span>

                    <span class="badge bg-danger rounded-pill">

                        {{ $alpha }}

                    </span>

                </div>

                <hr>

                <small class="text-muted">

                    Persentase Kehadiran

                </small>

                <div class="progress rounded-pill mt-2" style="height:10px;">

                    <div class="progress-bar bg-success"

                        style="width:{{ $persentaseKehadiran }}%">

                    </div>

                </div>

                <div class="text-end mt-2">

                    <strong>

                        {{ $persentaseKehadiran }}%

                    </strong>

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

.student-avatar{

    width:90px;

    height:90px;

    border-radius:50%;

    background:rgba(255,255,255,.2);

    border:3px solid rgba(255,255,255,.35);

    display:flex;

    align-items:center;

    justify-content:center;

    font-size:2rem;

    font-weight:700;

}

.dashboard-card{

    background:#fff;

    border-radius:20px;

    padding:24px;

    border:1px solid #eef2f7;

    box-shadow:0 5px 20px rgba(15,23,42,.05);

    height:100%;

}

.icon-box{

    width:60px;

    height:60px;

    border-radius:18px;

    display:flex;

    align-items:center;

    justify-content:center;

    font-size:1.4rem;

    margin-bottom:18px;

}

.schedule-icon{

    width:48px;

    height:48px;

    border-radius:14px;

    background:#eff6ff;

    color:#2563eb;

    display:flex;

    align-items:center;

    justify-content:center;

    font-size:1.2rem;

}

.table thead th{

    background:#f8fafc;

    color:#64748b;

    text-transform:uppercase;

    font-size:.82rem;

    border:0;

    padding:18px;

}

.table tbody td{

    padding:18px;

    border-color:#eef2f7;

}

.table-hover tbody tr:hover{

    background:#f8fbff;

}

.attendance-item{

    display:flex;

    justify-content:space-between;

    align-items:center;

    padding:12px 0;

    border-bottom:1px solid #eef2f7;

}

.attendance-item:last-child{

    border-bottom:none;

}

.badge{

    font-weight:600;

}

@media(max-width:768px){

    .student-avatar{

        width:70px;

        height:70px;

        font-size:1.5rem;

    }

}

</style>

@endsection