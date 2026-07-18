@extends('layouts.app')

@section('title', 'Absensi Ekskul')

@section('content')

@php
    $totalAnggota = $anggota->count();
    $summaryCounts = ['hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alpha' => 0];

    foreach ($anggota as $a) {
        $absen = \App\Models\AbsensiEkskul::where('ekskul_id', $ekskul->id)
            ->where('siswa_id', $a->siswa_id)
            ->where('tanggal', $tanggal)
            ->first();

        $status = $absen->status ?? 'hadir';
        $summaryCounts[$status] = ($summaryCounts[$status] ?? 0) + 1;
    }

    $hadirCount = $summaryCounts['hadir'] ?? 0;
    $izinSakitCount = ($summaryCounts['izin'] ?? 0) + ($summaryCounts['sakit'] ?? 0);
    $alphaCount = $summaryCounts['alpha'] ?? 0;
@endphp

<div class="container-fluid py-4">

    {{-- HERO --}}
    <div class="hero card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="hero-icon d-inline-flex align-items-center justify-content-center rounded-circle">
                            <i class="bi bi-clipboard2-check-fill"></i>
                        </div>
                        <span class="badge rounded-pill bg-white bg-opacity-20 px-3 py-2 fw-semibold">
                            <i class="bi bi-shield-check me-2"></i> Enterprise Panel
                        </span>
                    </div>
                    <h3 class="hero-title fw-bold mb-3">Absensi Ekstrakurikuler</h3>
                    <p class="hero-subtitle mb-0">Kelola kehadiran anggota ekstrakurikuler pada setiap pertemuan secara cepat menggunakan sistem absensi digital.</p>
                </div>
                <div class="col-lg-4">
                    <div class="hero-box glass-card p-4 rounded-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-semibold opacity-75">Total Anggota</span>
                            <span class="badge rounded-pill bg-white bg-opacity-20 px-3 py-2">Live</span>
                        </div>
                        <div class="hero-stat fw-bold">{{ $totalAnggota }}</div>
                        <p class="mb-0 opacity-75">Jumlah anggota ekskul.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ALERT SUCCESS --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 px-4 py-3 mb-4">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- ALERT ERROR --}}
    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm rounded-4 px-4 py-3 mb-4">
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- SUMMARY CARD --}}
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 summary-card h-100">
                <div class="card-body p-4">
                    <div class="summary-icon rounded-circle d-inline-flex align-items-center justify-content-center mb-3">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <h6 class="fw-semibold text-muted mb-2">Total Anggota</h6>
                    <h3 class="fw-bold mb-0">{{ $totalAnggota }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 summary-card h-100">
                <div class="card-body p-4">
                    <div class="summary-icon rounded-circle d-inline-flex align-items-center justify-content-center mb-3">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <h6 class="fw-semibold text-muted mb-2">Hadir</h6>
                    <h3 class="fw-bold mb-0">{{ $hadirCount }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 summary-card h-100">
                <div class="card-body p-4">
                    <div class="summary-icon rounded-circle d-inline-flex align-items-center justify-content-center mb-3">
                        <i class="bi bi-emoji-smile-fill"></i>
                    </div>
                    <h6 class="fw-semibold text-muted mb-2">Izin + Sakit</h6>
                    <h3 class="fw-bold mb-0">{{ $izinSakitCount }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 summary-card h-100">
                <div class="card-body p-4">
                    <div class="summary-icon rounded-circle d-inline-flex align-items-center justify-content-center mb-3">
                        <i class="bi bi-x-circle-fill"></i>
                    </div>
                    <h6 class="fw-semibold text-muted mb-2">Alpha</h6>
                    <h3 class="fw-bold mb-0">{{ $alphaCount }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTER --}}
    <div class="card border-0 shadow-sm rounded-4 info-card mb-4">
        <div class="card-body p-4 p-lg-5">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-start gap-3 mb-4">
                <div>
                    <h5 class="fw-bold mb-2">Informasi Pertemuan</h5>
                    <p class="text-muted mb-0">Ringkasan detail kegiatan ekstrakurikuler hari ini.</p>
                </div>
                <span class="badge rounded-pill bg-primary-subtle text-primary px-3 py-2">
                    <i class="bi bi-calendar2-week-fill me-2"></i> Pertemuan Aktif
                </span>
            </div>

            <form method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-8">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-trophy-fill me-2 text-primary"></i>Ekskul
                        </label>
                        <input
                            type="text"
                            class="form-control modern-input"
                            value="{{ $ekskul->nama }}"
                            readonly
                        >
                    </div>
                    <div class="col-lg-4">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-calendar-event-fill me-2 text-primary"></i>Tanggal Pertemuan
                        </label>
                        <input
                            type="date"
                            name="tanggal"
                            class="form-control modern-input"
                            value="{{ $tanggal }}"
                            onchange="this.form.submit()"
                        >
                    </div>
                </div>
            </form>

            <div class="row g-3 mt-2">
                <div class="col-md-3 col-sm-6">
                    <div class="info-pill">
                        <small class="text-muted d-block mb-1">Nama Ekskul</small>
                        <div class="fw-semibold text-dark">{{ $ekskul->nama }}</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="info-pill">
                        <small class="text-muted d-block mb-1">Hari</small>
                        <div class="fw-semibold text-dark">{{ $ekskul->hari }}</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="info-pill">
                        <small class="text-muted d-block mb-1">Jam</small>
                        <div class="fw-semibold text-dark">{{ substr($ekskul->jam_mulai,0,5) }} - {{ substr($ekskul->jam_selesai,0,5) }}</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="info-pill">
                        <small class="text-muted d-block mb-1">Total Anggota</small>
                        <div class="fw-semibold text-dark">{{ $totalAnggota }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FORM --}}
    <form action="{{ route('guru.ekskul.absensi.store') }}" method="POST">
        @csrf
        <input type="hidden" name="tanggal" value="{{ $tanggal }}">

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden table-card">
            <div class="card-body p-4 p-lg-5">
                <div class="action-toolbar d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                    <div>
                        <h5 class="fw-bold mb-1">Daftar Kehadiran Anggota</h5>
                        <small class="text-muted">Total anggota: {{ $totalAnggota }}</small>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <select class="form-select modern-input" id="bulkAction" style="width:180px">
                            <option value="">Aksi Massal</option>
                            <option value="hadir">Hadir</option>
                            <option value="izin">Izin</option>
                            <option value="sakit">Sakit</option>
                            <option value="alpha">Alpha</option>
                        </select>
                        <button class="btn action-btn rounded-pill px-4 py-2 shadow-sm">
                            <i class="bi bi-save-fill me-2"></i>Simpan Absensi
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-modern align-middle mb-0">
                        <thead>
                            <tr>
                                <th width="60">
                                    <input type="checkbox" id="tableCheckAll" class="form-check-input modern-checkbox">
                                </th>
                                <th width="70">No</th>
                                <th>Avatar</th>
                                <th>Nama</th>
                                <th width="120">NIS</th>
                                <th width="140">Jam Input</th>
                                <th width="200">Update Terakhir</th>
                                <th width="180">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($anggota as $i => $a)
                                @php
                                    $absen = \App\Models\AbsensiEkskul::where('ekskul_id', $ekskul->id)
                                        ->where('siswa_id', $a->siswa_id)
                                        ->where('tanggal', $tanggal)
                                        ->first();

                                    $status = $absen->status ?? 'hadir';
                                    $namaSiswa = $a->siswa->nama ?? '';
                                    $inisial = strtoupper(substr(trim($namaSiswa), 0, 1));
                                @endphp

                                <tr>
                                    <td>
                                        <input type="checkbox" class="form-check-input student-checkbox modern-checkbox">
                                    </td>
                                    <td class="fw-semibold text-muted">{{ $i + 1 }}</td>
                                    <td>
                                        <div class="member-avatar rounded-circle d-inline-flex align-items-center justify-content-center text-white fw-bold">
                                            {{ $inisial ?: 'A' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-semibold text-dark">{{ $a->siswa->nama }}</span>
                                            <small class="text-muted">Anggota Ekstrakurikuler</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill bg-light text-secondary px-3 py-2">
                                            {{ $a->siswa->nis }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2 text-muted">
                                            <i class="bi bi-clock-fill"></i>
                                            <span>{{ optional($absen?->created_at)->format('H:i') ?? 'Belum Diisi' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2 text-muted">
                                            <i class="bi bi-clock-history"></i>
                                            <span>{{ optional($absen?->updated_at)->format('d M Y H:i') ?? 'Belum Pernah' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="hidden" name="absensi[{{ $a->siswa_id }}][status]" value="{{ $status }}" class="statusInput">
                                        <div class="status-badge">
                                            <span class="badge statusBadge bg-{{ match($status) { 'hadir' => 'success', 'izin' => 'warning', 'sakit' => 'info', default => 'danger' } }}">
                                                {{ ucfirst($status) }}
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="empty-state">
                                            <div class="empty-icon mb-3">
                                                <i class="bi bi-clipboard-x"></i>
                                            </div>
                                            <h5 class="fw-bold mb-2">Belum Ada Anggota</h5>
                                            <p class="text-muted mb-0">Belum terdapat anggota ekstrakurikuler yang dapat dilakukan absensi.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    .hero {
        background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);
        position: relative;
        overflow: hidden;
    }

    .hero::after {
        content: "";
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at top right, rgba(255,255,255,0.2), transparent 35%);
        pointer-events: none;
    }

    .hero-icon {
        width: 48px;
        height: 48px;
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(10px);
        color: #fff;
        font-size: 1.25rem;
    }

    .hero-title {
        font-size: clamp(1.5rem, 2.2vw, 2rem);
        letter-spacing: -0.02em;
    }

    .hero-subtitle {
        font-size: 1rem;
        line-height: 1.7;
        color: rgba(255,255,255,0.9);
        max-width: 720px;
    }

    .hero-stat {
        font-size: 2.2rem;
        line-height: 1;
    }

    .hero-box {
        background: rgba(255,255,255,0.16);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(255,255,255,0.24);
        box-shadow: 0 16px 40px rgba(0, 0, 0, 0.12);
    }

    .glass-card {
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
    }

    .summary-card,
    .info-card,
    .table-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .summary-card:hover,
    .info-card:hover,
    .table-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 16px 40px rgba(15, 76, 129, 0.12) !important;
    }

    .summary-icon {
        width: 46px;
        height: 46px;
        background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);
        color: #fff;
        font-size: 1.1rem;
        box-shadow: 0 10px 20px rgba(37, 99, 235, 0.22);
    }

    .info-card {
        border: 1px solid rgba(37, 99, 235, 0.08);
    }

    .info-pill {
        padding: 1rem;
        border-radius: 18px;
        background: linear-gradient(135deg, #f8fbff, #f2f7ff);
        border: 1px solid #e8f0fb;
        height: 100%;
    }

    .modern-input {
        border: 1px solid #e5eef9;
        box-shadow: inset 0 1px 2px rgba(15, 76, 129, 0.04);
    }

    .member-avatar {
        width: 44px;
        height: 44px;
        min-width: 44px;
        background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);
        box-shadow: 0 8px 18px rgba(37, 99, 235, 0.18);
        font-size: 1rem;
    }

    .table-modern thead th {
        background: #f8fbff;
        color: #475569;
        font-weight: 700;
        border-bottom: 1px solid #e5eef9;
        padding-top: 1rem;
        padding-bottom: 1rem;
    }

    .table-modern tbody tr {
        transition: background-color 0.2s ease;
    }

    .table-modern tbody tr:hover {
        background: #f3f8ff;
    }

    .table-modern td {
        padding-top: 1rem;
        padding-bottom: 1rem;
        border-color: #eef4fb;
    }

    .modern-checkbox {
        width: 1.1rem;
        height: 1.1rem;
        border-radius: 0.35rem;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
    }

    .statusBadge {
        padding: 0.66rem 0.95rem;
        border-radius: 999px;
        font-weight: 600;
        letter-spacing: 0.02em;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
    }

    .statusBadge.bg-success::before {
        content: "\F633";
        font-family: bootstrap-icons;
    }

    .statusBadge.bg-warning::before {
        content: "\F5A9";
        font-family: bootstrap-icons;
    }

    .statusBadge.bg-info::before {
        content: "\F7A5";
        font-family: bootstrap-icons;
    }

    .statusBadge.bg-danger::before {
        content: "\F620";
        font-family: bootstrap-icons;
    }

    .action-toolbar {
        padding: 1rem 1.25rem;
        border: 1px solid #edf4fb;
        border-radius: 1.25rem;
        background: linear-gradient(135deg, #f9fcff, #f3f8ff);
    }

    .action-btn {
        background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);
        color: #fff;
        border: 0;
        transition: all 0.2s ease;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(37, 99, 235, 0.24);
        color: #fff;
    }

    .empty-state {
        color: #64748b;
    }

    .empty-icon {
        width: 72px;
        height: 72px;
        margin: 0 auto;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(15, 76, 129, 0.1), rgba(37, 99, 235, 0.12));
        color: #2563eb;
        font-size: 2rem;
    }

    @media (max-width: 767.98px) {
        .hero-box {
            margin-top: 0.5rem;
        }

        .action-toolbar {
            align-items: stretch;
        }

        .action-toolbar .d-flex {
            width: 100%;
        }

        .action-btn,
        .form-select {
            width: 100%;
        }
    }
</style>

<script>
document
.getElementById('tableCheckAll')
.addEventListener('change', function() {
    document
    .querySelectorAll('.student-checkbox')
    .forEach(cb => {
        cb.checked = this.checked;
    });
});

document
.getElementById('bulkAction')
.addEventListener('change', function() {
    let action = this.value;

    if(!action) return;

    document
    .querySelectorAll('tbody tr')
    .forEach(row => {
        let check = row.querySelector('.student-checkbox');

        if(check && check.checked){
            let input = row.querySelector('.statusInput');
            let badge = row.querySelector('.statusBadge');

            input.value = action;
            badge.className = 'badge statusBadge';

            if(action === 'hadir')
                badge.classList.add('bg-success');
            else if(action === 'izin')
                badge.classList.add('bg-warning');
            else if(action === 'sakit')
                badge.classList.add('bg-info');
            else
                badge.classList.add('bg-danger');

            badge.innerText = action.charAt(0).toUpperCase() + action.slice(1);
        }
    });
});
</script>

@endsection
