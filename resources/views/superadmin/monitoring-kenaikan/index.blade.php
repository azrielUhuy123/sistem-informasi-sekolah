@extends('layouts.app')

@section('title', 'Monitoring Kenaikan Kelas')

@section('content')

<div class="container-fluid dt-siswa-page">

    <x-table.page-header
        icon="bi-mortarboard"
        eyebrow="Monitoring / Kenaikan Kelas"
        title="Monitoring Kenaikan Kelas"
        description="Pantau pengajuan kenaikan kelas dari wali kelas untuk tahun ajaran aktif."
    />

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
                        <x-table.toolbar
                            table-id="dt-superadmin-monitoring-kenaikan-{{ $slug }}"
                            :column-toggle="true"
                            :refreshable="true"
                            :refresh-url="request()->fullUrl()"
                        >
                            <x-slot:columnMenu>
                                @foreach ([
                                    'class' => 'Kelas',
                                    'homeroom' => 'Wali Kelas',
                                    'rejected' => 'Total Ditolak',
                                    'approved' => 'Total Disetujui',
                                    'students' => 'Jumlah Siswa',
                                    'status' => 'Status',
                                    'action' => 'Aksi',
                                ] as $column => $label)
                                    <label class="dt-column-menu__option">
                                        <input type="checkbox" data-dt-column="{{ $column }}" checked>
                                        <span>{{ $label }}</span>
                                    </label>
                                @endforeach
                            </x-slot:columnMenu>
                        </x-table.toolbar>
                        <div class="table-responsive dt-siswa-table-wrap">
                            <table id="dt-superadmin-monitoring-kenaikan-{{ $slug }}" class="table table-hover align-middle mb-0 dt-table">
                                <colgroup>
                                    <col data-dt-column="class">
                                    <col data-dt-column="homeroom">
                                    <col data-dt-column="rejected">
                                    <col data-dt-column="approved">
                                    <col data-dt-column="students">
                                    <col data-dt-column="status">
                                    <col data-dt-column="action">
                                </colgroup>
                                <thead>
                                    <tr>                                        
                                        <th data-dt-column="class">Kelas</th>
                                        <th data-dt-column="homeroom">Wali Kelas</th>
                                        <th width="140" data-dt-column="rejected">Total Ditolak</th>
                                        <th width="140" data-dt-column="approved">Total Disetujui</th>
                                        <th width="140" data-dt-column="students">Jumlah Siswa</th>
                                        <th width="140" data-dt-column="status">Status</th>
                                        <th width="120" data-dt-column="action">Aksi</th>
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
                                            <td data-dt-column="class">
                                                <div class="fw-semibold">{{ $kelas->nama_kelas_label }}</div>
                                            </td>
                                            <td data-dt-column="homeroom">
                                                @php
                                                    $waliKelasNama = optional($kelas->waliKelas)
                                                        ->guru
                                                        ?->pegawai
                                                        ?->nama;
                                                @endphp

                                                {{ $waliKelasNama ?? '-' }}
                                            </td>
                                            <td data-dt-column="rejected">
                                                {{ $rejected }}
                                            </td>

                                            <td data-dt-column="approved">
                                                {{ $approved }}
                                            </td>
                                            <td data-dt-column="students">{{ $kelas->penempatan->count() }}</td>
                                            <td data-dt-column="status">
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
                                            <td data-dt-column="action">
                                                <a href="{{ route('superadmin.monitoring-kenaikan.show', $kelas) }}"
                                                    class="btn btn-primary btn-sm rounded-pill px-3 py-0">
                                                    Lihat
                                                    <i class="bi bi-chevron-right ms-1"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <x-table.empty
                                            :colspan="7"
                                            title="Belum ada data kelas"
                                            description="Belum terdapat pengajuan kenaikan kelas pada tingkat ini."
                                        />
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
