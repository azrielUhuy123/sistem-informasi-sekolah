@extends('layouts.app')

@section('title', 'Daftar Anggota Ekskul')

@section('content')

@php
    $memberItems = $anggota instanceof \Illuminate\Pagination\LengthAwarePaginator ? $anggota->getCollection() : $anggota;
    $totalAnggota = $anggota instanceof \Illuminate\Pagination\LengthAwarePaginator ? $anggota->total() : $memberItems->count();
    $aktifAnggota = $totalAnggota;
    $anggotaBaru = $memberItems->filter(function ($item) {
        return $item->created_at && $item->created_at->isCurrentMonth();
    })->count();
    $ekskulCount = $memberItems->pluck('ekskul_id')->filter()->unique()->count();
@endphp

<div class="container-fluid py-4">

    {{-- HERO --}}
    <div class="hero card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="hero-icon d-inline-flex align-items-center justify-content-center rounded-circle">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <span class="badge rounded-pill bg-white bg-opacity-20 px-3 py-2 fw-semibold">
                            <i class="bi bi-shield-check me-2"></i> Enterprise Panel
                        </span>
                    </div>
                    <h3 class="hero-title fw-bold mb-3">Daftar Anggota Ekstrakurikuler</h3>
                    <p class="hero-subtitle mb-0">Kelola seluruh anggota ekstrakurikuler, pantau status keanggotaan siswa, dan lakukan administrasi anggota dalam satu halaman.</p>
                </div>
                <div class="col-lg-4">
                    <div class="hero-box glass-card p-4 rounded-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-semibold opacity-75">Total Anggota</span>
                            <span class="badge rounded-pill bg-white bg-opacity-20 px-3 py-2">Live</span>
                        </div>
                        <div class="hero-stat fw-bold">{{ $totalAnggota }}</div>
                        <p class="mb-0 opacity-75">Siswa terdaftar dalam ektrakurikuler</p>
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

    {{-- SUMMARY CARDS --}}
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
                    <h6 class="fw-semibold text-muted mb-2">Anggota Aktif</h6>
                    <h3 class="fw-bold mb-0">{{ $aktifAnggota }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 summary-card h-100">
                <div class="card-body p-4">
                    <div class="summary-icon rounded-circle d-inline-flex align-items-center justify-content-center mb-3">
                        <i class="bi bi-person-plus-fill"></i>
                    </div>
                    <h6 class="fw-semibold text-muted mb-2">Anggota Baru</h6>
                    <h3 class="fw-bold mb-0">{{ $anggotaBaru }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 summary-card h-100">
                <div class="card-body p-4">
                    <div class="summary-icon rounded-circle d-inline-flex align-items-center justify-content-center mb-3">
                        <i class="bi bi-award-fill"></i>
                    </div>
                    <h6 class="fw-semibold text-muted mb-2">Ekstrakurikuler</h6>
                    <h3 class="fw-bold mb-0">{{ $ekskulCount }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- SEARCH --}}
    <div class="card border-0 shadow-sm rounded-4 search-card mb-4">
        <div class="card-body p-4 p-lg-5">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                <div>
                    <h5 class="fw-bold mb-1">Cari Anggota</h5>
                    <p class="text-muted mb-0">Cari berdasarkan Nama ataupun NIS.</p>
                </div>
                <span class="badge rounded-pill bg-primary-subtle text-primary px-3 py-2">
                    <i class="bi bi-funnel-fill me-2"></i> Pencarian Cepat
                </span>
            </div>

            <form method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-10">
                        <div class="position-relative">
                            <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                            <input
                                type="text"
                                name="search"
                                class="form-control form-control-lg ps-5 rounded-pill"
                                placeholder="Cari Nama atau NIS..."
                                value="{{ $search }}"
                            >
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <button class="btn btn-primary btn-lg w-100 rounded-pill">
                            <i class="bi bi-search me-2"></i>Cari
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden table-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-modern align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="70">No</th>
                            <th>Nama</th>
                            <th>NIS</th>
                            <th>Tanggal Bergabung</th>
                            <th>Status</th>
                            <th width="140">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($anggota as $a)
                            @php
                                $namaSiswa = optional($a->siswa)->nama ?? '';
                                $inisial = strtoupper(substr(trim($namaSiswa), 0, 1));
                            @endphp
                            <tr>
                                <td class="fw-semibold text-muted">
                                    {{ $anggota->firstItem() + $loop->index }}
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="member-avatar rounded-circle d-inline-flex align-items-center justify-content-center text-white fw-bold">
                                            {{ $inisial ?: 'A' }}
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="fw-semibold text-dark">{{ $a->siswa->nama }}</span>
                                            <small class="text-muted">Anggota Ekstrakurikuler</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge rounded-pill bg-light text-secondary px-3 py-2">
                                        {{ $a->siswa->nis }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-semibold text-dark">{{ $a->created_at->translatedFormat('d F Y') }}</span>
                                        <small class="text-muted">{{ $a->created_at->diffForHumans() }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge status-badge rounded-pill bg-success-subtle text-success px-3 py-2">
                                        <i class="bi bi-check-circle-fill me-2"></i>Aktif
                                    </span>
                                </td>
                                <td>
                                    <form
                                        action="{{ route('guru.ekskul.daftar-anggota.destroy', $a->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Keluarkan anggota ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn action-btn btn-sm rounded-pill">
                                            <i class="bi bi-person-dash-fill me-2"></i>Keluarkan
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="empty-state">
                                        <div class="empty-icon mb-3">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <h5 class="fw-bold mb-2">Belum Ada Anggota</h5>
                                        <p class="text-muted mb-0">Belum ada siswa yang menjadi anggota ekstrakurikuler ini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="mt-4 d-flex justify-content-center">
                {{ $anggota->links() }}
            </div>

        </div>
    </div>

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
    .search-card,
    .table-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .summary-card:hover,
    .search-card:hover,
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

    .search-card {
        border: 1px solid rgba(37, 99, 235, 0.08);
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

    .status-badge {
        font-weight: 600;
        letter-spacing: 0.02em;
    }

    .action-btn {
        background: #fff5f5;
        color: #dc2626;
        border: 1px solid #fed7d7;
        transition: all 0.2s ease;
    }

    .action-btn:hover {
        background: #dc2626;
        color: #fff;
        box-shadow: 0 10px 20px rgba(220, 38, 38, 0.16);
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

    .pagination .page-item .page-link {
        border: 0;
        border-radius: 999px;
        margin: 0 4px;
        color: #2563eb;
        box-shadow: 0 8px 20px rgba(15, 76, 129, 0.08);
        transition: all 0.2s ease;
    }

    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);
        color: #fff;
    }

    .pagination .page-item .page-link:hover {
        background: #eff6ff;
        color: #0f4c81;
    }

    @media (max-width: 767.98px) {
        .hero-box {
            margin-top: 0.5rem;
        }

        .search-card .btn {
            width: 100%;
        }
    }
</style>

@endsection