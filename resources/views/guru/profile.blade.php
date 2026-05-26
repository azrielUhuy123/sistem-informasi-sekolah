@extends('layouts.app')

@section('title', 'My Profile')

@section('content')

<div class="container-fluid">

    <div class="mb-4">

        <h2 class="fw-bold mb-1">
            My Profile
        </h2>

        <p class="text-muted mb-0">
            Informasi akun guru
        </p>

    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="row align-items-center">

                <div class="col-md-3 text-center">

                    <img
                        src="https://ui-avatars.com/api/?name={{ urlencode($guru->nama) }}"
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
                                {{ $guru->nama }}
                            </h5>

                        </div>

                        <div class="col-md-6">

                            <label class="text-muted small">
                                NIP
                            </label>

                            <h5 class="fw-semibold">
                                {{ $guru->nip }}
                            </h5>

                        </div>

                        <div class="col-md-6">

                            <label class="text-muted small">
                                Jenis Kelamin
                            </label>

                            <h5 class="fw-semibold">
                                {{ $guru->jenis_kelamin }}
                            </h5>

                        </div>

                        <div class="col-md-6">

                            <label class="text-muted small">
                                No Telepon
                            </label>

                            <h5 class="fw-semibold">
                                {{ $guru->telepon ?? '-' }}
                            </h5>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection