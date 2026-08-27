@extends('layouts.app')

@section('title','Monitoring Jadwal')

@section('content')

@php
    use App\Models\PengajuanJadwal;
    use Illuminate\Support\Str;

    $tahunId = current_tahun_id();

    $approvedCount = PengajuanJadwal::where('tahun_ajaran_id', $tahunId)
        ->where('status', 'approved')
        ->count();

    $rejectedCount = PengajuanJadwal::where('tahun_ajaran_id', $tahunId)
        ->where('status', 'rejected')
        ->count();

@endphp

<div class="container-fluid">

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2">Monitoring Jadwal</h3>
                    <p class="mb-0 opacity-75">Monitoring seluruh pengajuan jadwal pembelajaran pada setiap tingkat kelas.</p>
                </div>
                <div class="badge bg-white text-primary px-3 py-2 fs-6">
                    <i class="bi bi-shield-check me-2"></i> Sinergi-MJ
                </div>
            </div>
        </div>
    </div>    
    {{-- Monitoring per tingkat --}}

    <div id="monitoring-tingkat-list">
        @foreach ($kelasPerTingkat as $tingkat => $kelasList)
            @php
                $submittedCount = $kelasList->sum(function ($kelas) {
                    return $kelas->pengajuanJadwal
                        ->where('status', 'submitted')
                        ->count();
                });

                $publishedCount = $kelasList->sum(function ($kelas) {
                    return $kelas->pengajuanJadwal
                        ->where('status', 'published')
                        ->count();
                });

                $slug = Str::slug((string) $tingkat);
                $collapseId = "tingkat-$slug";
            @endphp

            <div class="border-0 rounded-3 mb-3 monitoring-tingkat-section">
                <button
                    type="button"
                    class="btn btn-light w-100 text-start rounded-3 d-flex align-items-center justify-content-between gap-3 p-3 monitoring-tingkat-trigger"
                    data-bs-toggle="collapse"
                    data-bs-target="#{{ $collapseId }}"
                    aria-controls="{{ $collapseId }}"
                    aria-expanded="false"
                >
                    <span class="d-flex align-items-center gap-3 min-w-0">
                        <i class="bi bi-mortarboard-fill text-primary fs-5 flex-shrink-0"></i>
                        <span class="min-w-0">
                            <span class="d-block fw-semibold text-truncate">Kelas {{ $tingkat }}</span>
                            <small class="d-block text-muted text-truncate">{{ $submittedCount }} Pengajuan Baru, {{ $publishedCount }} Sudah Publish</small>
                        </span>
                    </span>
                    <span class="d-flex align-items-center gap-2 flex-shrink-0">
                        <small class="text-muted monitoring-tingkat-count">{{ $kelasList->count() }} Kelas</small>
                        <i class="bi bi-chevron-down monitoring-tingkat-chevron"></i>
                    </span>
                </button>

                <div id="{{ $collapseId }}" class="collapse">
                    <div class="border-top p-3">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Kelas</th>
                                        <th width="140">Total Ditolak</th>
                                        <th width="140">Total Diterima</th>
                                        <th width="180">Status</th>
                                        <th width="140">Jumlah Jadwal</th>
                                        <th width="120">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($kelasList as $itemKelas)
                                        @php
                                            $submitted = $itemKelas->pengajuanJadwal
                                                ->where('status', 'submitted')
                                                ->count();
                                            $approved = $itemKelas->pengajuanJadwal
                                                ->where('status', 'approved')
                                                ->count();
                                            $rejected = $itemKelas->pengajuanJadwal
                                                ->where('status', 'rejected')
                                                ->count();
                                            $published = $itemKelas->pengajuanJadwal
                                                ->where('status', 'published')
                                                ->count();
                                            $total = $itemKelas->pengajuanJadwal->count();

                                            if ($published > 0) {
                                                $status = 'published';
                                            } elseif ($submitted > 0) {
                                                $status = 'submitted';
                                            } elseif ($approved > 0) {
                                                $status = 'approved';
                                            } elseif ($rejected > 0) {
                                                $status = 'rejected';
                                            } else {
                                                $status = 'draft';
                                            }
                                        @endphp

                                        <tr>
                                            <td>
                                                <div class="fw-semibold">{{ $itemKelas->nama_kelas_label }}</div>
                                            </td>
                                            <td>
                                                {{ $rejected }}
                                            </td>
                                            <td>
                                                {{ $approved }}
                                            </td>
                                            <td>
                                                @if ($status == 'published')
                                                    <span class="badge-status selesai">Publish</span>
                                                @else ($status == 'submitted')
                                                    <span class="badge-status pending">Pending</span>                                                
                                                @endif
                                            </td>
                                            <td>{{ $total }}</td>
                                            <td>
                                                <a
                                                    href="{{ route('superadmin.monitoring-jadwal.show', $itemKelas->id) }}"
                                                    class="btn btn-primary btn-sm rounded-pill px-3 py-0"
                                                >
                                                    Lihat
                                                    <i class="bi bi-chevron-right ms-1"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6">
                                                <div class="empty-state">
                                                    <i class="bi bi-calendar-x display-5 text-secondary"></i>
                                                    <h5 class="mt-3">Belum Ada Jadwal</h5>
                                                    <p class="text-muted mb-0">
                                                        Belum terdapat pengajuan jadwal pada tingkat ini.
                                                    </p>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.monitoring-tingkat-section').forEach(function (section) {
            const collapse = section.querySelector('.collapse');
            const trigger = section.querySelector('.monitoring-tingkat-trigger');
            const chevron = section.querySelector('.monitoring-tingkat-chevron');

            if (!collapse || !trigger || !chevron) {
                return;
            }

            collapse.addEventListener('shown.bs.collapse', function () {
                trigger.setAttribute('aria-expanded', 'true');
                chevron.classList.replace('bi-chevron-down', 'bi-chevron-up');
            });

            collapse.addEventListener('hidden.bs.collapse', function () {
                trigger.setAttribute('aria-expanded', 'false');
                chevron.classList.replace('bi-chevron-up', 'bi-chevron-down');
            });
        });
    });
</script>

<style>

.monitoring-tingkat-section {
    background: #fff;
    border-color: #dee2e6!important;
    overflow: hidden;
    transition: border-color .2s ease, box-shadow .2s ease;
}

.monitoring-tingkat-section:has(.collapse.show) {
    border-color: #b8d4f5!important;
    box-shadow: 0 .25rem .75rem rgba(15, 76, 129, .06);
}

.monitoring-tingkat-trigger {
    border: 0;
    color: #212529;
    transition: background-color .2s ease;
}

.monitoring-tingkat-trigger:hover,
.monitoring-tingkat-trigger:focus-visible {
    background-color: #f8fbff;
}

.monitoring-tingkat-trigger:focus-visible {
    box-shadow: 0 0 0 .2rem rgba(37, 99, 235, .15);
}

.monitoring-tingkat-count {
    white-space: nowrap;
}

.monitoring-tingkat-chevron {
    transition: transform .2s ease;
}

@media (max-width: 575.98px) {
    .monitoring-tingkat-trigger {
        padding: .75rem!important;
    }

    .monitoring-tingkat-trigger > .d-flex:first-child {
        gap: .6rem!important;
    }

    .monitoring-tingkat-count {
        font-size: .75rem;
    }
}

</style>

@endsection