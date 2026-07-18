@extends('layouts.app')

@section('title', 'Jadwal Ekskul')

@section('content')

<div class="container-fluid py-4">

    @php
        $jadwalCount = $jadwal->count();
        $hariCount = $hari->count();
        $aktifCount = $jadwal->where('status', 'aktif')->count();
    @endphp

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 hero">
        <div class="card-body p-4 p-lg-5 text-white">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <div class="d-flex align-items-start gap-3">
                        <div class="hero-icon">
                            <i class="bi bi-calendar2-week"></i>
                        </div>
                        <div>
                            <h3 class="hero-title">Jadwal Ekstrakurikuler</h3>
                            <p class="hero-subtitle mb-0">Kelola jadwal kegiatan ekstrakurikuler, waktu pelaksanaan, dan seluruh agenda pembinaan siswa.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="hero-box">
                        <div class="small fw-semibold text-white-50 mb-2">Total Jadwal</div>
                        <div class="hero-stat">{{ $jadwalCount }}</div>
                        <div class="small text-white-50">Data terdaftar saat ini</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ALERT SUCCESS --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- ALERT ERROR --}}
    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            {{ session('error') }}
        </div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-4 stat-card">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 text-primary stat-icon">
                        <i class="bi bi-calendar3 fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Total Jadwal</h6>
                        <p class="text-muted mb-0">{{ $jadwalCount }} data tersimpan</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-4 stat-card">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-info bg-opacity-10 p-3 text-info stat-icon">
                        <i class="bi bi-calendar2-week fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Hari Aktif</h6>
                        <p class="text-muted mb-0">{{ $hariCount }} pilihan hari</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-4 stat-card">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3 text-warning stat-icon">
                        <i class="bi bi-clock-history fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Jadwal Aktif</h6>
                        <p class="text-muted mb-0">{{ $aktifCount }} jadwal aktif</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-4 stat-card">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3 text-success stat-icon">
                        <i class="bi bi-people fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Total Pertemuan</h6>
                        <p class="text-muted mb-0">{{ $jadwalCount }} sesi terencana</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4 form-card">
        <div class="card-body p-4 p-lg-5">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
                <div>
                    <h5 class="fw-bold mb-1">Tambah Jadwal Baru</h5>
                    <p class="text-muted mb-0">Pilih hari dan rentang waktu kegiatan ekstrakurikuler.</p>
                </div>
                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                    <i class="bi bi-plus-circle me-1"></i> Form Input
                </span>
            </div>

            <form action="{{ route('guru.ekskul.jadwal.store') }}" method="POST">
                @csrf

                <div class="row g-3 align-items-end">
                    <div class="col-lg-4 col-md-6">
                        <label class="form-label modern-label">Hari</label>
                        <select name="hari_id" class="form-select modern-input" required>
                            @foreach($hari as $h)
                                <option value="{{ $h->id }}">{{ $h->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label class="form-label modern-label">Jam Mulai</label>
                        <input type="time" name="jam_mulai" class="form-control modern-input" required>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label class="form-label modern-label">Jam Selesai</label>
                        <input type="time" name="jam_selesai" class="form-control modern-input" required>
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <button class="btn btn-primary w-100 rounded-pill fw-semibold shadow-sm">
                            <i class="bi bi-save me-2"></i>Simpan Jadwal
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-4 p-lg-5">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                <div>
                    <h5 class="fw-bold mb-1">Daftar Jadwal</h5>
                    <p class="text-muted mb-0">Riwayat jadwal yang telah ditambahkan.</p>
                </div>
                <span class="badge bg-light text-dark border rounded-pill px-3 py-2">
                    <i class="bi bi-list-ul me-1"></i> Tampilan Terstruktur
                </span>
            </div>

            <div class="table-responsive">
                <table class="table table-modern align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Hari</th>
                            <th>Jam</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jadwal as $j)
                            <tr>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $j->hari->nama }}</div>
                                    <div class="small text-muted">Kegiatan ekskul</div>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $j->jam_mulai }} - {{ $j->jam_selesai }}</div>
                                    <div class="small text-muted">Waktu pelaksanaan</div>
                                </td>
                                <td>
                                    @php
                                        $status = strtoupper($j->status);
                                    @endphp

                                    @if($status === 'AKTIF')
                                        <span class="status-badge badge-active">Aktif</span>
                                    @elseif($status === 'PENDING')
                                        <span class="status-badge badge-pending">Pending</span>
                                    @elseif($status === 'TIDAK AKTIF')
                                        <span class="status-badge badge-inactive">Tidak Aktif</span>
                                    @else
                                        <span class="status-badge badge-draft">Draft</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <button type="button" class="action-btn btn-edit">
                                            <i class="bi bi-pencil-square me-1"></i>Edit
                                        </button>
                                        <button type="button" class="action-btn btn-delete">
                                            <i class="bi bi-trash3 me-1"></i>Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state">
                                        <div class="empty-icon">
                                            <i class="bi bi-calendar-x"></i>
                                        </div>
                                        <h5 class="fw-bold mb-2">Belum Ada Jadwal</h5>
                                        <p class="mb-0">Silakan tambahkan jadwal ekstrakurikuler terlebih dahulu.</p>
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

<style>
    .hero {
        background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);
        border: 1px solid rgba(255,255,255,0.16);
    }

    .hero-icon {
        width: 56px;
        height: 56px;
        border-radius: 18px;
        background: rgba(255,255,255,0.18);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        backdrop-filter: blur(10px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.12);
    }

    .hero-title {
        font-size: clamp(1.4rem, 2vw, 1.8rem);
        font-weight: 700;
        margin-bottom: 0.4rem;
    }

    .hero-subtitle {
        color: rgba(255,255,255,0.84);
        font-size: 1rem;
        line-height: 1.7;
        max-width: 700px;
    }

    .hero-box {
        background: rgba(255,255,255,0.14);
        border: 1px solid rgba(255,255,255,0.22);
        border-radius: 22px;
        padding: 1.25rem 1.3rem;
        backdrop-filter: blur(14px);
        box-shadow: 0 12px 35px rgba(0,0,0,0.12);
    }

    .hero-stat {
        font-size: 2rem;
        font-weight: 800;
        color: #fff;
        line-height: 1;
        margin-bottom: 0.3rem;
    }

    .stat-card {
        transition: all 0.25s ease;
        border: 1px solid rgba(15, 76, 129, 0.06);
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 14px 34px rgba(37, 99, 235, 0.12) !important;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .form-card {
        border: 1px solid rgba(15, 76, 129, 0.08);
    }

    .modern-label {
        font-size: 0.9rem;
        font-weight: 600;
        color: #1f2a44;
        margin-bottom: 0.5rem;
    }

    .modern-input {
        border: 1px solid #dfe7f2;
        border-radius: 16px;
        padding: 0.8rem 1rem;
        background: #f9fbff;
        transition: all 0.2s ease;
        box-shadow: inset 0 1px 2px rgba(0,0,0,0.02);
    }

    .modern-input:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.16);
        background: #fff;
    }

    .btn-primary {
        background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);
        border: none;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #0b3d6d 0%, #1d4ed8 100%);
        transform: translateY(-1px);
    }

    .table-modern thead th {
        background: #f8fbff;
        color: #526179;
        font-size: 0.9rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        border-bottom: 1px solid #e8eef7;
        padding: 1rem 0.9rem;
    }

    .table-modern tbody td {
        padding: 1rem 0.9rem;
        border-color: #eef3f9;
        vertical-align: middle;
    }

    .table-modern tbody tr {
        transition: all 0.2s ease;
    }

    .table-modern tbody tr:hover {
        background: #f5faff;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.45rem 0.8rem;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 700;
        letter-spacing: 0.02em;
    }

    .badge-active {
        background: #e9f9f0;
        color: #198754;
    }

    .badge-pending {
        background: #fff5e8;
        color: #d97706;
    }

    .badge-inactive {
        background: #fdecec;
        color: #dc3545;
    }

    .badge-draft {
        background: #f1f3f5;
        color: #6c757d;
    }

    .action-btn {
        border: none;
        border-radius: 999px;
        padding: 0.45rem 0.8rem;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .btn-edit {
        background: #eaf2ff;
        color: #2563eb;
    }

    .btn-edit:hover {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .btn-delete {
        background: #fdecec;
        color: #dc3545;
    }

    .btn-delete:hover {
        background: #fbdada;
        color: #b42318;
    }

    .empty-state {
        text-align: center;
        padding: 2.5rem 1rem;
        color: #64748b;
    }

    .empty-icon {
        width: 72px;
        height: 72px;
        margin: 0 auto 1rem;
        border-radius: 24px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        background: #f2f7ff;
        color: #2563eb;
    }

    @media (max-width: 991.98px) {
        .hero-box {
            margin-top: 0.5rem;
        }
    }

    @media (max-width: 767.98px) {
        .hero {
            padding: 1rem;
        }

        .hero-title {
            font-size: 1.35rem;
        }

        .hero-subtitle {
            font-size: 0.95rem;
        }

        .action-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

@endsection