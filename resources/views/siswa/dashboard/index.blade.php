@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h1 class="fw-bold mb-1">
            Dashboard Siswa
        </h1>

        <p class="text-muted mb-0">
            Selamat datang, {{ Auth::user()->name }}
        </p>
    </div>

    <span class="badge bg-primary px-3 py-2">
        {{ Auth::user()->role }}
    </span>

</div>

<div class="row g-4">

    <div class="col-md-6">

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <h6 class="text-muted mb-2">
                    Menu Nilai
                </h6>

                <h3 class="fw-bold">
                    Nilai Akademik
                </h3>

                <p class="text-muted mb-0">
                    Lihat perkembangan nilai siswa.
                </p>

            </div>

        </div>

    </div>

    <div class="col-md-6">

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <h6 class="text-muted mb-2">
                    Menu Absensi
                </h6>

                <h3 class="fw-bold">
                    Kehadiran
                </h3>

                <p class="text-muted mb-0">
                    Monitoring absensi semester aktif.
                </p>

            </div>

        </div>

    </div>

</div>

@endsection