@extends('layouts.app')

@section('title', 'Status Validasi')

@section('content')

<div class="container-fluid py-3">

    {{-- HEADER --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2">Status Validasi Jadwal</h3>
                    <p class="mb-0 opacity-75">Kelola status validasi jadwal oleh kepala sekolah</p>
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
                                    
                                    @elseif($item->status == 'published')

                                        <span class="badge bg-primary">
                                            Published
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