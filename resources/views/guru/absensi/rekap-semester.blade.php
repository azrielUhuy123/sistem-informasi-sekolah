@extends('layouts.app')

@section('title','Rekap Semester')

@section('content')

<div class="container-fluid py-3">

    <div class="mb-4">

        <h4 class="fw-bold mb-1">
            Rekap Semester
        </h4>

        <small class="text-muted">
            Rekap kehadiran siswa per bulan
        </small>

    </div>

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