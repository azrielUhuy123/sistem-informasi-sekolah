@extends('layouts.app')

@section('title', 'Dashboard Guru Bidang')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

        <div class="card-body p-4 p-lg-5 text-white"
            style="background: linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);">

            <div class="d-flex justify-content-between align-items-center flex-wrap">

                <div>

                    <h2 class="fw-bold mb-2">

                        Dashboard Guru Bidang

                    </h2>

                    <p class="mb-0 opacity-75">

                        Selamat datang {{ $guru->nama }}

                    </p>

                </div>

                <div>

                    <a
                        href="{{ route('guru.jadwal-saya.index') }}"
                        class="btn btn-light">

                        <i class="bi bi-calendar3 me-1"></i>

                        Jadwal

                    </a>

                    <a
                        href="{{ route('guru.nilai.index') }}"
                        class="btn btn-outline-light">

                        <i class="bi bi-journal-check me-1"></i>

                        Nilai

                    </a>

                </div>

            </div>

        </div>

    </div>

    {{-- CARD --}}
    <div class="row mb-4">

        <div class="col-lg-3 mb-3">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body">

                    <small class="text-muted">

                        Mata Pelajaran

                    </small>

                    <h2 class="fw-bold mt-2">

                        {{ $totalMapel }}

                    </h2>

                    <small class="text-success">

                        Diampu

                    </small>

                </div>

            </div>

        </div>

        <div class="col-lg-3 mb-3">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body">

                    <small class="text-muted">

                        Jadwal Mengajar

                    </small>

                    <h2 class="fw-bold mt-2">

                        {{ $totalJadwal }}

                    </h2>

                    <small class="text-primary">

                        Aktif

                    </small>

                </div>

            </div>

        </div>

        <div class="col-lg-3 mb-3">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body">

                    <small class="text-muted">

                        Tahun Ajaran

                    </small>

                    <h5 class="fw-bold mt-3">

                        {{ current_tahun()->tahun }}

                    </h5>

                </div>

            </div>

        </div>

        <div class="col-lg-3 mb-3">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body">

                    <small class="text-muted">

                        Status

                    </small>

                    <h5 class="fw-bold mt-3 text-success">

                        Guru Aktif

                    </h5>

                </div>

            </div>

        </div>

    </div>

    <div class="row">

        {{-- AKSES CEPAT --}}
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body">

                    <h5 class="fw-bold mb-1">

                        Akses Cepat

                    </h5>

                    <small class="text-muted">

                        Menu yang sering digunakan.

                    </small>

                    <div class="row mt-4">

                        <div class="col-md-6 mb-3">

                            <a
                                href="{{ route('guru.nilai.index') }}"
                                class="text-decoration-none">

                                <div class="border rounded-4 p-3">

                                    <h6 class="fw-bold">

                                        Nilai

                                    </h6>

                                    <small class="text-muted">

                                        Input dan kelola nilai siswa.

                                    </small>

                                </div>

                            </a>

                        </div>

                        <div class="col-md-6 mb-3">

                            <a
                                href="{{ route('guru.jadwal-saya.index') }}"
                                class="text-decoration-none">

                                <div class="border rounded-4 p-3">

                                    <h6 class="fw-bold">

                                        Jadwal

                                    </h6>

                                    <small class="text-muted">

                                        Lihat jadwal mengajar.

                                    </small>

                                </div>

                            </a>

                        </div>

                        <div class="col-md-6 mb-3">

                            <a
                                href="{{ route('guru.e-learning.index') }}"
                                class="text-decoration-none">

                                <div class="border rounded-4 p-3">

                                    <h6 class="fw-bold">

                                        E-Learning

                                    </h6>

                                    <small class="text-muted">

                                        Kelola materi pembelajaran.

                                    </small>

                                </div>

                            </a>

                        </div>

                        <div class="col-md-6 mb-3">

                            <a
                                href="{{ route('guru.perkembangan.index') }}"
                                class="text-decoration-none">

                                <div class="border rounded-4 p-3">

                                    <h6 class="fw-bold">

                                        Pengembangan

                                    </h6>

                                    <small class="text-muted">

                                        Monitoring perkembangan siswa.

                                    </small>

                                </div>

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- RINGKASAN --}}
        <div class="col-lg-4">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body">

                    <h5 class="fw-bold mb-4">

                        Ringkasan Guru

                    </h5>

                    <table class="table table-borderless">

                        <tr>

                            <td>Nama</td>

                            <td class="text-end fw-bold">

                                {{ $guru->nama }}

                            </td>

                        </tr>

                        <tr>

                            <td>Mapel</td>

                            <td class="text-end fw-bold">

                                {{ $totalMapel }}

                            </td>

                        </tr>

                        <tr>

                            <td>Jadwal</td>

                            <td class="text-end fw-bold">

                                {{ $totalJadwal }}

                            </td>

                        </tr>

                        <tr>

                            <td>Tahun</td>

                            <td class="text-end fw-bold">

                                {{ current_tahun()->tahun }}

                            </td>

                        </tr>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection