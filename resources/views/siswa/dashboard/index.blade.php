@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')

<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="mb-4">

        <h2 class="fw-bold mb-1">

            Selamat Datang,
            {{ $siswa->nama }}

        </h2>

        <p class="text-muted mb-0">

            Dashboard akademik siswa

        </p>

    </div>

    {{-- STATISTIK --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted">

                        Persentase Kehadiran

                    </small>

                    <h2 class="fw-bold mt-2">

                        {{ $persentaseKehadiran }}%

                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted">

                        Rata-rata Nilai

                    </small>

                    <h2 class="fw-bold mt-2">

                        {{ $rataNilai }}

                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted">

                        Total Mata Pelajaran

                    </small>

                    <h2 class="fw-bold mt-2">

                        {{ $totalMapel }}

                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted">

                        Status Akademik

                    </small>

                    <h2 class="fw-bold mt-2">

                        {{ $statusAkademik }}

                    </h2>

                </div>

            </div>

        </div>

    </div>

    <div class="row g-4">

        {{-- JADWAL HARI INI --}}
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <div>

                            <h5 class="fw-bold mb-1">

                                Jadwal Hari Ini

                            </h5>

                            <small class="text-muted">

                                Jadwal pelajaran siswa

                            </small>

                        </div>

                    </div>

                    <div class="table-responsive">

                        <table class="table align-middle">

                            <thead class="table-light">

                                <tr>

                                    <th>Jam</th>

                                    <th>Mata Pelajaran</th>

                                    <th>Guru</th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse($jadwalHariIni as $j)

                                    <tr>

                                        <td>

                                            {{ \Carbon\Carbon::parse($j->jam->mulai)->format('H:i') }}
                                            -
                                            {{ \Carbon\Carbon::parse($j->jam->selesai)->format('H:i') }}

                                        </td>

                                        <td>

                                            {{ $j->mapel->nama_mapel }}

                                        </td>

                                        <td>

                                            {{ $j->guru->pegawai->nama ?? '-' }}

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="3"
                                            class="text-center text-muted py-4"
                                        >

                                            Tidak ada jadwal hari ini

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

        {{-- RINGKASAN ABSENSI --}}
        <div class="col-lg-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <h5 class="fw-bold mb-4">

                        Ringkasan Absensi

                    </h5>

                    <div class="d-flex justify-content-between mb-3">

                        <span>Hadir</span>

                        <strong>

                            {{ $hadir }}

                        </strong>

                    </div>

                    <div class="d-flex justify-content-between mb-3">

                        <span>Izin</span>

                        <strong>

                            {{ $izin }}

                        </strong>

                    </div>

                    <div class="d-flex justify-content-between mb-3">

                        <span>Sakit</span>

                        <strong>

                            {{ $sakit }}

                        </strong>

                    </div>

                    <div class="d-flex justify-content-between">

                        <span>Alpha</span>

                        <strong>

                            {{ $alpha }}

                        </strong>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection