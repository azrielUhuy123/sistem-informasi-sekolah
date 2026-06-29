@extends('layouts.app')

@section('title', 'Guru & Wali Kelas')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2">Guru & Wali Kelas</h3>
                    <p class="mb-0 opacity-75">Kelola data guru dan wali kelas sekolah</p>
                </div>
                <div class="badge bg-white text-primary px-3 py-2 fs-6">
                    <i class="bi bi-shield-check me-2"></i> Sistem Terhubung
                </div>
            </div>
        </div>
    </div>


    <div class="card shadow-sm border-0">

        <div class="card-body">

            <table class="table align-middle">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Guru</th>
                        <th>Status Akademik</th>
                        <th>Mata Pelajaran</th>
                        <th>Kelas</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($gurus as $guru)

                        <tr>
                            <td>{{ $loop->iteration }}</td>


                            <td>
                                {{ $guru->pegawai->nama }}
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

                                    @forelse($guru->mapels as $mapel)

                                        <span class="badge bg-light text-dark border">

                                            {{ $mapel->nama_mapel }}

                                        </span>

                                    @empty

                                        <span class="text-muted">
                                            Belum ada mapel
                                        </span>

                                    @endforelse

                                @endif

                            </td>

                            <td>

                                @if($guru->waliKelas)

                                    {{ $guru->waliKelas->kelas->nama_kelas }}

                                @else

                                    -

                                @endif

                            </td>

                            <td>
                                <a href="{{ route('tu.guru.show', $guru) }}"
                                class="btn btn-sm btn-dark">
                                    Detail
                                </a>
                            </td>
                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection