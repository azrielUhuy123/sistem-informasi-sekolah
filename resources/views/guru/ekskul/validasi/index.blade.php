@extends('layouts.app')

@section('title', 'Status Validasi')

@section('content')

<div class="container-fluid py-4">

    @php

        $total = $jadwal->count();

        $approved = $jadwal->where('status','approved')->count();

        $submitted = $jadwal->where('status','submitted')->count();

        $published = $jadwal->where('status','published')->count();

        $rejected = $jadwal->where('status','rejected')->count();

    @endphp

    {{-- HERO --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 hero">

        <div class="card-body p-4 p-lg-5 text-white">

            <div class="row align-items-center g-4">

                <div class="col-lg-8">

                    <div class="d-flex align-items-start gap-3">

                        <div class="hero-icon">

                            <i class="bi bi-trophy-fill"></i>

                        </div>

                        <div>

                            <h2 class="hero-title mb-2">

                                Validasi Jadwal Ekskul

                            </h2>

                            <p class="hero-subtitle mb-0">

                                Pantau proses persetujuan jadwal ekstrakurikuler yang diajukan kepada Kepala Sekolah.

                            </p>

                        </div>

                    </div>

                </div>

                <div class="col-lg-4">

                    <div class="hero-box">

                        <div class="small fw-semibold text-white-50 mb-2">

                            Total Pengajuan

                        </div>

                        <div class="hero-stat">

                            {{ $total }}

                        </div>

                        <div class="small text-white-50">

                            Jadwal ekskul terdaftar

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- SUMMARY --}}
    <div class="row g-3 mb-4">

        <div class="col-lg-3 col-6">

            <div class="summary-card">

                <div class="summary-icon bg-warning-subtle text-warning">

                    <i class="bi bi-send-check-fill"></i>

                </div>

                <h3 class="fw-bold mb-1">{{ $submitted }}</h3>

                <small class="text-muted">Submitted</small>

            </div>

        </div>

        <div class="col-lg-3 col-6">

            <div class="summary-card">

                <div class="summary-icon bg-success-subtle text-success">

                    <i class="bi-check-circle-fill"></i>

                </div>

                <h3 class="fw-bold mb-1">{{ $approved }}</h3>

                <small class="text-muted">Approved</small>

            </div>

        </div>

        <div class="col-lg-3 col-6">

            <div class="summary-card">

                <div class="summary-icon bg-primary-subtle text-primary">

                    <i class="bi bi-broadcast-pin"></i>

                </div>

                <h3 class="fw-bold mb-1">{{ $published }}</h3>

                <small class="text-muted">Published</small>

            </div>

        </div>

        <div class="col-lg-3 col-6">

            <div class="summary-card">

                <div class="summary-icon bg-danger-subtle text-danger">

                    <i class="bi bi-x-octagon-fill"></i>

                </div>

                <h3 class="fw-bold mb-1">{{ $rejected }}</h3>

                <small class="text-muted">Rejected</small>

            </div>

        </div>

    </div>

    {{-- ALERT SUCCESS --}}
    @if(session('success'))

        <div class="alert alert-success border-0 shadow-sm rounded-4">

            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

        </div>

    @endif

    {{-- ALERT ERROR --}}
    @if(session('error'))

        <div class="alert alert-danger border-0 shadow-sm rounded-4">

            <i class="bi bi-exclamation-triangle-fill me-2"></i>

            {{ session('error') }}

        </div>

    @endif

    {{-- TABLE --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

        <div class="card-header bg-white border-0 py-4 px-4">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                <div>

                    <h5 class="fw-bold mb-1">Riwayat Validasi Ekskul</h5>

                    <small class="text-muted">Status persetujuan setiap jadwal ekstrakurikuler.</small>

                </div>

                <span class="badge bg-primary rounded-pill px-3 py-2">

                    {{ $total }} Jadwal

                </span>

            </div>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0 table-modern">

                    <thead>

                        <tr>

                            <th>Program</th>

                            <th>Hari</th>

                            <th>Jam</th>

                      

                            <th>Status</th>

                            <th>Catatan</th>

                        </tr>

                    </thead>

                    <tbody>

    @forelse($jadwal as $item)

        @php

            switch($item->status){

                case 'submitted':
                    $badge='warning';
                    $icon='bi-send-check-fill';
                    $label='Submitted';
                    break;

                case 'approved':
                    $badge='success';
                    $icon='bi-check-circle-fill';
                    $label='Approved';
                    break;

                case 'rejected':
                    $badge='danger';
                    $icon='bi-x-circle-fill';
                    $label='Rejected';
                    break;

                case 'published':
                    $badge='primary';
                    $icon='bi-megaphone-fill';
                    $label='Published';
                    break;

                default:
                    $badge='secondary';
                    $icon='bi-pencil-square';
                    $label='Draft';
                    break;

            }

        @endphp

        <tr>

            <td>

                <div class="d-flex align-items-center">

                    <div class="table-icon me-3">

                        <i class="bi bi-stars"></i>

                    </div>

                    <div>

                        <div class="fw-semibold">

                            {{ $item->ekskul->nama }}

                        </div>

                        <small class="text-muted">

                            Program Ekskul

                        </small>

                    </div>

                </div>

            </td>

            <td>

                <span class="badge bg-light text-dark rounded-pill px-3 py-2">

                    <i class="bi bi-calendar3 me-1"></i>

                    {{ $item->hari->nama ?? '-' }}

                </span>

            </td>

            <td>

                <small class="text-muted">

                    {{ substr($item->jam_mulai,0,5) }}

                    -

                    {{ substr($item->jam_selesai,0,5) }}

                </small>

            </td>


            <td>

                <span class="badge bg-{{ $badge }} rounded-pill px-3 py-2">

                    <i class="bi {{ $icon }} me-1"></i>

                    {{ $label }}

                </span>

            </td>

            <td>

                @if($item->catatan)

                    <div class="note-box">

                        <i class="bi bi-chat-left-text-fill me-2 text-danger"></i>

                        {{ $item->catatan }}

                    </div>

                @else

                    <span class="text-muted">

                        -

                    </span>

                @endif

            </td>

        </tr>

    @empty

        <tr>

            <td colspan="6">

                <div class="text-center py-5 empty-state">

                    <div class="empty-icon">

                        <i class="bi bi-calendar2-x"></i>

                    </div>

                    <h5 class="fw-bold mt-3 mb-2">

                        Belum Ada Data Validasi

                    </h5>

                    <p class="text-muted mb-0">

                        Jadwal ekstrakurikuler yang diajukan akan muncul pada halaman ini setelah proses pengajuan dilakukan.

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
    font-size: 1.45rem;
    backdrop-filter: blur(10px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.12);
}

.hero-title {
    font-size: clamp(1.35rem, 2vw, 1.8rem);
    font-weight: 700;
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
    padding: 1.2rem 1.3rem;
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

.card {
    transition: .25s;
}

.card:hover {
    box-shadow: 0 18px 45px rgba(37,99,235,.08)!important;
}

.summary-card {
    background: #fff;
    border-radius: 20px;
    padding: 24px;
    text-align: center;
    box-shadow: 0 .125rem .5rem rgba(0,0,0,.05);
    transition: .25s;
    height: 100%;
}

.summary-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 16px 40px rgba(37,99,235,.10);
}

.summary-icon {
    width: 60px;
    height: 60px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    font-size: 1.35rem;
}

.table-modern thead th {
    background: #f8fbff;
    color: #64748b;
    text-transform: uppercase;
    font-size: .82rem;
    letter-spacing: .5px;
    font-weight: 700;
    border: 0;
    padding: 18px;
}

.table-modern tbody td {
    padding: 18px;
    border-color: #eef2f7;
    vertical-align: middle;
}

.table-modern tbody tr {
    transition: .2s;
}

.table-modern tbody tr:hover {
    background: #f8fbff;
}

.table-icon {
    width: 48px;
    height: 48px;
    border-radius: 15px;
    background: linear-gradient(135deg, #2563eb, #0f4c81);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
}

.note-box {
    background: #fff5f5;
    border-left: 4px solid #dc3545;
    padding: 10px 14px;
    border-radius: 10px;
    font-size: .92rem;
}

.badge {
    font-weight: 600;
    letter-spacing: .3px;
}

.alert {
    border-radius: 18px;
}

.empty-state {
    padding: 2rem 1rem;
}

.empty-icon {
    width: 72px;
    height: 72px;
    margin: 0 auto 1rem;
    border-radius: 24px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    background: #f2f7ff;
    color: #2563eb;
}

@media(max-width:768px){

    .summary-card {
        padding: 18px;
    }

    .summary-icon,
    .table-icon {
        width: 45px;
        height: 45px;
        font-size: 1rem;
    }

}

</style>

@endsection