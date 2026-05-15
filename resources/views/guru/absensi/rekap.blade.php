@extends('layouts.app')

@section('title', 'Rekap Absensi')

@section('content')

<div class="container-fluid py-3">

    <div class="mb-4">

        <h4 class="fw-bold mb-1">

            Rekap Absensi

        </h4>

        <small class="text-muted">

            Kelas {{ $kelas->nama_kelas }}

        </small>

    </div>

    {{-- FILTER --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="GET">

                <div class="row align-items-end">

                    {{-- MODE --}}
                    <div class="col-md-3">

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
                    <div class="col-md-3">

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
                                    {{ $bulan == $i ? 'selected' : '' }}
                                >

                                    {{ date('F', mktime(0,0,0,$i,1)) }}

                                </option>

                            @endfor

                        </select>

                    </div>

                    {{-- TAHUN --}}
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

                    <div class="col-md-3 d-flex gap-3">

                        <button class="btn btn-primary w-100">

                            Filter Rekap

                        </button>
                        <a
                            href="{{ route('guru.absensi.export', request()->query()) }}"
                            class="btn btn-danger w-100"
                        >

                            Export PDF

                        </a>
                    </div>

                </div>


            </form>

        </div>

    </div>

    {{-- TABLE --}}
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

                        @foreach($rekap as $i => $r)

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

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>
<script>

    function toggleBulanField()
    {
        const mode = document.getElementById('modeRekap').value;

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