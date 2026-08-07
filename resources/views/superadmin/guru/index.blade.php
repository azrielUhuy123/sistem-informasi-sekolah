@extends('layouts.app')

@section('title', 'Guru & Wali Kelas')

@section('content')

<div class="container-fluid">

    {{-- ================= HEADER ================= --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

        <div class="card-body p-4 p-lg-5 text-white"
            style="background:linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);">

            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center">

                <div>

                    <h2 class="fw-bold mb-2">
                        Guru & Wali Kelas
                    </h2>

                    <p class="mb-0 opacity-75">
                        Monitoring seluruh guru, wali kelas, dan mata pelajaran yang diampu.
                    </p>

                </div>

                <div class="mt-3 mt-lg-0">

                    <div class="bg-white rounded-4 px-4 py-3 text-center">

                        <h3 class="fw-bold text-primary mb-0">

                            {{ $gurus->count() }}

                        </h3>

                        <small class="text-muted">

                            Total Guru

                        </small>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- ================= TABLE ================= --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white border-0 pt-4 px-4">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="fw-bold mb-1">

                        <i class="bi bi-person-workspace text-primary me-2"></i>

                        Data Guru & Wali Kelas

                    </h5>

                    <small class="text-muted">

                        Informasi guru beserta tugas akademiknya.

                    </small>

                </div>

            </div>

        </div>

        <div class="card-body pt-2">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>

                        <tr>

                            <th>Guru</th>

                            <th width="170">Status Akademik</th>

                            <th>Mata Pelajaran</th>

                            <th width="130">Kelas</th>

                            <th width="120" class="text-center">Aksi</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($gurus as $guru)

                            <tr>

                                {{-- GURU --}}
                                <td>

                                    <div class="d-flex align-items-center">

                                        <div class="avatar-circle me-3">

                                            {{ strtoupper(substr($guru->pegawai->nama,0,1)) }}

                                        </div>

                                        <div>

                                            <div class="fw-semibold">

                                                {{ $guru->pegawai->nama }}

                                            </div>

                                            <small class="text-muted">

                                                {{ $guru->pegawai->nip ?? '-' }}

                                            </small>

                                        </div>

                                    </div>

                                </td>

                                {{-- STATUS --}}
                                <td>

                                    @if($guru->waliKelas)

                                        <span class="badge rounded-pill bg-primary px-3 py-2">

                                            <i class="bi bi-mortarboard-fill me-1"></i>

                                            Wali Kelas

                                        </span>

                                    @else

                                        <span class="badge rounded-pill bg-secondary px-3 py-2">

                                            <i class="bi bi-book-half me-1"></i>

                                            Guru Bidang

                                        </span>

                                    @endif

                                </td>

                                {{-- MAPEL --}}
                                <td>

                                    @if($guru->waliKelas)

                                        @php

                                            $mapelInti = [

                                                'Bahasa Indonesia',
                                                'Matematika',
                                                'Pendidikan Pancasila',
                                                'IPAS',

                                            ];

                                        @endphp

                                        @foreach($mapelInti as $mapel)

                                            <span class="badge bg-light border text-dark mb-1">

                                                {{ $mapel }}

                                            </span>

                                        @endforeach

                                    @else

                                        @forelse($guru->pengampuMapel as $pengampu)

                                            <span class="badge bg-primary-subtle text-primary border mb-1">

                                                {{ $pengampu->mapel->nama_mapel }}

                                            </span>

                                        @empty

                                            <span class="text-muted">

                                                -

                                            </span>

                                        @endforelse

                                    @endif

                                </td>

                                {{-- KELAS --}}
                                <td>

                                    @if($guru->waliKelas)

                                        <span class="badge bg-success-subtle text-success px-3 py-2">

                                            {{ $guru->waliKelas->kelas->nama_kelas_label }}

                                        </span>

                                    @else

                                        @forelse($guru->pengampuMapel as $pengampu)

                                            <span class="badge bg-success-subtle text-success px-3 py-2 mb-1">

                                                {{ $pengampu->kelas->nama_kelas_label }}

                                            </span>

                                        @empty

                                            -

                                        @endforelse

                                    @endif

                                </td>

                                {{-- AKSI --}}
                                <td class="text-center">

                                    <a href="{{ route('superadmin.guru.show',$guru) }}"
                                        class="btn btn-primary btn-sm rounded-pill px-3">

                                        <i class="bi bi-eye-fill me-1"></i>

                                        Detail

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5">

                                    <div class="empty-state">

                                        <i class="bi bi-person-x display-4 text-secondary"></i>

                                        <h5 class="mt-3">

                                            Belum Ada Data Guru

                                        </h5>

                                        <p class="text-muted mb-0">

                                            Data guru belum tersedia.

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

.avatar-circle{

    width:52px;

    height:52px;

    border-radius:50%;

    background:linear-gradient(135deg,#2563eb,#0f4c81);

    color:white;

    display:flex;

    align-items:center;

    justify-content:center;

    font-weight:700;

    font-size:1.1rem;

    box-shadow:0 6px 18px rgba(37,99,235,.25);

}

.table thead th{

    border:0;

    color:#64748b;

    text-transform:uppercase;

    font-size:.83rem;

    font-weight:700;

    padding:16px;

    letter-spacing:.5px;

}

.table tbody td{

    padding:18px 16px;

    vertical-align:middle;

}

.table-hover tbody tr{

    transition:.25s;

}

.table-hover tbody tr:hover{

    background:#f8fbff;

}

.card{

    transition:.25s;

}

.card:hover{

    box-shadow:0 15px 40px rgba(15,76,129,.08)!important;

}

.empty-state{

    text-align:center;

    padding:60px 20px;

}

.badge{

    font-weight:500;

}

</style>

@endsection