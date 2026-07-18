@extends('layouts.app')

@section('title', 'Kelas Saya')

@section('content')

@php

    $totalSiswa = $penempatan->count();

    $laki = $penempatan->filter(fn($p) => $p->siswa->jenis_kelamin == 'L')->count();

    $perempuan = $penempatan->filter(fn($p) => $p->siswa->jenis_kelamin == 'P')->count();

@endphp

<div class="container-fluid py-4">

    {{-- HERO --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

        <div class="card-body p-4 p-lg-5 text-white"
            style="background:linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);">

            <div class="row align-items-center">

                <div class="col-lg-7">

                    <h2 class="fw-bold mb-2">

                        Kelas Saya

                    </h2>

                    <p class="opacity-75 mb-0">

                        Kelola data siswa yang berada pada kelas yang Anda ampu, serta akses informasi akademik dan raport siswa.

                    </p>

                </div>

                <div class="col-lg-5 mt-4 mt-lg-0">

                    <div class="row g-3">

                        <div class="col-4">

                            <div class="bg-white rounded-4 text-center p-3">

                                <h4 class="fw-bold text-primary mb-1">

                                    {{ $totalSiswa }}

                                </h4>

                                <small class="text-muted">

                                    Total

                                </small>

                            </div>

                        </div>

                        <div class="col-4">

                            <div class="bg-white rounded-4 text-center p-3">

                                <h4 class="fw-bold text-success mb-1">

                                    {{ $laki }}

                                </h4>

                                <small class="text-muted">

                                    Laki-laki

                                </small>

                            </div>

                        </div>

                        <div class="col-4">

                            <div class="bg-white rounded-4 text-center p-3">

                                <h4 class="fw-bold text-danger mb-1">

                                    {{ $perempuan }}

                                </h4>

                                <small class="text-muted">

                                    Perempuan

                                </small>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- ALERT --}}
    @if(session('success'))

        <div class="alert alert-success border-0 shadow-sm rounded-4">

            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

        </div>

    @endif

    @if(session('error'))

        <div class="alert alert-danger border-0 shadow-sm rounded-4">

            <i class="bi bi-exclamation-triangle-fill me-2"></i>

            {{ session('error') }}

        </div>

    @endif

    {{-- SUMMARY --}}
    <div class="row g-3 mb-4">

        <div class="col-lg-4">

            <div class="summary-card">

                <div class="summary-icon bg-primary-subtle text-primary">

                    <i class="bi bi-people-fill"></i>

                </div>

                <h3>{{ $totalSiswa }}</h3>

                <small class="text-muted">

                    Total Siswa

                </small>

            </div>

        </div>

        <div class="col-lg-4">

            <div class="summary-card">

                <div class="summary-icon bg-success-subtle text-success">

                    <i class="bi bi-person-fill"></i>

                </div>

                <h3>{{ $laki }}</h3>

                <small class="text-muted">

                    Siswa Laki-laki

                </small>

            </div>

        </div>

        <div class="col-lg-4">

            <div class="summary-card">

                <div class="summary-icon bg-danger-subtle text-danger">

                    <i class="bi bi-person-fill"></i>

                </div>

                <h3>{{ $perempuan }}</h3>

                <small class="text-muted">

                    Siswa Perempuan

                </small>

            </div>

        </div>

    </div>

    {{-- TABLE --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white border-0 py-4">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="fw-bold mb-1">

                        Daftar Siswa

                    </h5>

                    <small class="text-muted">

                        Seluruh siswa yang berada pada kelas Anda.

                    </small>

                </div>

                <span class="badge bg-primary rounded-pill px-3 py-2">

                    {{ $totalSiswa }} Siswa

                </span>

            </div>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>

                        <tr>

                            <th width="70">No</th>

                            <th>NIS</th>

                            <th>NISN</th>

                            <th>Nama Siswa</th>

                            <th>Tanggal Lahir</th>

                            <th>JK</th>

                            <th width="190" class="text-center">

                                Aksi

                            </th>

                        </tr>

                    </thead>

                    <tbody>

    @forelse($penempatan as $i => $p)

        <tr>

            <td>

                <span class="badge bg-light text-dark rounded-pill px-3 py-2">

                    {{ $i + 1 }}

                </span>

            </td>

            <td>

                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">

                    {{ $p->siswa->nis }}

                </span>

            </td>

            <td>

                <span class="badge bg-info-subtle text-info rounded-pill px-3 py-2">

                    {{ $p->siswa->nisn }}

                </span>

            </td>

            <td>

                <div class="d-flex align-items-center">

                    <div class="student-icon me-3">

                        <i class="bi bi-person-fill"></i>

                    </div>

                    <div>

                        <div class="fw-bold">

                            {{ $p->siswa->nama }}

                        </div>

                        <small class="text-muted">

                            Peserta Didik

                        </small>

                    </div>

                </div>

            </td>

            <td>

                <div class="fw-semibold">

                    <i class="bi bi-calendar-event text-primary me-2"></i>

                    {{ \Carbon\Carbon::parse($p->siswa->tanggal_lahir)->translatedFormat('d F Y') }}

                </div>

            </td>

            <td>

                @if($p->siswa->jenis_kelamin == 'L')

                    <span class="badge bg-primary rounded-pill px-3 py-2">

                        <i class="bi bi-gender-male me-1"></i>

                        Laki-laki

                    </span>

                @else

                    <span class="badge bg-danger rounded-pill px-3 py-2">

                        <i class="bi bi-gender-female me-1"></i>

                        Perempuan

                    </span>

                @endif

            </td>

            <td>

                <div class="d-flex justify-content-center gap-2 flex-wrap">

                    <a
                        href="{{ route('guru.kelas-saya.show',$p->siswa->id) }}"
                        class="btn btn-primary btn-sm rounded-pill px-3">

                        <i class="bi bi-eye-fill me-1"></i>

                        Detail

                    </a>

                    <a
                        href="{{ route('guru.raport.show',$p->siswa->id) }}"
                        class="btn btn-success btn-sm rounded-pill px-3">

                        <i class="bi bi-journal-richtext me-1"></i>

                        Raport

                    </a>

                </div>

            </td>

        </tr>

    @empty

        <tr>

            <td colspan="7">

                <div class="text-center py-5">

                    <i class="bi bi-people display-3 text-secondary"></i>

                    <h5 class="fw-bold mt-3">

                        Belum Ada Siswa

                    </h5>

                    <p class="text-muted mb-0">

                        Belum terdapat siswa yang ditempatkan pada kelas yang Anda ampu.

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

    margin:0 auto 16px;

    font-size:1.35rem;

}

.student-icon{

    width:48px;

    height:48px;

    border-radius:15px;

    background:linear-gradient(135deg,#2563eb,#0f4c81);

    color:#fff;

    display:flex;

    align-items:center;

    justify-content:center;

    font-size:1.15rem;

    flex-shrink:0;

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

    transition:.2s;

}

.btn:hover{

    transform:translateY(-2px);

}

.alert{

    border-radius:18px;

}

@media(max-width:768px){

    .summary-card{

        padding:18px;

    }

    .summary-icon,

    .student-icon{

        width:45px;

        height:45px;

        font-size:1rem;

    }

}

</style>

@endsection