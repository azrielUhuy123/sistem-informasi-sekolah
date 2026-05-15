@extends('layouts.app')

@section('title', 'Nilai')

@section('content')

<div class="container-fluid py-3">

    <div class="mb-4">

        <h4 class="fw-bold mb-1">

            Input Nilai

        </h4>



    </div>


    {{-- WALI KELAS --}}
    @isset($waliKelas)

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-body">

                <div class="row align-items-end">

                    <div class="col-md-6">

                        <label class="form-label">

                            Kelas

                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $kelas->nama_kelas }}"
                            readonly
                        >

                    </div>

                    <div class="col-md-6">

                        <form method="GET">

                            <label class="form-label">

                                Mata Pelajaran

                            </label>

                            <select
                                name="mapel_id"
                                class="form-select"
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

        {{-- GURU BIDANG --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-body">

                <form method="GET">

                    <div class="row align-items-end">

                        <div class="col-md-4">

                            <label class="form-label">

                                Kelas

                            </label>

                            <select
                                name="kelas_id"
                                class="form-select"
                                onchange="this.form.submit()"
                            >

                                <option value="">

                                    -- Pilih Kelas --

                                </option>

                                @foreach($kelasList as $k)

                                    <option
                                        value="{{ $k->id }}"
                                        {{ $kelasId == $k->id ? 'selected' : '' }}
                                    >

                                        {{ $k->nama_kelas }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                        <div class="col-md-4">

                            <label class="form-label">

                                Mata Pelajaran

                            </label>

                            <select
                                name="mapel_id"
                                class="form-select"
                            >

                                <option value="">

                                    -- Pilih Mata Pelajaran --

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

                            <button class="btn btn-primary w-100">

                                Tampilkan Siswa

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

                                    <tr>

                                        <td>

                                            {{ $i + 1 }}

                                        </td>

                                        <td>

                                            {{ $p->siswa->nama }}

                                        </td>
                                        <td>
                                            @if($p->persentase_kehadiran >= 90)

                                                <span class="text-success fw-semibold">

                                                    {{ $p->persentase_kehadiran }}%

                                                </span>

                                            @elseif($p->persentase_kehadiran >= 75)

                                                <span class="text-warning fw-semibold">

                                                    {{ $p->persentase_kehadiran }}%

                                                </span>

                                            @else

                                                <span class="text-danger fw-semibold">

                                                    {{ $p->persentase_kehadiran }}%

                                                </span>

                                            @endif

                                        </td>

                                        <td>

                                            <input
                                                type="number"
                                                name="nilai[{{ $p->siswa->id }}][tugas]"
                                                class="form-control"
                                                min="0"
                                                max="100"
                                                value="{{ $p->nilai_existing->nilai_tugas ?? '' }}"
                                            >

                                        </td>

                                        <td>

                                            <input
                                                type="number"
                                                name="nilai[{{ $p->siswa->id }}][uts]"
                                                class="form-control"
                                                min="0"
                                                max="100"
                                                value="{{ $p->nilai_existing->nilai_uts ?? '' }}"
                                            >

                                        </td>

                                        <td>

                                            <input
                                                type="number"
                                                name="nilai[{{ $p->siswa->id }}][uas]"
                                                class="form-control"
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

                </div>

            </div>

            <div class="mt-4 d-flex justify-content-end">

                <button class="btn btn-success px-4">

                    Simpan Nilai

                </button>

            </div>
        </form>

    @endif

</div>

@endsection