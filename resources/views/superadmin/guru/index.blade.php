@extends('layouts.app')

@section('title', 'Guru & Wali Kelas')

@section('content')

<div class="container-fluid py-3">

    <h4 class="fw-bold mb-4">
        Guru & Wali Kelas
    </h4>

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <table class="table align-middle">

                <thead>
                    <tr>
                        <th>Nama Guru</th>
                        <th>Status Akademik</th>
                        <th>Mata Pelajaran</th>
                        <th>Kelas</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($gurus as $guru)

                        <tr>

                            <td>
                                {{ $guru->nama }}
                            </td>

                            <td>

                                @if($guru->waliKelas)

                                    <span class="badge bg-primary">
                                        Wali Kelas
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        Guru Bidang
                                    </span>

                                @endif

                            </td>

                            <td>

                                @if($guru->waliKelas)

                                    @php

                                        $mapelInti = [
                                            'Bahasa Indonesia',
                                            'Matematika',
                                            'Pendidikan Pancasila',
                                            'Ilmu Pengetahuan Alam dan Sosial',
                                        ];

                                    @endphp

                                    @foreach($mapelInti as $mapel)

                                        <span class="badge bg-light text-dark border">

                                            {{ $mapel }}

                                        </span>

                                    @endforeach

                                @else

                                    @foreach($guru->mapels as $mapel)

                                        <span class="badge bg-light text-dark border">

                                            {{ $mapel->nama_mapel }}

                                        </span>

                                    @endforeach

                                @endif

                            </td>
                                                        <td>

                                @if($guru->waliKelas)

                                    {{ $guru->waliKelas->kelas->nama_kelas }}

                                @else

                                    -

                                @endif

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection