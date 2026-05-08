@extends('layouts.app')

@section('title', 'Status Validasi')

@section('content')

<div class="container-fluid py-3">

    {{-- HEADER --}}
    <div class="mb-4">

        <h4 class="fw-bold mb-0">
            Status Validasi Jadwal
        </h4>

        <small class="text-muted">
            Monitoring approval jadwal oleh kepala sekolah
        </small>

    </div>

    {{-- CARD --}}
    <div class="card shadow-sm border-0">

        <div class="card-body">

            <div class="table-responsive">

                <table
                    class="table table-bordered align-middle"
                >

                    <thead class="table-light">

                        <tr>

                            <th>Kelas</th>
                            <th>Hari</th>
                            <th>Jam</th>
                            <th>Mapel</th>
                            <th>Status</th>
                            <th>Catatan</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($jadwals as $item)

                            <tr>

                                <td>
                                    {{ $item->kelas->nama_kelas ?? '-' }}
                                </td>

                                <td>
                                    {{ $item->hari->nama ?? '-' }}
                                </td>

                                <td>

                                    Jam
                                    {{ $item->jamPelajaran->jam_ke }}

                                    <br>

                                    <small class="text-muted">

                                        {{ substr(
                                            $item->jamPelajaran->mulai,
                                            0,
                                            5
                                        ) }}

                                        -

                                        {{ substr(
                                            $item->jamPelajaran->selesai,
                                            0,
                                            5
                                        ) }}

                                    </small>

                                </td>

                                <td>
                                    {{ $item->mapel->nama_mapel ?? '-' }}
                                </td>

                                <td>

                                    @if($item->status == 'submitted')

                                        <span class="badge bg-warning">
                                            Submitted
                                        </span>

                                    @elseif($item->status == 'approved')

                                        <span class="badge bg-success">
                                            Approved
                                        </span>

                                    @elseif($item->status == 'rejected')

                                        <span class="badge bg-danger">
                                            Rejected
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            Draft
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    @if($item->catatan)

                                        <div
                                            class="
                                                alert
                                                alert-danger
                                                py-2
                                                px-3
                                                mb-0
                                            "
                                        >

                                            {{ $item->catatan }}

                                        </div>

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
                                    class="text-center text-muted"
                                >

                                    Belum ada data validasi

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