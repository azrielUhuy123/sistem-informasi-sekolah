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

                <div class="badge bg-white text-primary px-3 py-2 fs-6">
                    <i class="bi bi-shield-check me-2"></i> Sinergi-MJ
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
                $draftCountTingkat = $kelasList->filter(function ($kelas) {
                    return ($kelas->keputusanKenaikan->first()?->approval_status ?? 'draft') === 'draft';
                })->count();

                $pendingCountTingkat = $kelasList->filter(function ($kelas) {
                    return ($kelas->keputusanKenaikan->first()?->approval_status ?? 'draft') === 'pending';
                })->count();

                $rejectedCountTingkat = $kelasList->filter(function ($kelas) {
                    return ($kelas->keputusanKenaikan->first()?->approval_status ?? 'draft') === 'rejected';
                })->count();

                $approvedCountTingkat = $kelasList->filter(function ($kelas) {
                    return ($kelas->keputusanKenaikan->first()?->approval_status ?? 'draft') === 'approved';
                })->count();

                $slug = Str::slug((string) $tingkat);
                $collapseId = 'tingkat-' . $slug;
                $headingId = 'heading-' . $slug;
            @endphp

            <div class="border-0 rounded-3 mb-3 monitoring-kenaikan-section">                
                <button 
                    class="btn btn-light w-100 text-start rounded-3 d-flex align-items-center justify-content-between gap-3 p-3 monitoring-kenaikan-trigger" 
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#{{ $collapseId }}"
                    aria-controls="{{ $collapseId }}"
                    aria-expanded="false"
                >
                    <span class="d-flex align-items-center gap-3 min-w-0">
                        <i class="bi bi-mortarboard-fill text-primary fs-5 flex-shrink-0"></i>
                        <span class="min-w-0">
                            <span class="d-block fw-semibold text-truncate">Kelas {{ $tingkat }}</span>   
                            <small class="d-block text-muted text-truncate">{{ $pendingCountTingkat }} Pengajuan Baru</small>                         
                        </span>
                    </span>            
                    <span class="d-flex align-items-center gap-2 flex-shrink-0">
                        <small class="text-muted monitoring-kenaikan-count">{{ $kelasList->count() }} Kelas</small>
                        <i class="bi bi-chevron-down monitoring-kenaikan-chevron"></i>
                    </span>        
                </button>        

                <div id="{{ $collapseId }}" class="collapse" data-bs-parent="#accordionKenaikanKelas">
                    <div class="border-top p-3">                    
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>                                        
                                        <th>Kelas</th>
                                        <th>Wali Kelas</th>
                                        <th width="140">Total Ditolak</th>
                                        <th width="140">Total Disetujui</th>
                                        <th width="140">Jumlah Siswa</th>
                                        <th width="140">Status</th>
                                        <th width="120">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($kelasList as $kelas)
                                        @php
                                            $draft = $kelas->keputusanKenaikan
                                                ->where('approval_status', 'draft')
                                                ->count();
                                            $pending = $kelas->keputusanKenaikan
                                                ->where('approval_status', 'pending')
                                                ->count();
                                            $rejected = $kelas->keputusanKenaikan
                                                ->where('approval_status', 'rejected')
                                                ->count();
                                            $approved = $kelas->keputusanKenaikan
                                                ->where('approval_status', 'approved')
                                                ->count();
                                            $total = $kelas->keputusanKenaikan->count();
                                            /*
                                            * Prioritas status kelas:
                                            *
                                            * approved penuh  -> Diterima
                                            * ada pending      -> Pending
                                            * ada rejected     -> Ditolak
                                            * selain itu       -> Draft
                                            *
                                            * Jika masih ada siswa pending, status kelas
                                            * tetap Pending walaupun sebagian sudah approved/rejected.
                                            */
                                            if ($total > 0 && $approved === $total) {
                                                $status = 'approved';
                                            } elseif ($pending > 0) {
                                                $status = 'pending';
                                            } elseif ($rejected > 0) {
                                                $status = 'rejected';
                                            } elseif ($draft > 0) {
                                                $status = 'draft';
                                            } else {
                                                $status = 'draft';
                                            }
                                            $waliKelasNama = optional($kelas->waliKelas)
                                                ->guru
                                                ?->pegawai
                                                ?->nama;
                                        @endphp
                                        <tr>                                            
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
                                            <td>
                                                {{ $rejected }}
                                            </td>

                                            <td>
                                                {{ $approved }}
                                            </td>
                                            <td>{{ $kelas->penempatan->count() }}</td>
                                            <td>
                                                @if ($status == 'pending')
                                                    <span class="badge-status pending">Menunggu Persetujuan</span>
                                                @elseif ($status == 'rejected')
                                                    <span class="badge-status nonaktif">Ditolak</span>
                                                @elseif ($status == 'approved')
                                                    <span class="badge-status aktif">Disetujui</span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('superadmin.monitoring-kenaikan.show', $kelas) }}"
                                                    class="btn btn-primary btn-sm rounded-pill px-3 py-0">
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
                                                <h5 class="mt-3">Belum ada data kelas.</h5>
                                                <p class="text-muted mb-0">
                                                    Belum terdapat pengajuan kenaikan kelas pada tingkat ini.
                                                </p>                                                
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
<style>

.monitoring-kenaikan-section {
    background: #fff;
    border-color: #dee2e6!important;
    overflow: hidden;
    transition: border-color .2s ease, box-shadow .2s ease;
}

.monitoring-kenaikan-section:has(.collapse.show) {
    border-color: #b8d4f5!important;
    box-shadow: 0 .25rem .75rem rgba(15, 76, 129, .06);
}

.monitoring-kenaikan-trigger {
    border: 0;
    color: #212529;
    transition: background-color .2s ease;
}

.monitoring-kenaikan-trigger:hover,
.monitoring-kenaikan-trigger:focus-visible {
    background-color: #f8fbff;
}

.monitoring-kenaikan-trigger:focus-visible {
    box-shadow: 0 0 0 .2rem rgba(37, 99, 235, .15);
}

.monitoring-kenaikan-count {
    white-space: nowrap;
}

.monitoring-kenaikan-chevron {
    transition: transform .2s ease;
}

@media (max-width: 575.98px) {
    .monitoring-kenaikan-trigger {
        padding: .75rem!important;
    }

    .monitoring-kenaikan-trigger > .d-flex:first-child {
        gap: .6rem!important;
    }

    .monitoring-kenaikan-count {
        font-size: .75rem;
    }
}

</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.monitoring-kenaikan-section').forEach(function (section) {
            const collapse = section.querySelector('.collapse');
            const trigger = section.querySelector('.monitoring-kenaikan-trigger');
            const chevron = section.querySelector('.monitoring-kenaikan-chevron');

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
@endsection
