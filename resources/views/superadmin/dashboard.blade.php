@extends('layouts.app')

@section('title', 'Dashboard Kepala Sekolah')

@section('content')

<div class="container-fluid px-0">

    {{-- HEADER --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

        <div class="card-body p-4 p-lg-5 text-white"
            style="background: linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);">

            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">

                <div>

                    <h3 class="fw-bold mb-2">
                        Dashboard Kepala Sekolah
                    </h3>

                    <p class="mb-0 opacity-75">
                        Ringkasan akademik, monitoring sekolah, validasi, dan pengambilan keputusan.
                    </p>

                </div>

                <div class="badge bg-white text-primary px-3 py-2 fs-6">

                    <i class="bi bi-person-check-fill me-2"></i>

                    Kepala Sekolah

                </div>

            </div>

        </div>

    </div>

    {{-- STATISTIK --}}
    <div class="row g-4 mb-4">

        <div class="col-lg-4 col-md-6">

            <div class="card border-0 shadow-sm rounded-4 h-100 dashboard-card">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <div class="rounded-circle bg-primary bg-opacity-10 p-2">

                            <i class="bi bi-people-fill text-primary fs-4"></i>

                        </div>

                        <span class="badge bg-light text-muted">
                            Sistem
                        </span>

                    </div>

                    <h2 class="fw-bold mb-0">
                        {{ $totalUser }}
                    </h2>

                    <p class="text-muted small mt-2 mb-0">
                        Total pengguna sistem
                    </p>

                </div>

            </div>

        </div>

        <div class="col-lg-4 col-md-6">

            <div class="card border-0 shadow-sm rounded-4 h-100 dashboard-card">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <div class="rounded-circle bg-warning bg-opacity-10 p-2">

                            <i class="bi bi-clock-history text-warning fs-4"></i>

                        </div>

                        <span class="badge bg-light text-muted">
                            Validasi
                        </span>

                    </div>

                    <h2 class="fw-bold text-warning mb-0">
                        {{ $pendingUser }}
                    </h2>

                    <p class="text-muted small mt-2 mb-0">
                        User menunggu persetujuan
                    </p>

                </div>

            </div>

        </div>

        <div class="col-lg-4 col-md-6">

            <div class="card border-0 shadow-sm rounded-4 h-100 dashboard-card">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <div class="rounded-circle bg-success bg-opacity-10 p-2">

                            <i class="bi bi-check-circle-fill text-success fs-4"></i>

                        </div>

                        <span class="badge bg-light text-muted">
                            Aktif
                        </span>

                    </div>

                    <h2 class="fw-bold text-success mb-0">
                        {{ $activeUser }}
                    </h2>

                    <p class="text-muted small mt-2 mb-0">
                        Pengguna aktif
                    </p>

                </div>

            </div>

        </div>

    </div>

    {{-- QUICK MENU --}}
    <div class="row g-4">

        <div class="col-lg-8">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center mb-4">

                        <div>

                            <h4 class="fw-semibold mb-1">
                                Monitoring Cepat
                            </h4>

                            <p class="text-muted small mb-0">
                                Akses cepat menuju menu yang sering digunakan Kepala Sekolah.
                            </p>

                        </div>

                    </div>

                    <div class="row g-3">

                        <div class="col-md-6">

                            <a href="{{ route('superadmin.monitoring-jadwal.index') }}"
                                class="text-decoration-none">

                                <div class="quick-link border rounded-4 p-3 h-100">

                                    <div class="d-flex align-items-center mb-2">

                                        <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-2">

                                            <i class="bi bi-calendar-check text-primary"></i>

                                        </div>

                                        <h6 class="mb-0 text-dark fw-semibold">
                                            Monitoring Jadwal
                                        </h6>

                                    </div>

                                    <p class="small text-muted mb-0">
                                        Pantau seluruh jadwal pembelajaran sekolah.
                                    </p>

                                </div>

                            </a>

                        </div>

                        <div class="col-md-6">

                            <a href="{{ route('superadmin.guru.index') }}"
                                class="text-decoration-none">

                                <div class="quick-link border rounded-4 p-3 h-100">

                                    <div class="d-flex align-items-center mb-2">

                                        <div class="rounded-circle bg-success bg-opacity-10 p-2 me-2">

                                            <i class="bi bi-person-workspace text-success"></i>

                                        </div>

                                        <h6 class="mb-0 text-dark fw-semibold">
                                            Guru & Wali Kelas
                                        </h6>

                                    </div>

                                    <p class="small text-muted mb-0">
                                        Monitoring data guru dan wali kelas.
                                    </p>

                                </div>

                            </a>

                        </div>

                        <div class="col-md-6">

                            <a href="{{ route('superadmin.siswa.index') }}"
                                class="text-decoration-none">

                                <div class="quick-link border rounded-4 p-3 h-100">

                                    <div class="d-flex align-items-center mb-2">

                                        <div class="rounded-circle bg-warning bg-opacity-10 p-2 me-2">

                                            <i class="bi bi-people text-warning"></i>

                                        </div>

                                        <h6 class="mb-0 text-dark fw-semibold">
                                            Monitoring Siswa
                                        </h6>

                                    </div>

                                    <p class="small text-muted mb-0">
                                        Lihat data seluruh peserta didik.
                                    </p>

                                </div>

                            </a>

                        </div>

                        <div class="col-md-6">

                            <a href="{{ route('superadmin.monitoring-ekskul.index') }}"
                                class="text-decoration-none">

                                <div class="quick-link border rounded-4 p-3 h-100">

                                    <div class="d-flex align-items-center mb-2">

                                        <div class="rounded-circle bg-info bg-opacity-10 p-2 me-2">

                                            <i class="bi bi-trophy text-info"></i>

                                        </div>

                                        <h6 class="mb-0 text-dark fw-semibold">
                                            Monitoring Ekskul
                                        </h6>

                                    </div>

                                    <p class="small text-muted mb-0">
                                        Monitoring seluruh kegiatan ekstrakurikuler.
                                    </p>

                                </div>

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- RINGKASAN --}}
        <div class="col-lg-4">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body p-4">

                    <h5 class="fw-semibold mb-3">
                        Ringkasan
                    </h5>

                    <ul class="list-group list-group-flush">

                        <li class="list-group-item px-0 py-3">

                            <div class="d-flex justify-content-between">

                                <span class="text-muted">
                                    Status Sistem
                                </span>

                                <span class="badge bg-success-subtle text-success">
                                    Aktif
                                </span>

                            </div>

                        </li>

                        <li class="list-group-item px-0 py-3">

                            <div class="d-flex justify-content-between">

                                <span class="text-muted">
                                    Tahun Ajaran
                                </span>

                                <span class="fw-semibold">
                                    {{ current_tahun()?->tahun }}
                                </span>

                            </div>

                        </li>

                        <li class="list-group-item px-0 py-3">

                            <div class="d-flex justify-content-between">

                                <span class="text-muted">
                                    Semester
                                </span>

                                <span class="fw-semibold">
                                    {{ current_tahun()?->semester }}
                                </span>

                            </div>

                        </li>

                        <li class="list-group-item px-0 py-3">

                            <div class="d-flex justify-content-between">

                                <span class="text-muted">
                                    Role
                                </span>

                                <span class="fw-semibold">
                                    Kepala Sekolah
                                </span>

                            </div>

                        </li>

                    </ul>

                    <div class="card border-0 bg-light rounded-4 mt-4">

                        <div class="card-body">

                            <h6 class="fw-semibold">
                                Informasi
                            </h6>

                            <p class="small text-muted mb-0">
                                Dashboard ini digunakan Kepala Sekolah untuk memantau aktivitas akademik,
                                validasi administrasi, monitoring guru, serta pengambilan keputusan sekolah.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<style>

.dashboard-card{
    transition:.25s;
}

.dashboard-card:hover{
    transform:translateY(-3px);
    box-shadow:0 1rem 2rem rgba(15,76,129,.12)!important;
}

.quick-link{
    transition:.25s;
}

.quick-link:hover{
    background:#f8fbff;
    border-color:#2563eb!important;
    transform:translateY(-2px);
}

</style>

@endsection