@extends('layouts.app')

@section('title', 'Nilai')

@section('content')

<div class="container-fluid py-3">

    {{-- HEADER --}}
    <div class="mb-4">

        <h4 class="fw-bold mb-1">

            Nilai Akademik

        </h4>

        <small class="text-muted">

            Rekap nilai siswa semester aktif

        </small>

    </div>

    {{-- CARD --}}
    <div class="card border-0 shadow-sm">

        <div class="card-body">

            @if($nilai->count())

                <div class="table-responsive">

                    <table
                        class="table table-bordered align-middle"
                    >

                        {{-- HEAD --}}
                        <thead class="table-light">

                            <tr>

                                <th width="70">

                                    No

                                </th>

                                <th>

                                    Mata Pelajaran

                                </th>

                                <th>

                                    Guru

                                </th>

                                <th width="120">

                                    Tugas

                                </th>

                                <th width="120">

                                    UTS

                                </th>

                                <th width="120">

                                    UAS

                                </th>

                                <th width="140">

                                    Nilai Akhir

                                </th>

                                <th width="120">

                                    Predikat

                                </th>

                            </tr>

                        </thead>

                        {{-- BODY --}}
                        <tbody>

                            @foreach($nilai as $i => $n)

                                @php

                                    $akhir =
                                        $n->nilai_akhir;

                                    if ($akhir >= 90) {

                                        $predikat = 'A';

                                    } elseif ($akhir >= 80) {

                                        $predikat = 'B';

                                    } elseif ($akhir >= 70) {

                                        $predikat = 'C';

                                    } else {

                                        $predikat = 'D';

                                    }

                                @endphp

                                <tr>

                                    {{-- NO --}}
                                    <td>

                                        {{ $i + 1 }}

                                    </td>

                                    {{-- MAPEL --}}
                                    <td class="fw-semibold">

                                        {{ $n->mapel->nama_mapel }}

                                    </td>

                                    {{-- GURU --}}
                                    <td>

                                        {{ $n->guru->nama ?? '-' }}

                                    </td>

                                    {{-- TUGAS --}}
                                    <td>

                                        {{ $n->nilai_tugas }}

                                    </td>

                                    {{-- UTS --}}
                                    <td>

                                        {{ $n->nilai_uts }}

                                    </td>

                                    {{-- UAS --}}
                                    <td>

                                        {{ $n->nilai_uas }}

                                    </td>

                                    {{-- NILAI AKHIR --}}
                                    <td>

                                        <span
                                            class="
                                                fw-bold

                                                @if($akhir >= 90)
                                                    text-success
                                                @elseif($akhir >= 80)
                                                    text-primary
                                                @elseif($akhir >= 70)
                                                    text-warning
                                                @else
                                                    text-danger
                                                @endif
                                            "
                                        >

                                            {{ $akhir }}

                                        </span>

                                    </td>

                                    {{-- PREDIKAT --}}
                                    <td>

                                        <span
                                            class="
                                                badge

                                                @if($predikat == 'A')
                                                    bg-success
                                                @elseif($predikat == 'B')
                                                    bg-primary
                                                @elseif($predikat == 'C')
                                                    bg-warning text-dark
                                                @else
                                                    bg-danger
                                                @endif
                                            "
                                        >

                                            {{ $predikat }}

                                        </span>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                {{-- EMPTY --}}
                <div class="text-center py-5">

                    <h6 class="text-muted mb-2">

                        Belum ada data nilai

                    </h6>

                    <small class="text-muted">

                        Nilai akan tampil setelah guru menginput nilai siswa

                    </small>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection
