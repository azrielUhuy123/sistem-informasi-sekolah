@extends('layouts.app')

@section('title', 'Detail Ekskul')

@section('content')

<div class="container-fluid py-4">

    <div class="mb-4">

        <h3 class="fw-bold mb-1">

            Detail Ekskul

        </h3>

        <p class="text-muted mb-0">

            Informasi ekstrakurikuler yang diikuti

        </p>

    </div>

    <div class="row g-4">

        <div class="col-md-7">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <h5 class="fw-bold mb-4">

                        {{ $ekskul->nama }}

                    </h5>

                    <table class="table table-borderless">

                        <tr>

                            <th width="220">

                                Nama Ekskul

                            </th>

                            <td>

                                {{ $ekskul->nama }}

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Pembina

                            </th>

                            <td>

                                {{ $ekskul->pembina->guru->pegawai->nama ?? '-' }}

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Hari

                            </th>

                            <td>

                                {{ $ekskul->hari }}

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Jam

                            </th>

                            <td>

                                {{ substr($ekskul->jam_mulai,0,5) }}
                                -
                                {{ substr($ekskul->jam_selesai,0,5) }}

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Jumlah Anggota

                            </th>

                            <td>

                                {{ $anggota }} Siswa

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Status

                            </th>

                            <td>

                                <span class="badge bg-success">

                                    Aktif

                                </span>

                            </td>

                        </tr>

                    </table>

                </div>

            </div>

        </div>

        <div class="col-md-5">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <h5 class="fw-bold mb-4">

                        Statistik Kehadiran

                    </h5>

                    <div class="mb-3">

                        <div class="text-muted">

                            Total Pertemuan

                        </div>

                        <div class="fs-4 fw-bold">

                            {{ $totalPertemuan }}

                        </div>

                    </div>

                    <div class="mb-3">

                        <div class="text-muted">

                            Total Hadir

                        </div>

                        <div class="fs-4 fw-bold text-success">

                            {{ $hadir }}

                        </div>

                    </div>

                    <div>

                        <div class="text-muted">

                            Persentase Kehadiran

                        </div>

                        <div class="fs-4 fw-bold text-primary">

                            {{ $persentase }}%

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="mt-4">

        <a
            href="{{ route('siswa.ekskul.index') }}"
            class="btn btn-light"
        >

            Kembali

        </a>

    </div>

</div>

@endsection