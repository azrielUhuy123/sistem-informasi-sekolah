@extends('layouts.app')

@section('title', 'Jadwal Kelas')

@section('content')

<div class="container-fluid">

    <h2 class="mb-4">
        Monitoring Jadwal
    </h2>

    <div class="row mb-4">

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="ca rd-body">
                    <h6>Submitted</h6>
                    <h3 class="text-warning">
                        {{ \App\Models\PengajuanJadwal::where('status', 'submitted')->count() }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6>Approved</h6>
                    <h3 class="text-success">
                        {{ \App\Models\PengajuanJadwal::where('status', 'approved')->count() }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6>Rejected</h6>
                    <h3 class="text-danger">
                        {{ \App\Models\PengajuanJadwal::where('status', 'rejected')->count() }}
                    </h3>
                </div>
            </div>
        </div>

    </div>

    <div class="accordion" id="accordionTingkat">

        @foreach($kelasPerTingkat as $tingkat => $kelasList)

            @php
                $pendingCount = $kelasList->sum(function($kelas) {
                    return $kelas->pengajuanJadwal
                        ->where('status', 'submitted')
                        ->count();
                });
            @endphp

            <div class="accordion-item mb-3 border-0 shadow-sm">

                <h2 class="accordion-header">

                    <button class="accordion-button collapsed"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#tingkat{{ $tingkat }}">

                        <div class="d-flex justify-content-between w-100 me-3">

                            <div>
                                <strong>Kelas Tingkat {{ $tingkat }}</strong>
                            </div>

                            <div>
                                <span class="badge bg-warning">
                                    {{ $pendingCount }} Pending
                                </span>
                            </div>

                        </div>

                    </button>

                </h2>

                <div id="tingkat{{ $tingkat }}"
                     class="accordion-collapse collapse"
                     data-bs-parent="#accordionTingkat">

                    <div class="accordion-body">

                        <div class="table-responsive">

                            <table class="table align-middle">

                                <thead>
                                    <tr>
                                        <th>Kelas</th>
                                        <th>Status</th>
                                        <th>Total Jadwal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>

                                <tbody>

                                @foreach($kelasList as $kelas)

                                    @php
                                        $latestStatus = optional(
                                            $kelas->pengajuanJadwal->last()
                                        )->status;
                                    @endphp

                                    <tr>

                                        <td>
                                            <strong>
                                                {{ $kelas->nama_kelas }}
                                            </strong>
                                        </td>

                                        <td>

                                            @if($latestStatus == 'submitted')

                                                <span class="badge bg-warning">
                                                    Submitted
                                                </span>

                                            @elseif($latestStatus == 'approved')

                                                <span class="badge bg-success">
                                                    Approved
                                                </span>

                                            @elseif($latestStatus == 'rejected')

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
                                            {{ $kelas->pengajuanJadwal->count() }}
                                        </td>

                                        <td>

                                            <a href="{{ route('superadmin.monitoring-jadwal.show', $kelas->id) }}"
                                               class="btn btn-sm btn-primary">

                                                Lihat

                                            </a>

                                        </td>

                                    </tr>

                                @endforeach

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

        @endforeach

    </div>

</div>

@endsection