@extends('layouts.app')

@section('title', 'Status Validasi')

@section('content')

<div class="container-fluid py-4">

    <div class="mb-4">

        <h3 class="fw-bold mb-1">

            Status Validasi Jadwal

        </h3>

        <p class="text-muted mb-0">

            Monitoring pengajuan jadwal ekskul

        </p>

    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>

                        <tr>

                            <th width="60">

                                No

                            </th>

                            <th>

                                Hari

                            </th>

                            <th>

                                Jam

                            </th>

                            <th>

                                Status

                            </th>

                            <th>

                                Catatan Kepala Sekolah

                            </th>

                            <th width="120">

                                Aksi

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($jadwal as $i => $j)

                            <tr>

                                <td>

                                    {{ $loop->iteration }}

                                </td>

                                <td>

                                    {{ $j->hari->nama }}

                                </td>

                                <td>

                                    {{ substr($j->jam_mulai,0,5) }}
                                    -
                                    {{ substr($j->jam_selesai,0,5) }}

                                </td>

                                <td>

                                    @switch($j->status)

                                        @case('draft')

                                            <span class="badge bg-secondary">

                                                Draft

                                            </span>

                                        @break

                                        @case('submitted')

                                            <span class="badge bg-warning text-dark">

                                                Menunggu Validasi

                                            </span>

                                        @break

                                        @case('approved')

                                            <span class="badge bg-success">

                                                Disetujui

                                            </span>

                                        @break

                                        @case('published')

                                            <span class="badge bg-primary">

                                                Dipublish

                                            </span>

                                        @break

                                        @case('rejected')

                                            <span class="badge bg-danger">

                                                Ditolak

                                            </span>

                                        @break

                                    @endswitch

                                </td>

                                <td>

                                    @if($j->status == 'rejected')

                                        <div class="text-danger fw-semibold">

                                            {{ $j->catatan }}

                                        </div>

                                    @else

                                        <span class="text-muted">

                                            -

                                        </span>

                                    @endif

                                </td>

                                <td>

                                    @if($j->status == 'rejected')

                                        <a
                                            href="{{ route('guru.ekskul.jadwal.edit', $j->id) }}"
                                            class="btn btn-warning btn-sm"
                                        >

                                            Edit

                                        </a>

                                    @else

                                        <span class="text-muted">

                                            -

                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="text-center text-muted py-4"
                                >

                                    Belum ada pengajuan jadwal

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>
        </div>

    </div>

</div>

@endsection