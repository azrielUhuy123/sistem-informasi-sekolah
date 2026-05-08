@extends('layouts.app')

@section('title', 'Control Akademik')

@section('content')

<div class="container-fluid">

    <!-- 🔶 HEADER -->
    <div class="mb-4">
        <h4 class="mb-1">Control Akademik</h4>
        <small class="text-muted">
            Pusat pengaturan siklus tahun ajaran
        </small>
    </div>

    <!-- 🔷 STATE BANNER -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">

            <div class="text-muted small">STATUS SISTEM</div>

            {{-- dummy dulu --}}
            <h3 class="mb-1">
                2026/2027
                <span class="badge bg-info text-dark">Genap</span>
            </h3>

            <span class="badge bg-success">AKTIF</span>

            <div class="mt-2 text-muted small">
                Mode: Operasional
            </div>

        </div>
    </div>

    <!-- 🔷 PILIH TAHUN AJARAN -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">

            <label class="form-label fw-semibold">
                Pilih Tahun Ajaran
            </label>

            <select class="form-select">
                <option>2026/2027 - Genap (Aktif)</option>
                <option>2026/2027 - Ganjil (Transisi)</option>
                <option>2025/2026 - Genap (Selesai)</option>
            </select>

        </div>
    </div>

    <!-- 🔷 ACTION PANEL -->
    <div class="card shadow-sm border-0">
        <div class="card-body">

            <h6 class="mb-3">Aksi Sistem</h6>

            <div class="d-flex gap-2 flex-wrap">

                <!-- AKTIF -->
                <button class="btn btn-warning">
                    Masuk Masa Transisi
                </button>

                <!-- TRANSISI -->
                <button class="btn btn-primary">
                    Proses Naik Kelas
                </button>

                <button class="btn btn-danger">
                    Tutup Tahun Ajaran
                </button>

                <!-- SELESAI -->
                <button class="btn btn-success">
                    Aktifkan Tahun Ajaran
                </button>

            </div>

            <small class="text-muted d-block mt-3">
                * Tombol akan disesuaikan otomatis berdasarkan status (next step)
            </small>

        </div>
    </div>

</div>

@endsection