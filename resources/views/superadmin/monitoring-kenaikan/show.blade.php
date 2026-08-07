@extends('layouts.app')

@section('title', 'Monitoring Kenaikan Kelas')

@section('content')

<div class="container-fluid">

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white"
            style="background:linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center">
                <div>
                    <h2 class="fw-bold mb-2">Monitoring Kenaikan Kelas</h2>
                    <p class="mb-0 opacity-75">
                        Monitoring hasil pengajuan kenaikan kelas yang diajukan oleh wali kelas.
                    </p>
                </div>

                <div class="mt-3 mt-lg-0">
                    <div class="bg-white rounded-4 px-4 py-3 text-center">
                        <h2 class="fw-bold text-primary mb-0">{{ $tahunAktif->nama_tahun ?? '-' }}</h2>
                        <small class="text-muted">Tahun Ajaran Aktif</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('superadmin.monitoring-kenaikan.index') }}"
                        class="btn btn-secondary rounded-pill px-4">
                        <i class="bi bi-arrow-left me-2"></i>
                        Kembali
                    </a>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    @if($approvalStatus == 'pending')
                        <button class="btn btn-danger rounded-pill px-4"
                            data-bs-toggle="modal"
                            data-bs-target="#rejectModal">
                            <i class="bi bi-x-circle-fill me-2"></i>
                            Reject
                        </button>

                        <button class="btn btn-success rounded-pill px-4"
                            data-bs-toggle="modal"
                            data-bs-target="#finalApproveModal">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            Final Approve
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <small class="text-muted">Nama Kelas</small>
                    <div class="fw-bold">{{ $kelas->nama_kelas_label }}</div>
                </div>
                <div class="col-md-4">
                    <small class="text-muted">Tingkat</small>
                    <div class="fw-bold">{{ $kelas->kelasGrup->kelasMaster->tingkat ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                    <small class="text-muted">Wali Kelas</small>
                    <div class="fw-bold">
                        {{ optional(optional($kelas->waliKelas)->guru->pegawai)->nama ?? '-' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body text-center">
                    <i class="bi bi-people-fill text-primary display-6"></i>
                    <h3 class="fw-bold mt-2 mb-0">{{ $totalSiswa }}</h3>
                    <small class="text-muted">Total Siswa</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body text-center">
                    <i class="bi bi-arrow-up-circle-fill text-success display-6"></i>
                    <h3 class="fw-bold mt-2 mb-0">{{ $jumlahNaik }}</h3>
                    <small class="text-muted">Naik Kelas</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body text-center">
                    <i class="bi bi-arrow-down-circle-fill text-danger display-6"></i>
                    <h3 class="fw-bold mt-2 mb-0">{{ $jumlahTinggal }}</h3>
                    <small class="text-muted">Tinggal Kelas</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body text-center">
                    <i class="bi bi-shield-check text-warning display-6"></i>
                    <h3 class="fw-bold mt-2 mb-0">
                        @php
                            $badgeClass = match ($approvalStatus) {
                                'pending' => 'bg-warning text-dark',
                                'rejected' => 'bg-danger',
                                'approved' => 'bg-success',
                                default => 'bg-secondary',
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }} px-3 py-2">{{ strtoupper($approvalStatus) }}</span>
                    </h3>
                    <small class="text-muted">Status Approval</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Siswa</th>
                            <th>Keputusan</th>
                            <th>Catatan Wali Kelas</th>
                            <th>Status Approval</th>
                            <th>Catatan Kepala Sekolah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($keputusan as $item)
                            @php
                                $statusBadge = match ($item->approval_status ?? 'draft') {
                                    'pending' => 'bg-warning text-dark',
                                    'rejected' => 'bg-danger',
                                    'approved' => 'bg-success',
                                    default => 'bg-secondary',
                                };

                                $keputusanBadge = $item->status_keputusan === 'naik' ? 'bg-success' : 'bg-danger';
                            @endphp

                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $item->siswa->nama ?? '-' }}</td>
                                <td>
                                    @if(($item->status_keputusan ?? '') === 'naik')
                                        <span class="badge bg-success px-3 py-2">NAIK KELAS</span>
                                    @elseif(($item->status_keputusan ?? '') === 'tinggal')
                                        <span class="badge bg-danger px-3 py-2">TINGGAL KELAS</span>
                                    @else
                                        <span class="badge bg-secondary px-3 py-2">-</span>
                                    @endif
                                </td>
                                <td>{{ $item->catatan ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $statusBadge }} px-3 py-2">
                                        {{ strtoupper($item->approval_status ?? 'draft') }}
                                    </span>
                                </td>
                                <td>{{ $item->catatan_kepsek ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="alert alert-light text-muted mb-0">
                                        <i class="bi bi-info-circle me-2"></i>
                                        Belum ada data pengajuan kenaikan kelas.
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

<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Reject Pengajuan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('superadmin.monitoring-kenaikan.reject', $kelas) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="catatanKepsek" class="form-label">Alasan Reject</label>
                        <textarea class="form-control" id="catatanKepsek" name="catatan_kepsek" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="finalApproveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Final Approve</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('superadmin.monitoring-kenaikan.approve', $kelas) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Persetujuan ini bersifat FINAL. Seluruh siswa akan diproses naik kelas sesuai keputusan wali kelas dan tindakan ini tidak dapat dibatalkan.
                    </div>
                    <div class="mb-3">
                        <label for="confirmation" class="form-label">Konfirmasi</label>
                        <input type="text" class="form-control" id="confirmation" name="confirmation" placeholder="Ketik NAIK KELAS" required>
                        <div class="form-text">Ketik NAIK KELAS untuk melanjutkan.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Final Approve</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
