@extends('layouts.app')

@section('title', 'Dashboard Pembina Ekskul')

@section('content')

<div class="container-fluid py-3">

    {{-- HEADER --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white"
             style="background: linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);">

            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">

                <div>

                    <h3 class="fw-bold mb-2">
                        Dashboard Pembina Ekskul
                    </h3>

                    <p class="mb-0 opacity-75">

                        @if($totalEkskul == 1)

                            {{ optional($ekskuls->first()->ekskul)->nama }}

                        @else

                            {{ $totalEkskul }} Ekskul Dibina

                        @endif

                        • Tahun Ajaran {{ current_tahun()?->tahun }}

                    </p>

                </div>

                <div class="d-flex gap-2">

                    <a
                        href="{{ route('guru.ekskul.anggota.index') }}"
                        class="btn btn-light text-primary shadow-sm">

                        <i class="bi bi-people me-2"></i>

                        Anggota

                    </a>

                    <a
                        href="{{ route('guru.ekskul.jurnal.index') }}"
                        class="btn btn-outline-light shadow-sm">

                        <i class="bi bi-journal-text me-2"></i>

                        Jurnal

                    </a>

                </div>

            </div>

        </div>
    </div>

    {{-- STATISTIK --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card border-0 shadow-sm rounded-4 dashboard-card h-100">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">

                                Total Ekskul

                            </small>

                            <h2 class="fw-bold mt-2 mb-1">

                                {{ $totalEkskul }}

                            </h2>

                            <small class="text-success">

                                Ekskul dibina

                            </small>

                        </div>

                        <div class="rounded-circle bg-primary bg-opacity-10 p-2">

                            <i class="bi bi-trophy text-primary fs-5"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card border-0 shadow-sm rounded-4 dashboard-card h-100">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">

                                Total Anggota

                            </small>

                            <h2 class="fw-bold mt-2 mb-1">

                                {{ $totalAnggota }}

                            </h2>

                            <small class="text-muted">

                                Peserta aktif

                            </small>

                        </div>

                        <div class="rounded-circle bg-success bg-opacity-10 p-2">

                            <i class="bi bi-people-fill text-success fs-5"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card border-0 shadow-sm rounded-4 dashboard-card h-100">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">

                                Jurnal

                            </small>

                            <h2 class="fw-bold mt-2 mb-1">

                                {{ $totalJurnal }}

                            </h2>

                            <small class="text-muted">

                                Kegiatan

                            </small>

                        </div>

                        <div class="rounded-circle bg-warning bg-opacity-10 p-2">

                            <i class="bi bi-journal-richtext text-warning fs-5"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card border-0 shadow-sm rounded-4 dashboard-card h-100">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">

                                Catatan

                            </small>

                            <h2 class="fw-bold mt-2 mb-1">

                                {{ $totalCatatan }}

                            </h2>

                            <small class="text-danger">

                                Pembinaan

                            </small>

                        </div>

                        <div class="rounded-circle bg-danger bg-opacity-10 p-2">

                            <i class="bi bi-journal-bookmark text-danger fs-5"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- QUICK MENU --}}
    <div class="row g-4">

        <div class="col-lg-8">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <div>

                            <h5 class="fw-semibold">

                                Akses Cepat

                            </h5>

                            <p class="text-muted small mb-0">

                                Menu utama pengelolaan ekstrakurikuler.

                            </p>

                        </div>

                        <span class="badge bg-light text-dark">

                            Menu Utama

                        </span>

                    </div>

                    <div class="row g-3">

                        <div class="col-md-6">

                            <a
                                href="{{ route('guru.ekskul.anggota.index') }}"
                                class="text-decoration-none">

                                <div class="border rounded-4 p-3 quick-link h-100">

                                    <div class="d-flex align-items-center mb-2">

                                        <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-2">

                                            <i class="bi bi-people text-primary"></i>

                                        </div>

                                        <h6 class="mb-0 text-dark fw-semibold">

                                            Anggota

                                        </h6>

                                    </div>

                                    <p class="small text-muted mb-0">

                                        Kelola anggota ekstrakurikuler.

                                    </p>

                                </div>

                            </a>

                        </div>

                        <div class="col-md-6">

                            <a
                                href="{{ route('guru.ekskul.absensi.index') }}"
                                class="text-decoration-none">

                                <div class="border rounded-4 p-3 quick-link h-100">

                                    <div class="d-flex align-items-center mb-2">

                                        <div class="rounded-circle bg-success bg-opacity-10 p-2 me-2">

                                            <i class="bi bi-calendar-check text-success"></i>

                                        </div>

                                        <h6 class="mb-0 text-dark fw-semibold">

                                            Absensi

                                        </h6>

                                    </div>

                                    <p class="small text-muted mb-0">

                                        Kelola kehadiran anggota.

                                    </p>

                                </div>

                            </a>

                        </div>

                        <div class="col-md-6">

                            <a
                                href="{{ route('guru.ekskul.jurnal.index') }}"
                                class="text-decoration-none">

                                <div class="border rounded-4 p-3 quick-link h-100">

                                    <div class="d-flex align-items-center mb-2">

                                        <div class="rounded-circle bg-warning bg-opacity-10 p-2 me-2">

                                            <i class="bi bi-journal-text text-warning"></i>

                                        </div>

                                        <h6 class="mb-0 text-dark fw-semibold">

                                            Jurnal

                                        </h6>

                                    </div>

                                    <p class="small text-muted mb-0">

                                        Dokumentasi kegiatan ekstrakurikuler.

                                    </p>

                                </div>

                            </a>

                        </div>

                        <div class="col-md-6">

                            <a
                                href="{{ route('guru.ekskul.pembinaan.index') }}"
                                class="text-decoration-none">

                                <div class="border rounded-4 p-3 quick-link h-100">

                                    <div class="d-flex align-items-center mb-2">

                                        <div class="rounded-circle bg-danger bg-opacity-10 p-2 me-2">

                                            <i class="bi bi-journal-bookmark text-danger"></i>

                                        </div>

                                        <h6 class="mb-0 text-dark fw-semibold">

                                            Pembinaan

                                        </h6>

                                    </div>

                                    <p class="small text-muted mb-0">

                                        Monitoring perkembangan anggota.

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

                        Ringkasan Pembina

                    </h5>

                    <ul class="list-group list-group-flush">

                        <li class="list-group-item px-0">

                            <div class="d-flex justify-content-between">

                                <span class="text-muted">

                                    Pembina

                                </span>

                                <span class="fw-semibold">

                                    {{ $guru->nama }}

                                </span>

                            </div>

                        </li>

                        <li class="list-group-item px-0">

                            <div class="d-flex justify-content-between">

                                <span class="text-muted">

                                    Ekskul

                                </span>

                                <span class="fw-semibold">

                                    {{ $totalEkskul }}

                                </span>

                            </div>

                        </li>

                        <li class="list-group-item px-0">

                            <div class="d-flex justify-content-between">

                                <span class="text-muted">

                                    Anggota

                                </span>

                                <span class="fw-semibold">

                                    {{ $totalAnggota }}

                                </span>

                            </div>

                        </li>

                        <li class="list-group-item px-0">

                            <div class="d-flex justify-content-between">

                                <span class="text-muted">

                                    Tahun

                                </span>

                                <span class="fw-semibold">

                                    {{ current_tahun()?->tahun }}

                                </span>

                            </div>

                        </li>

                    </ul>

                </div>

            </div>

        </div>

        <div class="row g-4 mt-1">

    {{-- JURNAL TERBARU --}}
    <div class="col-lg-6">

        <div class="card border-0 shadow-sm rounded-4 h-100">

            <div class="card-body p-4">

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <div>

                        <h5 class="fw-semibold mb-1">

                            Jurnal Kegiatan Terbaru

                        </h5>

                        <p class="small text-muted mb-0">

                            Aktivitas ekstrakurikuler yang terakhir dicatat.

                        </p>

                    </div>

                    <span class="badge bg-warning-subtle text-warning-emphasis">

                        {{ $totalJurnal }}

                    </span>

                </div>

                <div class="table-responsive">

                    <table class="table align-middle">

                        <thead class="table-light">

                            <tr>

                                <th>Tanggal</th>

                                <th>Ekskul</th>

                                <th>Materi</th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($jurnalTerbaru as $jurnal)

                                <tr>

                                    <td>

                                        {{ \Carbon\Carbon::parse($jurnal->tanggal)->format('d M Y') }}

                                    </td>

                                    <td>

                                        {{ $jurnal->ekskul->nama ?? '-' }}

                                    </td>

                                    <td>

                                        {{ Str::limit($jurnal->materi,40) }}

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="3"
                                        class="text-center text-muted py-4">

                                        Belum ada jurnal kegiatan.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

            {{-- CATATAN PEMBINAAN --}}
            <div class="col-lg-6">

                <div class="card border-0 shadow-sm rounded-4 h-100">

                    <div class="card-body p-4">

                        <div class="d-flex justify-content-between align-items-center mb-3">

                            <div>

                                <h5 class="fw-semibold mb-1">

                                    Catatan Pembinaan Terbaru

                                </h5>

                                <p class="small text-muted mb-0">

                                    Monitoring perkembangan anggota ekskul.

                                </p>

                            </div>

                            <span class="badge bg-danger-subtle text-danger-emphasis">

                                {{ $totalCatatan }}

                            </span>

                        </div>

                        <div class="table-responsive">

                            <table class="table align-middle">

                                <thead class="table-light">

                                    <tr>

                                        <th>Siswa</th>

                                        <th>Perkembangan</th>

                                        <th>Kehadiran</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @forelse($catatanTerbaru as $catatan)

                                        <tr>

                                            <td>

                                                {{ $catatan->siswa->nama ?? '-' }}

                                            </td>

                                            <td>

                                                {{ $catatan->perkembangan }}

                                            </td>

                                            <td>

                                                <span class="badge bg-success">

                                                    {{ $catatan->kehadiran }}

                                                </span>

                                            </td>

                                        </tr>

                                    @empty

                                        <tr>

                                            <td colspan="3"
                                                class="text-center text-muted py-4">

                                                Belum ada catatan pembinaan.

                                            </td>

                                        </tr>

                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<style>

.dashboard-card{
    transition:.2s;
}

.dashboard-card:hover{
    transform:translateY(-3px);
    box-shadow:0 .75rem 1.5rem rgba(15,76,129,.12)!important;
}

.quick-link{
    transition:.2s;
}

.quick-link:hover{
    border-color:#2563eb!important;
    background:#f8fbff;
    transform:translateY(-2px);
}

</style>

@endsection