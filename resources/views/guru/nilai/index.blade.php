@extends('layouts.app')

@section('title', 'Nilai')

@section('content')

@php

    $totalSiswa = $penempatan->count();

@endphp

<div class="container-fluid py-4">

    {{-- ================= HERO ================= --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

        <div class="card-body p-4 p-lg-5 text-white"
             style="background:linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);">

            <div class="row align-items-center">

                <div class="col-lg-8">

                    <h2 class="fw-bold mb-2">

                        Manajemen Nilai

                    </h2>

                    <p class="mb-0 opacity-75">

                        Kelola nilai siswa berdasarkan kelas dan mata pelajaran yang diampu.

                    </p>

                </div>

                <div class="col-lg-4 mt-4 mt-lg-0">

                    <div class="row g-3">

                        <div class="col-6">

                            <div class="bg-white rounded-4 text-center p-3">

                                <h4 class="fw-bold text-primary mb-1">

                                    {{ $totalSiswa }}

                                </h4>

                                <small class="text-muted">

                                    Siswa

                                </small>

                            </div>

                        </div>

                        <div class="col-6">

                            <div class="bg-white rounded-4 text-center p-3">

                                <h4 class="fw-bold text-success mb-1">

                                    {{ $mapels->count() }}

                                </h4>

                                <small class="text-muted">

                                    Mapel

                                </small>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- ALERT --}}
    @if(session('success'))

        <div class="alert alert-success border-0 shadow-sm rounded-4">

            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

        </div>

    @endif

    @if(session('error'))

        <div class="alert alert-danger border-0 shadow-sm rounded-4">

            <i class="bi bi-exclamation-triangle-fill me-2"></i>

            {{ session('error') }}

        </div>

    @endif

    {{-- ================= FILTER ================= --}}

    @isset($waliKelas)

        <div class="card border-0 shadow-sm rounded-4 mb-4">

            <div class="card-header bg-white border-0 py-3">

                <h5 class="fw-bold mb-0">

                    Filter Nilai

                </h5>

            </div>

            <div class="card-body">

                <div class="row g-3 align-items-end">

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">

                            Kelas

                        </label>

                        <input
                            type="text"
                            class="form-control rounded-3"
                            value="{{ $kelas->nama_kelas_label }}"
                            readonly>

                    </div>

                    <div class="col-md-6">

                        <form method="GET">

                            <label class="form-label fw-semibold">

                                Mata Pelajaran

                            </label>

                            <select
                                name="mapel_id"
                                class="form-select rounded-3"
                                onchange="this.form.submit()">

                                @foreach($mapels as $m)

                                    <option value="{{ $m->id }}"
                                        {{ $mapelId == $m->id ? 'selected' : '' }}>

                                        {{ $m->nama_mapel }}

                                    </option>

                                @endforeach

                            </select>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    @else

        <div class="card border-0 shadow-sm rounded-4 mb-4">

            <div class="card-header bg-white border-0 py-3">

                <h5 class="fw-bold mb-0">

                    Filter Nilai

                </h5>

            </div>

            <div class="card-body">

                <form method="GET">

                    <div class="row g-3 align-items-end">

                        <div class="col-md-4">

                            <label class="form-label fw-semibold">

                                Kelas

                            </label>

                            <select
                                name="kelas_id"
                                class="form-select rounded-3">

                                <option value="">

                                    Pilih Kelas

                                </option>

                                @foreach($kelasList as $k)

                                    <option
                                        value="{{ $k->id }}"
                                        {{ $kelasId == $k->id ? 'selected' : '' }}>

                                        {{ $k->nama_kelas_label }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                        <div class="col-md-4">

                            <label class="form-label fw-semibold">

                                Mata Pelajaran

                            </label>

                            <select
                                name="mapel_id"
                                class="form-select rounded-3">

                                <option value="">

                                    Pilih Mata Pelajaran

                                </option>

                                @foreach($mapels as $m)

                                    <option
                                        value="{{ $m->id }}"
                                        {{ $mapelId == $m->id ? 'selected' : '' }}>

                                        {{ $m->nama_mapel }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                        <div class="col-md-4">

                            <button class="btn btn-primary rounded-pill w-100">

                                <i class="bi bi-search me-2"></i>

                                Tampilkan Data

                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    @endisset

    {{-- TABLE NILAI --}}
    @if($penempatan->count())

        <form
            action="{{ route('guru.nilai.store') }}"
            method="POST"
        >

            @csrf

            <input
                type="hidden"
                name="kelas_id"
                value="{{ $kelas->id ?? $kelasId }}"
            >

            <input
                type="hidden"
                name="mapel_id"
                value="{{ $mapelId }}"
            >

            <div class="card border-0 shadow-sm">

                <div class="card-body">

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
                                    <th>Kehadiran</th>

                                    <th width="180">

                                        Tugas

                                    </th>

                                    <th width="180">

                                        UTS

                                    </th>

                                    <th width="180">

                                        UAS

                                    </th>

                                </tr>

                            </thead>

                            <tbody>

    @foreach($penempatan as $i => $p)

        @php

            $persen = $p->persentase_kehadiran;

            if($persen >= 90){

                $badge = 'success';

            }elseif($persen >= 75){

                $badge = 'warning';

            }else{

                $badge = 'danger';

            }

        @endphp

        <tr>

            <td>

                <span class="badge bg-light text-dark rounded-pill px-3 py-2">

                    {{ $i + 1 }}

                </span>

            </td>

            <td>

                <div class="d-flex align-items-center">

                    <div class="student-icon me-3">

                        {{ strtoupper(substr($p->siswa->nama,0,1)) }}

                    </div>

                    <div>

                        <div class="fw-bold">

                            {{ $p->siswa->nama }}

                        </div>

                        <small class="text-muted">

                            {{ $p->siswa->nis }}

                        </small>

                    </div>

                </div>

            </td>

            <td>

                <span class="badge bg-{{ $badge }} rounded-pill px-3 py-2">

                    <i class="bi bi-bar-chart-fill me-1"></i>

                    {{ $persen }}%

                </span>

            </td>

            <td>

                <input
                    type="number"
                    name="nilai[{{ $p->siswa->id }}][tugas]"
                    class="form-control form-control-modern"
                    min="0"
                    max="100"
                    value="{{ $p->nilai_existing->nilai_tugas ?? '' }}">

            </td>

            <td>

                <input
                    type="number"
                    name="nilai[{{ $p->siswa->id }}][uts]"
                    class="form-control form-control-modern"
                    min="0"
                    max="100"
                    value="{{ $p->nilai_existing->nilai_uts ?? '' }}">

            </td>

            <td>

                <input
                    type="number"
                    name="nilai[{{ $p->siswa->id }}][uas]"
                    class="form-control form-control-modern"
                    min="0"
                    max="100"
                    value="{{ $p->nilai_existing->nilai_uas ?? '' }}">

            </td>

        </tr>

    @endforeach

</tbody>

</table>

</div>

</div>

</div>

<div class="mt-4 d-flex justify-content-end">

    <button class="btn btn-success rounded-pill px-5">

        <i class="bi bi-check-circle-fill me-2"></i>

        Simpan Nilai

    </button>

</div>

</form>

@else

<div class="card border-0 shadow-sm rounded-4">

    <div class="card-body py-5">

        <div class="text-center">

            <i class="bi bi-journal-x display-3 text-secondary"></i>

            <h4 class="fw-bold mt-3">

                Belum Ada Data

            </h4>

            <p class="text-muted mb-0">

                Pilih kelas dan mata pelajaran terlebih dahulu untuk menampilkan daftar siswa.

            </p>

        </div>

    </div>

</div>

@endif

</div>

<style>

.card{

    transition:.25s;

}

.card:hover{

    box-shadow:0 18px 45px rgba(37,99,235,.08)!important;

}

.student-icon{

    width:46px;

    height:46px;

    border-radius:50%;

    background:linear-gradient(135deg,#2563eb,#0f4c81);

    color:#fff;

    display:flex;

    align-items:center;

    justify-content:center;

    font-weight:700;

    flex-shrink:0;

}

.table thead th{

    background:#f8fafc;

    color:#64748b;

    text-transform:uppercase;

    font-size:.82rem;

    font-weight:700;

    letter-spacing:.5px;

    border:0;

    padding:18px;

}

.table tbody td{

    padding:18px;

    vertical-align:middle;

    border-color:#eef2f7;

}

.table-hover tbody tr{

    transition:.2s;

}

.table-hover tbody tr:hover{

    background:#f8fbff;

}

.form-control-modern{

    border-radius:12px;

    text-align:center;

    font-weight:600;

    min-width:90px;

}

.form-control-modern:focus{

    border-color:#2563eb;

    box-shadow:0 0 0 .2rem rgba(37,99,235,.15);

}

.badge{

    font-weight:600;

    letter-spacing:.3px;

}

.btn{

    font-weight:600;

    transition:.2s;

}

.btn:hover{

    transform:translateY(-2px);

}

@media(max-width:768px){

    .student-icon{

        width:40px;

        height:40px;

        font-size:.9rem;

    }

}

</style>

@endsection