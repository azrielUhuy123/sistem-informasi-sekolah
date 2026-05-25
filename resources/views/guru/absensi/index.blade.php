@extends('layouts.app')

@section('title', 'Absensi')

@section('content')

<div class="container-fluid py-3">

    {{-- HEADER --}}
    <div class="mb-4">

        <h4 class="fw-bold mb-1">

            Absensi Jadwal Mengajar

        </h4>

        <small class="text-muted">

            Input absensi berdasarkan jadwal pelajaran hari ini

        </small>

    </div>

    {{-- FILTER JADWAL --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="GET">

                <div class="row g-3 align-items-end">

                    {{-- JADWAL --}}
                    <div class="col-lg-7">

                        <label class="form-label">

                            Jadwal Hari Ini

                        </label>

                        <select
                            name="jadwal_id"
                            class="form-select"
                            onchange="this.form.submit()"
                        >

                            @forelse($jadwals as $j)

                                <option
                                    value="{{ $j->id }}"
                                    {{
                                        $jadwalId == $j->id
                                        ? 'selected'
                                        : ''
                                    }}
                                >

                                    {{ $j->kelas->nama_kelas }}
                                    -
                                    {{ $j->mapel->nama_mapel }}
                                    -
                                    Jam {{ $j->jamPelajaran->jam_ke }}
                                    (
                                    {{ substr($j->jamPelajaran->mulai, 0, 5) }}
                                    -
                                    {{ substr($j->jamPelajaran->selesai, 0, 5) }}
                                    )

                                </option>

                            @empty

                                <option>

                                    Tidak ada jadwal hari ini

                                </option>

                            @endforelse

                        </select>

                    </div>

                    {{-- TANGGAL --}}
                    <div class="col-lg-3">

                        <label class="form-label">

                            Tanggal

                        </label>

                        <input
                            type="date"
                            class="form-control"
                            value="{{ request('tanggal') ?? date('Y-m-d') }}"
                            disabled
                        >

                    </div>

                    {{-- INFO --}}
                    <div class="col-lg-2">

                        <div class="small text-muted">

                            Total Jadwal

                        </div>

                        <div class="fw-bold fs-5">

                            {{ $jadwals->count() }}

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>

    {{-- DETAIL JADWAL --}}
    @if($jadwal)

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-body">

                <div class="row">

                    <div class="col-md-3">

                        <small class="text-muted d-block">

                            Kelas

                        </small>

                        <div class="fw-semibold">

                            {{ $jadwal->kelas->nama_kelas }}

                        </div>

                    </div>

                    <div class="col-md-3">

                        <small class="text-muted d-block">

                            Mata Pelajaran

                        </small>

                        <div class="fw-semibold">

                            {{ $jadwal->mapel->nama_mapel }}

                        </div>

                    </div>

                    <div class="col-md-3">

                        <small class="text-muted d-block">

                            Hari

                        </small>

                        <div class="fw-semibold">

                            {{ $jadwal->hari->nama }}

                        </div>

                    </div>

                    <div class="col-md-3">

                        <small class="text-muted d-block">

                            Jam Pelajaran

                        </small>

                        <div class="fw-semibold">

                            Jam {{ $jadwal->jamPelajaran->jam_ke }}
                            (
                            {{ substr($jadwal->jamPelajaran->mulai, 0, 5) }}
                            -
                            {{ substr($jadwal->jamPelajaran->selesai, 0, 5) }}
                            )

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- FORM ABSENSI --}}
        <form
            action="{{ route('guru.absensi.store') }}"
            method="POST"
        >

            @csrf

            <input
                type="hidden"
                name="jadwal_id"
                value="{{ $jadwal->id }}"
            >

            <input
                type="hidden"
                name="kelas_id"
                value="{{ $jadwal->kelas_id }}"
            >

            <input
                type="hidden"
                name="tanggal"
                value="{{ date('Y-m-d') }}"
            >

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-4">

                        <div>

                            <h5 class="fw-bold mb-1">

                                Daftar Kehadiran Siswa

                            </h5>

                            <small class="text-muted">

                                Total siswa:
                                {{ $penempatan->count() }}

                            </small>

                        </div>

                        <button class="btn btn-primary px-4">

                            Simpan Absensi

                        </button>

                    </div>

                    <div class="table-responsive">

                        <table class="table align-middle">

                            <thead class="table-light">

                                <tr>

                                    <th width="70">

                                        No

                                    </th>

                                    <th>

                                        Nama Siswa

                                    </th>

                                    <th width="220">

                                        Status Kehadiran

                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse($penempatan as $i => $p)

                                    <tr>

                                        <td>

                                            {{ $i + 1 }}

                                        </td>

                                        <td>

                                            <div class="fw-semibold">

                                                {{ $p->siswa->nama }}

                                            </div>

                                            <small class="text-muted">

                                                NIS:
                                                {{ $p->siswa->nis }}

                                            </small>

                                        </td>

                                        <td>

                                            <select
                                                name="absensi[{{ $p->siswa->id }}][status]"
                                                class="form-select"
                                            >

                                                <option value="hadir">

                                                    Hadir

                                                </option>

                                                <option value="izin">

                                                    Izin

                                                </option>

                                                <option value="sakit">

                                                    Sakit

                                                </option>

                                                <option value="alpha">

                                                    Alpha

                                                </option>

                                            </select>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="3"
                                            class="text-center text-muted py-4"
                                        >

                                            Tidak ada siswa pada kelas ini

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </form>

    @endif
    {{-- HISTORY ABSENSI --}}
    <div class="card border-0 shadow-sm mt-4">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>

                    <h5 class="fw-bold mb-1">

                        Riwayat Absensi

                    </h5>

                    <small class="text-muted">

                        Absensi pertemuan sebelumnya

                    </small>

                </div>

            </div>

            <div class="table-responsive">

                <table class="table align-middle">

                    <thead class="table-light">

                        <tr>

                            <th>

                                Tanggal

                            </th>

                            <th>

                                Kelas

                            </th>

                            <th>

                                Mapel

                            </th>

                            <th>

                                Jam

                            </th>

                            <th>

                                Siswa

                            </th>

                            <th>

                                Status

                            </th>

                            <th width="120">

                                Aksi

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($history as $h)

                            <tr>

                                <td>

                                    {{ $h->tanggal }}

                                </td>

                                <td>

                                    {{ $h->jadwal->kelas->nama_kelas }}

                                </td>

                                <td>

                                    {{ $h->jadwal->mapel->nama_mapel }}

                                </td>

                                <td>

                                    Jam
                                    {{ $h->jadwal->jamPelajaran->jam_ke }}

                                </td>

                                <td>

                                    {{ $h->siswa->nama }}

                                </td>

                                <td>

                                    <span class="
                                        badge
                                        bg-{{
                                            match($h->status) {
                                                'hadir' => 'success',
                                                'izin' => 'warning',
                                                'sakit' => 'info',
                                                default => 'danger'
                                            }
                                        }}
                                    ">

                                        {{ ucfirst($h->status) }}

                                    </span>

                                </td>

                                <td>

                                    <a
                                        href="{{ route(
                                            'guru.absensi.edit',
                                            $h->id
                                        ) }}"
                                        class="btn btn-sm btn-warning"
                                    >

                                        Edit

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="text-center text-muted py-4"
                                >

                                    Belum ada riwayat absensi

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