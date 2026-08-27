@extends('layouts.app')

@section('title', 'Detail Siswa')

@section('content')

@php

    $totalNilai = $nilai->count();

    $rataNilai = $totalNilai
        ? round($nilai->avg('nilai_akhir'),1)
        : 0;

@endphp

<div class="container-fluid py-4">

    {{-- HERO --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

        <div class="card-body p-4 p-lg-5 text-white"
            style="background:linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);">

            <div class="row align-items-center">

                <div class="col-lg-8">

                    <h2 class="fw-bold mb-2">

                        {{ $siswa->nama }}

                    </h2>

                    <p class="mb-0 opacity-75">

                        Detail informasi akademik, absensi, dan perkembangan siswa.

                    </p>

                </div>

                <div class="col-lg-4 mt-4 mt-lg-0">

                    <div class="row g-3">

                        <div class="col-6">

                            <div class="bg-white rounded-4 text-center p-3">

                                <h4 class="fw-bold text-primary mb-1">

                                    {{ $siswa->nis }}

                                </h4>

                                <small class="text-muted">

                                    NIS

                                </small>

                            </div>

                        </div>

                        <div class="col-6">

                            <div class="bg-white rounded-4 text-center p-3">

                                <h4 class="fw-bold text-success mb-1">

                                    {{ $rataNilai }}

                                </h4>

                                <small class="text-muted">

                                    Rata-rata

                                </small>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- ABSENSI --}}
    <div class="row g-3 mb-4">

        <div class="col-lg-3 col-6">

            <div class="summary-card">

                <div class="summary-icon bg-success-subtle text-success">

                    <i class="bi bi-check-circle-fill"></i>

                </div>

                <h3>{{ $hadir }}</h3>

                <small class="text-muted">

                    Hadir

                </small>

            </div>

        </div>

        <div class="col-lg-3 col-6">

            <div class="summary-card">

                <div class="summary-icon bg-warning-subtle text-warning">

                    <i class="bi bi-calendar-check-fill"></i>

                </div>

                <h3>{{ $izin }}</h3>

                <small class="text-muted">

                    Izin

                </small>

            </div>

        </div>

        <div class="col-lg-3 col-6">

            <div class="summary-card">

                <div class="summary-icon bg-info-subtle text-info">

                    <i class="bi bi-heart-pulse-fill"></i>

                </div>

                <h3>{{ $sakit }}</h3>

                <small class="text-muted">

                    Sakit

                </small>

            </div>

        </div>

        <div class="col-lg-3 col-6">

            <div class="summary-card">

                <div class="summary-icon bg-danger-subtle text-danger">

                    <i class="bi bi-x-circle-fill"></i>

                </div>

                <h3>{{ $alpha }}</h3>

                <small class="text-muted">

                    Alpha

                </small>

            </div>

        </div>

    </div>

    {{-- NILAI --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-header bg-white border-0 py-4">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="fw-bold mb-1">

                        Nilai Akademik

                    </h5>

                    <small class="text-muted">

                        Rekap seluruh nilai mata pelajaran siswa.

                    </small>

                </div>

                <span class="badge bg-primary rounded-pill px-2 py-1">

                    {{ $totalNilai }} Mata Pelajaran

                </span>

            </div>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>

                        <tr>

                            <th>Mata Pelajaran</th>

                            <th class="text-center">Tugas</th>

                            <th class="text-center">UTS</th>

                            <th class="text-center">UAS</th>

                            <th class="text-center">Nilai Akhir</th>

                        </tr>

                    </thead>

                    <tbody>

    @forelse($nilai as $n)

        <tr>

            <td>

                <div class="d-flex align-items-center">

                    <div class="mapel-icon me-3">

                        <i class="bi bi-book-half"></i>

                    </div>

                    <div>

                        <div class="fw-bold">

                            {{ $n->mapel->nama_mapel }}

                        </div>

                        <small class="text-muted">

                            Mata Pelajaran

                        </small>

                    </div>

                </div>

            </td>

            <td>

                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">

                    {{ $n->nilai_tugas }}

                </span>

            </td>

            <td>

                <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2">

                    {{ $n->nilai_uts }}

                </span>

            </td>

            <td>

                <span class="badge bg-info-subtle text-info rounded-pill px-3 py-2">

                    {{ $n->nilai_uas }}

                </span>

            </td>

            <td>

                <span class="badge bg-success rounded-pill px-3 py-2">

                    {{ $n->nilai_akhir }}

                </span>

            </td>

        </tr>

    @empty

        <tr>

            <td colspan="5">

                <div class="text-center py-5">

                    <i class="bi bi-journal-x display-3 text-secondary"></i>

                    <h5 class="fw-bold mt-3">

                        Belum Ada Nilai

                    </h5>

                    <p class="text-muted mb-0">

                        Nilai akademik siswa belum tersedia.

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

{{-- PERKEMBANGAN --}}
<div class="card border-0 shadow-sm rounded-4">

    <div class="card-header bg-white border-0 py-4">

        <h5 class="fw-bold mb-1">

            Perkembangan Siswa

        </h5>

        <small class="text-muted">

            Catatan perkembangan karakter dan perilaku siswa.

        </small>

    </div>

    <div class="card-body">

        @if($perkembangan)

            <div class="row g-4">

                <div class="col-md-6">

                    <div class="info-card">

                        <div class="info-title">

                            <i class="bi bi-stars text-primary me-2"></i>

                            Karakter

                        </div>

                        <p class="mb-0">

                            {{ $perkembangan->karakter ?: '-' }}

                        </p>

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="info-card">

                        <div class="info-title">

                            <i class="bi bi-people-fill text-success me-2"></i>

                            Sosial

                        </div>

                        <p class="mb-0">

                            {{ $perkembangan->sosial ?: '-' }}

                        </p>

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="info-card">

                        <div class="info-title">

                            <i class="bi bi-shield-check text-warning me-2"></i>

                            Disiplin

                        </div>

                        <p class="mb-0">

                            {{ $perkembangan->disiplin ?: '-' }}

                        </p>

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="info-card">

                        <div class="info-title">

                            <i class="bi bi-droplet-half text-info me-2"></i>

                            Kebersihan

                        </div>

                        <p class="mb-0">

                            {{ $perkembangan->kebersihan ?: '-' }}

                        </p>

                    </div>

                </div>

                <div class="col-12">

                    <div class="info-card">

                        <div class="info-title">

                            <i class="bi bi-chat-left-text-fill text-danger me-2"></i>

                            Catatan Umum

                        </div>

                        <p class="mb-0">

                            {{ $perkembangan->catatan ?: '-' }}

                        </p>

                    </div>

                </div>

            </div>

        @else

            <div class="text-center py-5">

                <i class="bi bi-person-lines-fill display-3 text-secondary"></i>

                <h5 class="fw-bold mt-3">

                    Belum Ada Data Perkembangan

                </h5>

                <p class="text-muted mb-0">

                    Guru belum memberikan catatan perkembangan siswa.

                </p>

            </div>

        @endif

    </div>

</div>

<div class="mt-4">

    <a href="{{ route('guru.kelas-saya') }}"
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
    margin:0 auto 16px;
    font-size:1.35rem;
}

.mapel-icon{
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

.info-card{
    background:#f8fafc;
    border-radius:18px;
    padding:20px;
    height:100%;
    border:1px solid #eef2f7;
}

.info-title{
    font-weight:700;
    margin-bottom:10px;
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
    border-color:#eef2f7;
    vertical-align:middle;
}

.table-hover tbody tr{
    transition:.2s;
}

.table-hover tbody tr:hover{
    background:#f8fbff;
}

.badge{
    font-weight:600;
}

.btn{
    font-weight:600;
    transition:.2s;
}

.btn:hover{
    transform:translateY(-2px);
}

@media(max-width:768px){

    .summary-card{
        padding:18px;
    }

    .summary-icon,
    .mapel-icon{

        width:45px;
        height:45px;
        font-size:1rem;

    }

}

</style>

@endsection