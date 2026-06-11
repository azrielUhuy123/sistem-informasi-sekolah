@extends('layouts.app')

@section('title', 'Rekap Bulanan')

@section('content')

<style>

.sticky-name{
    position:sticky;
    left:0;
    background:white;
    z-index:10;
}

</style>

<div class="container-fluid py-3">

<div class="mb-4">

    <h4 class="fw-bold mb-1">
        Rekap Bulanan Absensi
    </h4>

    <small class="text-muted">
        Monitoring kehadiran siswa per tanggal
    </small>

</div>

<div class="card border-0 shadow-sm mb-4">

    <div class="card-body">

        <form method="GET">

            <div class="row g-3">

                <div class="col-md-3">

                    <label class="form-label">
                        Kelas
                    </label>

                    <select
                        name="kelas_id"
                        class="form-select"
                    >

                        @foreach($kelasDiampu as $item)

                            <option
                                value="{{ $item->kelas_id }}"
                                {{
                                    $kelasId == $item->kelas_id
                                    ? 'selected'
                                    : ''
                                }}
                            >

                                {{ $item->kelas->nama_kelas }}

                            </option>

                        @endforeach

                    </select>

                </div>

                <div class="col-md-3">

                    <label class="form-label">
                        Mapel
                    </label>

                    <select
                        name="mapel_id"
                        class="form-select"
                    >

                        @foreach($kelasDiampu as $item)

                            <option
                                value="{{ $item->mapel_id }}"
                                {{
                                    $mapelId == $item->mapel_id
                                    ? 'selected'
                                    : ''
                                }}
                            >

                                {{ $item->mapel->nama_mapel }}

                            </option>

                        @endforeach

                    </select>

                </div>

                <div class="col-md-2">

                    <label class="form-label">
                        Bulan
                    </label>

                    <select
                        name="bulan"
                        class="form-select">

                        @for($i=1;$i<=12;$i++)

                            <option
                                value="{{ $i }}"
                                {{ $bulan == $i ? 'selected' : '' }}
                            >

                                {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}

                            </option>

                        @endfor

                    </select>

                </div>

                <div class="col-md-2">

                    <label class="form-label">
                        Tahun
                    </label>

                    <input
                        type="number"
                        name="tahun"
                        value="{{ $tahun }}"
                        class="form-control"
                    >

                </div>

                <div class="col-md-2 d-grid">

                    <label class="form-label">
                        &nbsp;
                    </label>

                    <button class="btn btn-primary">

                        Filter

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

@if($selected)

<div class="card border-0 shadow-sm">

    <div class="card-body">

        <div class="table-responsive">

            <table
                class="table table-bordered table-sm align-middle text-center"
            >

                <thead>

                    <tr>

                        <th class="sticky-name" rowspan="2">
                            Nama Siswa
                        </th>

                        @for($i=1;$i<=$jumlahHari;$i++)

                            <th>
                                {{ $i }}
                            </th>

                        @endfor

                        <th rowspan="2">H</th>
                        <th rowspan="2">I</th>
                        <th rowspan="2">S</th>
                        <th rowspan="2">A</th>

                    </tr>

                    <tr>

                        @for($i=1;$i<=$jumlahHari;$i++)

                            @php

                                $tanggal =
                                    \Carbon\Carbon::create(
                                        $tahun,
                                        $bulan,
                                        $i
                                    );

                            @endphp

                            <th style="font-size:10px">

                                {{ substr($tanggal->translatedFormat('D'),0,1) }}

                            </th>

                        @endfor

                    </tr>

                </thead>

                <tbody>

                    @foreach($rekap as $row)

                        <tr>

                            <td class="sticky-name text-start">

                                {{ $row['siswa']->nama }}

                            </td>

                            @for($i=1;$i<=$jumlahHari;$i++)

                                @php
                                    $absen =
                                        $row['absensi'][$i]
                                        ?? null;
                                @endphp

                                <td>

                                    @if($absen)

                                        {{ strtoupper(substr($absen->status,0,1)) }}

                                    @endif

                                </td>

                            @endfor

                            <td class="fw-bold text-success">
                                {{ $row['hadir'] }}
                            </td>

                            <td class="fw-bold text-warning">
                                {{ $row['izin'] }}
                            </td>

                            <td class="fw-bold text-info">
                                {{ $row['sakit'] }}
                            </td>

                            <td class="fw-bold text-danger">
                                {{ $row['alpha'] }}
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

</div>

@endif


</div>

@endsection
