@extends('layouts.app')

@section('title','Rekap Semester')

@section('content')

<div class="container-fluid py-3">

    {{-- HEADER --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2">Manajemen Rekap Semester</h3>
                    <p class="mb-0 opacity-75">Monitoring kehadiran siswa per semester</p>
                </div>
                <div class="badge bg-white text-primary px-3 py-2 fs-6">
                    <i class="bi bi-shield-check me-2"></i> Sistem Terhubung
                </div>
            </div>
        </div>
    </div>


    {{-- ALERT SUCCESS --}}
    @if(session('success'))

        <div class="alert alert-success border-0 shadow-sm">

            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}

        </div>

    @endif

    {{-- ALERT ERROR --}}
    @if(session('error'))

        <div class="alert alert-danger border-0 shadow-sm">

            <i class="bi bi-exclamation-circle-fill me-2"></i>
            {{ session('error') }}

        </div>

    @endif

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <form method="GET">

                <div class="row g-3">

                    <div class="col-md-5">

                        <label>
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

                    <div class="col-md-5">

                        <label>
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

                    <div class="col-md-2 d-grid">

                        <label>&nbsp;</label>

                        <button
                            class="btn btn-primary"
                        >
                            Filter
                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

    @if($selected)

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <div class="table-responsive">

                <table
                    class="table table-bordered align-middle text-center"
                >

                    <thead>

                        <tr>

                            <th>
                                Nama
                            </th>

                            <th>Jan</th>
                            <th>Feb</th>
                            <th>Mar</th>
                            <th>Apr</th>
                            <th>Mei</th>
                            <th>Jun</th>
                            <th>Jul</th>
                            <th>Agu</th>
                            <th>Sep</th>
                            <th>Okt</th>
                            <th>Nov</th>
                            <th>Des</th>

                            <th>
                                Total
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($rekap as $row)

                            <tr>

                                <td class="text-start">

                                    {{ $row['siswa']->nama }}

                                </td>

                                @for($i=1;$i<=12;$i++)

                                    <td>

                                        {{ $row['bulan'][$i] }}

                                    </td>

                                @endfor

                                <td
                                    class="fw-bold text-success"
                                >

                                    {{ $row['total'] }}

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