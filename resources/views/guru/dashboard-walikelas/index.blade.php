@extends('layouts.app')

@section('title', 'Dashboard Wali Kelas')

@section('content')

<div class="container-fluid py-3">

    {{-- HEADER --}}
    <div class="mb-4">

        <h3 class="fw-bold mb-1">

            Dashboard Wali Kelas

        </h3>

        <small class="text-muted">

            {{ $kelas->nama_kelas }}
            -
            Tahun Ajaran
            {{ current_tahun()?->tahun }}

        </small>

    </div>

    {{-- SUMMARY --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted">

                        Total Siswa

                    </small>

                    <h2 class="fw-bold mb-0">

                        {{ $totalSiswa }}

                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted">

                        Laki-Laki

                    </small>

                    <h2 class="fw-bold mb-0">

                        {{ $totalLaki }}

                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted">

                        Perempuan

                    </small>

                    <h2 class="fw-bold mb-0">

                        {{ $totalPerempuan }}

                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted">

                        Kehadiran Kelas

                    </small>

                    <h2 class="fw-bold mb-0">

                        {{ $persentaseHadir }}%

                    </h2>

                </div>

            </div>

        </div>

    </div>

    {{-- SECOND ROW --}}
    <div class="row g-3 mb-4">

        <div class="col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted">

                        Rata-Rata Nilai Kelas

                    </small>

                    <h2 class="fw-bold mb-0">

                        {{ $rataNilai }}

                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted">

                        Belum Ada Perkembangan

                    </small>

                    <h2 class="fw-bold mb-0 text-danger">

                        {{ $belumPerkembangan }}

                    </h2>

                </div>

            </div>

        </div>

    </div>

    {{-- NILAI TERENDAH --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <h5 class="fw-bold mb-4">

                Nilai Terendah

            </h5>

            <div class="table-responsive">

                <table class="table align-middle">

                    <thead class="table-light">

                        <tr>

                            <th>Nama</th>
                            <th>Nilai Akhir</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($nilaiTerendah as $n)

                            <tr>

                                <td>

                                    {{ $n->siswa->nama }}

                                </td>

                                <td>

                                    {{ $n->nilai_akhir }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="2" class="text-center">

                                    Belum ada data

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    {{-- ALPHA --}}
    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <h5 class="fw-bold mb-4">

                Alpha Terbanyak

            </h5>

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

                                    {{ $a['alpha'] }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="2" class="text-center">

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

@endsection