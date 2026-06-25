@extends('layouts.app')

@section('title', 'Absensi')

@section('content')

<div class="container-fluid py-3">

    {{-- HEADER --}}
    <div class="mb-4">

        <h4 class="fw-bold mb-1">

            Riwayat Absensi

        </h4>

        <small class="text-muted">

            Monitoring kehadiran siswa

        </small>

    </div>

    {{-- FILTER --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="GET">

                <div class="row align-items-end">

                    {{-- MAPEL --}}
                    <div class="col-md-6">

                        <label class="form-label">

                            Mata Pelajaran

                        </label>

                        <select
                            name="mapel_id"
                            class="form-select"
                        >

                            <option value="">

                                Semua Mata Pelajaran

                            </option>

                            @foreach($mapels as $m)

                                <option
                                    value="{{ $m->id }}"
                                    {{ $mapelId == $m->id ? 'selected' : '' }}
                                >

                                    {{ $m->nama_mapel }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- BULAN --}}
                    <div class="col-md-4">

                        <label class="form-label">

                            Bulan

                        </label>

                        <input
                            type="month"
                            name="bulan"
                            class="form-control"
                            value="{{ $bulanInput ?? now()->format('Y-m') }}"
                        >

                    </div>

                    {{-- BUTTON --}}
                    <div class="col-md-2">

                        <button class="btn btn-dark w-100">

                            Filter

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

    {{-- STATISTIK --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <small class="text-muted">

                        Hadir

                    </small>

                    <h3 class="fw-bold mb-0 text-success">

                        {{ $hadir }}

                    </h3>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <small class="text-muted">

                        Izin

                    </small>

                    <h3 class="fw-bold mb-0 text-warning">

                        {{ $izin }}

                    </h3>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <small class="text-muted">

                        Sakit

                    </small>

                    <h3 class="fw-bold mb-0 text-primary">

                        {{ $sakit }}

                    </h3>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <small class="text-muted">

                        Alpha

                    </small>

                    <h3 class="fw-bold mb-0 text-danger">

                        {{ $alpha }}

                    </h3>

                </div>

            </div>

        </div>

    </div>

    {{-- PERSENTASE --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body d-flex justify-content-between align-items-center">

            <div>

                <h5 class="fw-bold mb-1">

                    Persentase Kehadiran

                </h5>

                <small class="text-muted">

                    {{ $kesimpulan }}

                </small>

            </div>

            <h2 class="fw-bold mb-0">

                {{ $persentase }}%

            </h2>

        </div>

    </div>

    {{-- TABLE --}}
    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered align-middle">

                    <thead class="table-light">

                        <tr>

                            <th width="70">

                                No

                            </th>

                            <th>

                                Tanggal

                            </th>

                            <th>

                                Jam Update

                            </th>

                            <th>

                                Mata Pelajaran

                            </th>

                            <th>

                                Guru

                            </th>

                            <th width="150">

                                Status

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($absensi as $i => $a)

                            <tr>

                                <td>

                                    {{ $i + 1 }}

                                </td>

                                <td>

                                    {{ \Carbon\Carbon::parse($a->tanggal)->translatedFormat('d F Y') }}

                                </td>

                                <td>

                                    {{ $a->updated_at->format('H:i') }}

                                </td>

                                <td>

                                    {{ $a->jadwal->mapel->nama_mapel ?? '-' }}

                                </td>

                                <td>

                                    {{ $a->jadwal->guru->pegawai->nama ?? '-' }}

                                </td>

                                <td>

                                    @if($a->status == 'hadir')

                                        <span class="badge bg-success">

                                            Hadir

                                        </span>

                                    @elseif($a->status == 'izin')

                                        <span class="badge bg-warning text-dark">

                                            Izin

                                        </span>

                                    @elseif($a->status == 'sakit')

                                        <span class="badge bg-primary">

                                            Sakit

                                        </span>

                                    @else

                                        <span class="badge bg-danger">

                                            Alpha

                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
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
