@extends('layouts.app')

@section('title', 'My Profile')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">
                My Profile
            </h2>

            <p class="text-muted mb-0">
                Informasi akun siswa
            </p>

        </div>

    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="row align-items-center">

                <div class="col-md-3 text-center">

                    <img
                        src="https://ui-avatars.com/api/?name={{ urlencode($siswa->nama) }}"
                        class="rounded-circle border"
                        width="140"
                        height="140"
                    >

                </div>

                <div class="col-md-9">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <label class="text-muted small">
                                Nama Lengkap
                            </label>

                            <h5 class="fw-semibold">
                                {{ $siswa->nama }}
                            </h5>

                        </div>

                        <div class="col-md-6">

                            <label class="text-muted small">
                                NIS
                            </label>

                            <h5 class="fw-semibold">
                                {{ $siswa->nis }}
                            </h5>

                        </div>

                        <div class="col-md-6">

                            <label class="text-muted small">
                                NISN
                            </label>

                            <h5 class="fw-semibold">
                                {{ $siswa->nisn }}
                            </h5>

                        </div>

                        <div class="col-md-6">

                            <label class="text-muted small">
                                Tanggal Lahir
                            </label>

                            <h5 class="fw-semibold">
                                {{ $siswa->tanggal_lahir }}
                            </h5>

                        </div>

                        <div class="col-md-6">

                            <label class="text-muted small">
                                Jenis Kelamin
                            </label>

                            <h5 class="fw-semibold">
                                {{ $siswa->jenis_kelamin }}
                            </h5>

                        </div>

                        <div class="col-md-6">

                            <label class="text-muted small">
                                Kelas
                            </label>

                            <h5 class="fw-semibold">
                                {{ $siswa->penempatanAktif?->kelas?->nama_kelas ?? '-' }}
                            </h5>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection