@extends('layouts.app')

@section('title', 'Rekap Absensi Ekskul')

@section('content')

<div class="container-fluid py-3">

    <div class="mb-4">

        <h4 class="fw-bold mb-1">

            Rekap Absensi Ekskul

        </h4>

        <small class="text-muted">

            {{ $ekskul->nama }}

        </small>

    </div>

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="GET">

                <div class="row g-3 align-items-end">

                    <div class="col-md-3">

                        <label class="form-label">

                            Mode

                        </label>

                        <select
                            name="mode"
                            class="form-select"
                        >

                            <option
                                value="bulanan"
                                {{ $mode == 'bulanan' ? 'selected' : '' }}
                            >

                                Bulanan

                            </option>

                            <option
                                value="tahunan"
                                {{ $mode == 'tahunan' ? 'selected' : '' }}
                            >

                                Tahunan

                            </option>

                        </select>

                    </div>

                    <div class="col-md-3">

                        <label class="form-label">

                            Bulan

                        </label>

                        <select
                            name="bulan"
                            class="form-select"
                        >

                            @for($i = 1; $i <= 12; $i++)

                                <option
                                    value="{{ $i }}"
                                    {{ $bulan == $i ? 'selected' : '' }}
                                >

                                    {{ date('F', mktime(0,0,0,$i,1)) }}

                                </option>

                            @endfor

                        </select>

                    </div>

                    <div class="col-md-3">

                        <label class="form-label">

                            Tahun

                        </label>

                        <input
                            type="number"
                            name="tahun"
                            class="form-control"
                            value="{{ $tahun }}"
                        >

                    </div>

                    <div class="col-md-3">

                        <button
                            class="btn btn-primary w-100"
                        >

                            Filter

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>
    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <small>Hadir</small>

                    <h4 class="mb-0 text-success">

                        {{ $summary['hadir'] }}

                    </h4>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <small>Izin</small>

                    <h4 class="mb-0 text-warning">

                        {{ $summary['izin'] }}

                    </h4>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <small>Sakit</small>

                    <h4 class="mb-0 text-info">

                        {{ $summary['sakit'] }}

                    </h4>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <small>Alpha</small>

                    <h4 class="mb-0 text-danger">

                        {{ $summary['alpha'] }}

                    </h4>

                </div>

            </div>

        </div>

    </div>
    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table align-middle">

                    <thead class="table-light">

                        <tr>

                            <th>No</th>
                            <th>Nama Siswa</th>

                            <th class="text-success">

                                Hadir

                            </th>

                            <th class="text-warning">

                                Izin

                            </th>

                            <th class="text-info">

                                Sakit

                            </th>

                            <th class="text-danger">

                                Alpha

                            </th>

                            <th>

                                %

                            </th>

                            <th>

                                Status

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($rekap as $i => $r)

                            <tr>

                                <td>

                                    {{ $loop->iteration }}

                                </td>

                                <td>

                                    {{ $r['siswa']->nama }}

                                </td>

                                <td class="text-success fw-semibold">

                                    {{ $r['hadir'] }}

                                </td>

                                <td class="text-warning fw-semibold">

                                    {{ $r['izin'] }}

                                </td>

                                <td class="text-info fw-semibold">

                                    {{ $r['sakit'] }}

                                </td>

                                <td class="text-danger fw-semibold">

                                    {{ $r['alpha'] }}

                                </td>

                                <td>

                                    {{ $r['persentase'] }}%

                                </td>

                                <td>

                                    @if($r['persentase'] >= 90)

                                        <span class="badge bg-success">

                                            Sangat Aktif

                                        </span>

                                    @elseif($r['persentase'] >= 75)

                                        <span class="badge bg-primary">

                                            Aktif

                                        </span>

                                    @elseif($r['persentase'] >= 60)

                                        <span class="badge bg-warning">

                                            Cukup

                                        </span>

                                    @else

                                        <span class="badge bg-danger">

                                            Perlu Pembinaan

                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="8"
                                    class="text-center text-muted py-4"
                                >

                                    Belum ada data absensi

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