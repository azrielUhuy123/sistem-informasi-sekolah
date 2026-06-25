@extends('layouts.app')

@section('title', 'Detail Guru')

@section('content')

<div class="container-fluid py-3">

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <div class="row">

                <div class="col-md-4">

                    <div class="d-flex align-items-center">

                        @if($guru->foto)

                            <img
                                src="{{ asset('foto-guru/' . $guru->foto) }}"
                                alt="{{ $guru->pegawai->nama }}"
                                class="rounded-circle border shadow-sm"
                                width="90"
                                height="90"
                                style="object-fit: cover;">

                        @else

                            <div
                                class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center"
                                style="width:90px;height:90px;font-size:32px;">

                                {{ strtoupper(substr($guru->pegawai->nama, 0, 2)) }}

                            </div>

                        @endif

                        <div class="ms-3">

                            <h4 class="mb-1">
                                {{ $guru->pegawai->nama }}
                            </h4>

                            <div class="text-muted">
                                NIP {{ $guru->nip ?? '-' }}
                            </div>

                            <div class="text-muted">
                                {{ $guru->email ?? '-' }}
                            </div>

                        </div>

                    </div>

                </div>

                <div class="col-md-8">

                    <h5 class="fw-bold mb-3">
                        Informasi Guru
                    </h5>

                    <table class="table table-sm">

                        <tr>
                            <th width="220">
                                Jenis Kelamin
                            </th>

                            <td>

                                @if($guru->jenis_kelamin == 'L')

                                    <span class="badge bg-primary">
                                        Laki-Laki
                                    </span>

                                @elseif($guru->jenis_kelamin == 'P')

                                    <span class="badge bg-danger">
                                        Perempuan
                                    </span>

                                @else

                                    {{ $guru->jenis_kelamin }}

                                @endif

                            </td>

                        </tr>

                        <tr>

                            <th>Telepon</th>

                            <td>
                                {{ $guru->telepon ?: '-' }}
                            </td>

                        </tr>

                        <tr>

                            <th>Alamat</th>

                            <td>
                                {{ $guru->alamat ?: '-' }}
                            </td>

                        </tr>

                        <tr>

                            <th>Status Akademik</th>

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

                        </tr>

                        @if($guru->waliKelas)

                            <tr>

                                <th>Wali Kelas</th>

                                <td>

                                    <span class="badge bg-success">

                                        {{ $guru->waliKelas->kelas->nama_kelas ?? '-' }}

                                    </span>

                                </td>

                            </tr>

                        @endif

                        <tr>

                            <th>Mata Pelajaran Diampu</th>

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

                        </tr>

                    </table>

                    <div class="mt-3">

                        <a href="{{ route('superadmin.guru.index') }}"
                           class="btn btn-secondary">

                            Kembali

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection