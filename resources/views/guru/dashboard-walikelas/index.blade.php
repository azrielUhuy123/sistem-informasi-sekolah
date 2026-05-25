@extends('layouts.app')

@section('title', 'Dashboard Wali Kelas')

@section('content')

<div class="container-fluid py-3">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold mb-1">

                Dashboard Wali Kelas

            </h3>

            <small class="text-muted">

                {{ $kelas->nama_kelas }}
                •
                Tahun Ajaran
                {{ current_tahun()?->tahun }}

            </small>

        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route('guru.kelas-saya') }}"
                class="btn btn-primary shadow-sm"
            >

                <i class="bi bi-file-earmark-text"></i>
                Raport

            </a>

            <a
                href="{{ route('guru.absensi.rekap') }}"
                class="btn btn-light border shadow-sm"
            >

                <i class="bi bi-clipboard-data"></i>
                Rekap

            </a>

        </div>

    </div>

    {{-- SUMMARY --}}
    <div class="row g-3 mb-4">

        {{-- TOTAL SISWA --}}
        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">

                                Total Siswa

                            </small>

                            <h2 class="fw-bold mb-1 mt-2">

                                {{ $totalSiswa }}

                            </h2>

                            <small class="text-success">

                                Siswa aktif

                            </small>

                        </div>

                        <div class="fs-2 text-primary">

                            <i class="bi bi-people-fill"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- LAKI --}}
        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">

                                Laki-Laki

                            </small>

                            <h2 class="fw-bold mb-1 mt-2">

                                {{ $totalLaki }}

                            </h2>

                            <small class="text-muted">

                                Peserta didik

                            </small>

                        </div>

                        <div class="fs-2 text-info">

                            <i class="bi bi-gender-male"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- PEREMPUAN --}}
        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">

                                Perempuan

                            </small>

                            <h2 class="fw-bold mb-1 mt-2">

                                {{ $totalPerempuan }}

                            </h2>

                            <small class="text-muted">

                                Peserta didik

                            </small>

                        </div>

                        <div class="fs-2 text-danger">

                            <i class="bi bi-gender-female"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- KEHADIRAN --}}
        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">

                                Kehadiran

                            </small>

                            <h2 class="fw-bold mb-1 mt-2">

                                {{ $persentaseHadir }}%

                            </h2>

                            <small class="
                                {{
                                    $persentaseHadir >= 90
                                    ? 'text-success'
                                    : 'text-danger'
                                }}
                            ">

                                Monitoring semester

                            </small>

                        </div>

                        <div class="fs-2 text-success">

                            <i class="bi bi-clipboard-check"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- SECOND ROW --}}
    <div class="row g-3 mb-4">

        {{-- RATA NILAI --}}
        <div class="col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">

                                Rata-Rata Nilai Kelas

                            </small>

                            <h2 class="fw-bold mt-2 mb-1">

                                {{ $rataNilai }}

                            </h2>

                            <small class="text-success">

                                Akademik kelas stabil

                            </small>

                        </div>

                        <div class="fs-1 text-warning">

                            <i class="bi bi-bar-chart-line"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- PERKEMBANGAN --}}
        <div class="col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">

                                Belum Ada Perkembangan

                            </small>

                            <h2 class="fw-bold mt-2 mb-1 text-danger">

                                {{ $belumPerkembangan }}

                            </h2>

                            <small class="text-danger">

                                Perlu tindak lanjut

                            </small>

                        </div>

                        <div class="fs-1 text-danger">

                            <i class="bi bi-exclamation-circle"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- ALERT --}}
    <div class="alert alert-warning border-0 shadow-sm mb-4">

        <div class="d-flex align-items-center gap-2">

            <i class="bi bi-exclamation-triangle-fill"></i>

            <div>

                Monitoring kelas membantu wali kelas
                memantau siswa yang memerlukan perhatian khusus.

            </div>

        </div>

    </div>

    {{-- MONITORING --}}
    <div class="row g-3">

        {{-- NILAI TERENDAH --}}
        <div class="col-lg-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-4">

                        <h5 class="fw-bold mb-0">

                            Perlu Perhatian Akademik

                        </h5>

                        <span class="badge bg-danger">

                            Nilai Rendah

                        </span>

                    </div>

                    <div class="table-responsive">

                        <table class="table align-middle">

                            <thead class="table-light">

                                <tr>

                                    <th>Nama</th>
                                    <th>Nilai</th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse($nilaiTerendah as $n)

                                    <tr>

                                        <td>

                                            {{ $n->siswa->nama }}

                                        </td>

                                        <td>

                                            <span class="badge bg-danger">

                                                {{ $n->nilai_akhir }}

                                            </span>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="2" class="text-center text-muted py-4">

                                            Belum ada data

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

        {{-- ALPHA --}}
        <div class="col-lg-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-4">

                        <h5 class="fw-bold mb-0">

                            Kehadiran Bermasalah

                        </h5>

                        <span class="badge bg-warning text-dark">

                            Alpha

                        </span>

                    </div>

                    <div class="table-responsive">

                        <table class="table align-middle">

                            <thead class="table-light">

                                <tr>

                                    <th>Nama</th>
                                    <th>Total Alpha</th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse($alphaTerbanyak as $a)

                                    <tr>

                                        <td>

                                            {{ $a['nama'] }}

                                        </td>

                                        <td>

                                            <span class="badge bg-warning text-dark">

                                                {{ $a['alpha'] }}

                                            </span>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="2" class="text-center text-muted py-4">

                                            Belum ada data

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

@endsection