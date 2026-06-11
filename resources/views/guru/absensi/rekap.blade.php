@extends('layouts.app')

@section('title', 'Statistik Absensi')

@section('content')

<div class="container-fluid py-3">

    <div class="mb-4">

        <h4 class="fw-bold mb-1">

            Statistik Absensi Mapel

        </h4>

        <small class="text-muted">

            Statistik absensi siswa berdasarkan mata pelajaran

        </small>

    </div>

    {{-- FILTER --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="GET">

                <div class="row g-3 align-items-end">

                    {{-- KELAS --}}
                    <div class="col-md-2">

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

                    {{-- MAPEL --}}
                    <div class="col-md-2">

                        <label class="form-label">

                            Mata Pelajaran

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

                    {{-- MODE --}}
                    <div class="col-md-2">

                        <label class="form-label">

                            Mode Rekap

                        </label>

                        <select
                            id="modeRekap"
                            name="mode"
                            class="form-select"
                        >

                            <option
                                value="semester"
                                {{ $mode == 'semester' ? 'selected' : '' }}
                            >

                                Semester

                            </option>

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

                    {{-- BULAN --}}
                    <div class="col-md-2">

                        <label class="form-label">

                            Bulan

                        </label>

                        <select
                            id="bulanField"
                            name="bulan"
                            class="form-select"
                        >

                            @for($i = 1; $i <= 12; $i++)

                                <option
                                    value="{{ $i }}"
                                    {{
                                        $bulan == $i
                                        ? 'selected'
                                        : ''
                                    }}
                                >

                                    {{ date('F', mktime(0,0,0,$i,1)) }}

                                </option>

                            @endfor

                        </select>

                    </div>

                    {{-- TAHUN --}}
                    <div class="col-md-2">

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

                    {{-- BUTTON --}}
                    <div class="col-md-2 d-flex gap-2">

                        <button class="btn btn-primary w-100">

                            Filter

                        </button>

                    </div>

                </div>

                @if($selected)

                    <div class="mt-4 d-flex gap-2">

                        <a
                            href="{{ route(
                                'guru.absensi.export',
                                [
                                    'kelas_id' => $kelasId,
                                    'mapel_id' => $mapelId,
                                    'mode' => $mode,
                                    'bulan' => $bulan,
                                    'tahun' => $tahun,
                                ]
                            ) }}"
                            class="btn btn-danger"
                        >

                            Export PDF

                        </a>

                    </div>

                @endif

            </form>

        </div>

    </div>

    {{-- TABLE --}}
    @if($selected)

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <div class="mb-4">

                    <h5 class="fw-bold mb-1">

                        {{ $selected->kelas->nama_kelas }}

                        -
                        {{ $selected->mapel->nama_mapel }}

                    </h5>

                    <small class="text-muted">

                        Statistik absensi siswa berdasarkan mata pelajaran

                    </small>

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

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($rekap as $i => $r)

                                <tr>

                                    <td>

                                        {{ $i + 1 }}

                                    </td>

                                    <td>

                                        {{ $r['siswa']->nama }}

                                    </td>

                                    <td class="fw-semibold text-success">

                                        {{ $r['hadir'] }}

                                    </td>

                                    <td class="fw-semibold text-warning">

                                        {{ $r['izin'] }}

                                    </td>

                                    <td class="fw-semibold text-info">

                                        {{ $r['sakit'] }}

                                    </td>

                                    <td class="fw-semibold text-danger">

                                        {{ $r['alpha'] }}

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

    @endif

</div>

<script>

    function toggleBulanField()
    {
        const mode =
            document.getElementById('modeRekap').value;

        const bulanField =
            document.getElementById('bulanField');

        bulanField.disabled =
            mode !== 'bulanan';
    }

    document
        .getElementById('modeRekap')
        .addEventListener(
            'change',
            toggleBulanField
        );

    toggleBulanField();

</script>

@endsection