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

<div class="container-fluid dt-siswa-page">

    <x-table.page-header
        icon="bi-calendar-week"
        eyebrow="Monitoring / Jadwal Pelajaran"
        title="Monitoring Jadwal"
        description="Pantau pengajuan jadwal pembelajaran pada setiap tingkat kelas."
    />
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
                        <x-table.toolbar
                            table-id="dt-superadmin-monitoring-jadwal-{{ $slug }}"
                            :column-toggle="true"
                            :refreshable="true"
                            :refresh-url="request()->fullUrl()"
                        >
                            <x-slot:columnMenu>
                                @foreach ([
                                    'class' => 'Kelas',
                                    'rejected' => 'Total Ditolak',
                                    'approved' => 'Total Diterima',
                                    'status' => 'Status',
                                    'schedule_count' => 'Jumlah Jadwal',
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
                            <table id="dt-superadmin-monitoring-jadwal-{{ $slug }}" class="table table-hover align-middle mb-0 dt-table">
                                <colgroup>
                                    <col data-dt-column="class">
                                    <col data-dt-column="rejected">
                                    <col data-dt-column="approved">
                                    <col data-dt-column="status">
                                    <col data-dt-column="schedule_count">
                                    <col data-dt-column="action">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th data-dt-column="class">Kelas</th>
                                        <th width="140" data-dt-column="rejected">Total Ditolak</th>
                                        <th width="140" data-dt-column="approved">Total Diterima</th>
                                        <th width="180" data-dt-column="status">Status</th>
                                        <th width="140" data-dt-column="schedule_count">Jumlah Jadwal</th>
                                        <th width="120" data-dt-column="action">Aksi</th>
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
                                            <td data-dt-column="class">
                                                <div class="fw-semibold">{{ $itemKelas->nama_kelas_label }}</div>
                                            </td>
                                            <td data-dt-column="rejected">
                                                {{ $rejected }}
                                            </td>
                                            <td data-dt-column="approved">
                                                {{ $approved }}
                                            </td>
                                            <td data-dt-column="status">
                                                @if ($status == 'published')
                                                    <span class="badge-status selesai">Publish</span>
                                                @else ($status == 'submitted')
                                                    <span class="badge-status pending">Pending</span>                                                
                                                @endif
                                            </td>
                                            <td data-dt-column="schedule_count">{{ $total }}</td>
                                            <td data-dt-column="action">
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
                                        <x-table.empty
                                            :colspan="6"
                                            title="Belum Ada Jadwal"
                                            description="Belum terdapat pengajuan jadwal pada tingkat ini."
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