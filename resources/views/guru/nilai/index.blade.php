@extends('layouts.app')

@section('title', 'Nilai')

@section('content')

@php
    $totalSiswa = $penempatan->count();
@endphp

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white"
            style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">

            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">

                <div>
                    <h3 class="fw-bold mb-2">
                        Manajemen Nilai
                    </h3>

                    <p class="mb-0 opacity-75">
                        Kelola nilai siswa berdasarkan kelas dan mata pelajaran yang diampu.
                    </p>
                </div>

                <div class="badge bg-white text-primary px-3 py-2 fs-6">
                    <i class="bi bi-shield-check me-2"></i>
                    Sinergi-MJ
                </div>

            </div>

        </div>
    </div>

    {{-- ALERT --}}
    <x-alert.flash key="success" type="success"/>
    <x-alert.flash key="error" type="danger"/>

    {{-- FILTER --}}
    @isset($waliKelas)

        <div class="card border-0 shadow-sm rounded-4 mb-4">

            <div class="card-header bg-white border-0 pt-4 px-4">

                <h5 class="fw-bold mb-1">
                    Filter Nilai
                </h5>

                <small class="text-muted">
                    Pilih mata pelajaran untuk mengelola nilai siswa.
                </small>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Kelas
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $kelas->nama_kelas_label }}"
                            readonly
                        >

                    </div>

                    <div class="col-md-6">

                        <form method="GET">

                            <label class="form-label fw-semibold">
                                Mata Pelajaran
                            </label>

                            <select
                                name="mapel_id"
                                class="form-control"
                                onchange="this.form.submit()"
                            >

                                @foreach($mapels as $m)

                                    <option
                                        value="{{ $m->id }}"
                                        {{ $mapelId == $m->id ? 'selected' : '' }}
                                    >
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

            <div class="card-header bg-white border-0 pt-4 px-4">

                <h5 class="fw-bold mb-1">
                    Filter Nilai
                </h5>

                <small class="text-muted">
                    Pilih kelas dan mata pelajaran untuk menampilkan data nilai.
                </small>

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
                                class="form-control"
                            >

                                <option value="">
                                    Pilih Kelas
                                </option>

                                @foreach($kelasList as $k)

                                    <option
                                        value="{{ $k->id }}"
                                        {{ $kelasId == $k->id ? 'selected' : '' }}
                                    >
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
                                class="form-control"
                            >

                                <option value="">
                                    Pilih Mata Pelajaran
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

                        <div class="col-md-4">

                            <button
                                class="btn btn-primary rounded-pill w-100"
                            >
                                <i class="bi bi-search me-2"></i>
                                Tampilkan Data
                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    @endisset

    {{-- DATA NILAI --}}
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

            <x-table.card
                title="Daftar Nilai Siswa"
                subtitle="{{ $kelas->nama_kelas_label ?? '-' }} - {{ $mapels->firstWhere('id', $mapelId)?->nama_mapel ?? '-' }}"
            >

                <x-slot:badge>

                    <span class="badge bg-primary rounded-pill px-2 py-1">
                        {{ $totalSiswa }}
                        <span class="text-badge">Siswa</span>
                    </span>

                </x-slot:badge>

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead>

                            <tr>

                                <th width="70">
                                    No
                                </th>

                                <th>
                                    Nama Siswa
                                </th>

                                <th width="150">
                                    Kehadiran
                                </th>

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

                                    {{-- NO --}}
                                    <td>

                                        <span class="badge bg-light text-dark rounded-circle">
                                            {{ $i + 1 }}
                                        </span>

                                    </td>

                                    {{-- NAMA + NIS --}}
                                    <td>

                                        <div class="d-flex align-items-center">

                                            <div
                                                class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3"
                                                style="width: 48px; height: 48px;"
                                            >
                                                <i class="bi bi-person-fill fs-4"></i>
                                            </div>

                                            <div>

                                                <div class="fw-semibold">
                                                    {{ $p->siswa->nama }}
                                                </div>

                                                <small class="text-muted">
                                                    NIS : {{ $p->siswa->nis }}
                                                </small>

                                            </div>

                                        </div>

                                    </td>

                                    {{-- KEHADIRAN --}}
                                    <td>

                                        <span class="badge bg-{{ $badge }} rounded-pill px-3 py-2">

                                            <i class="bi bi-bar-chart-fill me-1"></i>

                                            {{ $persen }}%

                                        </span>

                                    </td>

                                    {{-- TUGAS --}}
                                    <td>

                                        <input
                                            type="number"
                                            name="nilai[{{ $p->siswa->id }}][tugas]"
                                            class="form-control text-center"
                                            min="0"
                                            max="100"
                                            value="{{ $p->nilai_existing->nilai_tugas ?? '' }}"
                                        >

                                    </td>

                                    {{-- UTS --}}
                                    <td>

                                        <input
                                            type="number"
                                            name="nilai[{{ $p->siswa->id }}][uts]"
                                            class="form-control text-center"
                                            min="0"
                                            max="100"
                                            value="{{ $p->nilai_existing->nilai_uts ?? '' }}"
                                        >

                                    </td>

                                    {{-- UAS --}}
                                    <td>

                                        <input
                                            type="number"
                                            name="nilai[{{ $p->siswa->id }}][uas]"
                                            class="form-control text-center"
                                            min="0"
                                            max="100"
                                            value="{{ $p->nilai_existing->nilai_uas ?? '' }}"
                                        >

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

                <div class="d-flex justify-content-end mt-4 gap-2">

                    <button
                        type="submit"
                        class="btn btn-success rounded-pill px-4"
                    >

                        <i class="bi bi-check-circle-fill me-2"></i>

                        Simpan Nilai

                    </button>

                </div>

            </x-table.card>

        </form>

    @else

        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-body py-5">

                <div class="text-center">

                    <i class="bi bi-journal-x display-5 text-secondary"></i>

                    <p class="mt-3 mb-0 text-muted">
                        Pilih kelas dan mata pelajaran terlebih dahulu untuk menampilkan daftar siswa.
                    </p>

                </div>

            </div>

        </div>

    @endif

</div>

@endsection