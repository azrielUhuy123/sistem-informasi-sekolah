@extends('layouts.app')

@section('title', 'Monitoring Kenaikan Kelas')

@section('content')

<div class="container-fluid">

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body text-white p-4 p-lg-5"
            style="background:linear-gradient(135deg,#0f4c81,#2563eb);">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h2 class="fw-bold mb-2">Monitoring Kenaikan Kelas</h2>
                    <p class="mb-0 opacity-75">
                        Monitoring pengajuan kenaikan kelas yang diajukan oleh wali kelas untuk tahun ajaran aktif.
                    </p>
                </div>

                <div class="bg-white rounded-4 text-center px-4 py-3 mt-3 mt-lg-0">
                    <h2 class="fw-bold text-primary mb-0">{{ $approvedCount }}</h2>
                    <small class="text-muted">Disetujui</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted">Draft</small>
                        <h2 class="fw-bold text-secondary mb-0">{{ $draftCount }}</h2>
                    </div>
                    <div class="fs-2 text-secondary">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted">Pending</small>
                        <h2 class="fw-bold text-warning mb-0">{{ $pendingCount }}</h2>
                    </div>
                    <div class="fs-2 text-warning">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted">Rejected</small>
                        <h2 class="fw-bold text-danger mb-0">{{ $rejectedCount }}</h2>
                    </div>
                    <div class="fs-2 text-danger">
                        <i class="bi bi-x-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted">Approved</small>
                        <h2 class="fw-bold text-success mb-0">{{ $approvedCount }}</h2>
                    </div>
                    <div class="fs-2 text-success">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="accordion" id="accordionKenaikanKelas">
        @php
            use Illuminate\Support\Str;
        @endphp

        @foreach($kelasPerTingkat as $tingkat => $kelasList)
            @php
                $slug = Str::slug((string) $tingkat);
                $collapseId = 'tingkat-' . $slug;
                $headingId = 'heading-' . $slug;
            @endphp

            <div class="accordion-item border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <h2 class="accordion-header" id="{{ $headingId }}">
                    <button class="accordion-button collapsed fw-bold" type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#{{ $collapseId }}">
                        <i class="bi bi-mortarboard-fill text-primary me-2"></i>
                        Tingkat {{ $tingkat }}
                    </button>
                </h2>

                <div id="{{ $collapseId }}" class="accordion-collapse collapse" data-bs-parent="#accordionKenaikanKelas">
                    <div class="accordion-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kelas</th>
                                        <th>Wali Kelas</th>
                                        <th>Jumlah Siswa</th>
                                        <th>Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($kelasList as $kelas)
                                        @php
                                            $status = $kelas->keputusanKenaikan->first()?->approval_status ?? 'draft';
                                        @endphp

                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="fw-semibold">{{ $kelas->nama_kelas_label }}</div>
                                            </td>
                                            <td>
                                                @php
                                                    $waliKelasNama = optional($kelas->waliKelas)
                                                        ->guru
                                                        ?->pegawai
                                                        ?->nama;
                                                @endphp

                                                {{ $waliKelasNama ?? '-' }}
                                            </td>
                                            <td>{{ $kelas->penempatan->count() }}</td>
                                            <td>
                                                @php
                                                    $badgeClass = match ($status) {
                                                        'pending' => 'bg-warning text-dark',
                                                        'rejected' => 'bg-danger',
                                                        'approved' => 'bg-success',
                                                        default => 'bg-secondary',
                                                    };
                                                @endphp

                                                <span class="badge {{ $badgeClass }} px-3 py-2">
                                                    {{ strtoupper($status) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('superadmin.monitoring-kenaikan.show', $kelas) }}"
                                                    class="btn btn-primary btn-sm rounded-pill px-3">
                                                    <i class="bi bi-eye-fill me-1"></i>
                                                    Lihat
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6">
                                                <div class="alert alert-light text-muted mb-0">
                                                    <i class="bi bi-info-circle me-2"></i>
                                                    Belum ada data kelas.
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
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
