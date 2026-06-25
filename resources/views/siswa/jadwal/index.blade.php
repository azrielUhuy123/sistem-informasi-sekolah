@extends('layouts.app')

@section('title', 'Jadwal Pelajaran')

@section('content')

<div class="container-fluid py-3">

    {{-- HEADER --}}
    <div class="mb-4">

        <h4 class="fw-bold mb-1">

            Jadwal Pelajaran

        </h4>

        <small class="text-muted">

            Jadwal resmi kelas
            {{ $penempatan->kelas->nama_kelas ?? '-' }}

        </small>

    </div>

    {{-- CARD --}}
    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table
                    class="table table-bordered align-middle text-center jadwal-table"
                >

                    {{-- HEAD --}}
                    <thead class="table-light">

                        <tr>

                            <th width="130">

                                Jam

                            </th>

                            @foreach($hari as $h)

                                <th>

                                    {{ $h->nama }}

                                </th>

                            @endforeach

                        </tr>

                    </thead>

                    {{-- BODY --}}
                    <tbody>

                        @foreach($jam as $jp)

                            <tr>

                                {{-- JAM --}}
                                <td class="fw-semibold bg-light">

                                    {{ substr($jp->mulai,0,5) }}
                                    -
                                    {{ substr($jp->selesai,0,5) }}

                                </td>

                                {{-- HARI --}}
                                @foreach($hari as $h)

                                    @php

                                        $jadwal =
                                            $grid[$jp->id][$h->id]
                                            ?? null;

                                    @endphp

                                    <td
                                        style="min-width: 180px;"
                                    >

                                        @if($jadwal)

                                            {{-- MAPEL --}}
                                            <div class="fw-semibold">

                                                {{ $jadwal->mapel->nama_mapel }}

                                            </div>

                                            {{-- GURU --}}
                                            <small class="text-muted d-block mt-1">

                                                {{ $jadwal->guru->pegawai->nama }}

                                            </small>

                                        @else

                                            <span class="text-muted">

                                                -

                                            </span>

                                        @endif

                                    </td>

                                @endforeach

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection
