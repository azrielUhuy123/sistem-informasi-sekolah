@extends('layouts.public')

@section('title','PPDB SDN Mekar Jaya')

@section('content')

<style>
.hero-gradient .lead {
    color: rgba(255,255,255,.9);
}
.btn-hero {
    min-width: 170px;
}
.card.shadow-soft {
    border: none;
    border-radius: 24px;
}
.feature-icon {
    width: 64px;
    height: 64px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 18px;
    background: rgba(23,65,110,.12);
    color: #17416e;
    font-size: 1.6rem;
    margin-bottom: 1rem;
}
@media (max-width: 576px) {
    .hero-gradient {
        padding-top: 3rem;
        padding-bottom: 3rem;
    }
    .btn-hero {
        width: 100%;
    }
}
</style>

<div class="hero-gradient text-white py-5">
    <div class="container text-center">
        <div class="mx-auto mb-4" style="max-width: 720px;">
            <h1 class="display-4 fw-bold mb-3">
                PPDB SDN Mekar Jaya
            </h1>
            <p class="lead mb-4">
                Penerimaan Peserta Didik Baru
                Tahun Ajaran
                <strong>
                    {{ $setting?->tahunAjaran?->tahun }}
                </strong>
            </p>
        </div>

        <div class="d-grid gap-3 d-sm-flex justify-content-center">
            @if($setting?->status == 'dibuka')
                <a href="{{ route('ppdb.create') }}" class="btn btn-light btn-lg btn-hero">
                    Daftar Sekarang
                </a>
            @else
                <button class="btn btn-secondary btn-lg btn-hero" disabled>
                    PPDB Ditutup
                </button>
            @endif

            <a href="{{ route('ppdb.alur') }}" class="btn btn-outline-light btn-lg btn-hero">
                Lihat Alur
            </a>

            <a href="{{ route('gateway') }}" class="btn btn-outline-light btn-lg btn-hero">
                Kembali
            </a>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
        <div class="col">
            <div class="card shadow-soft h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Kuota Siswa</h6>
                    <h2 class="fw-bold text-primary">{{ $setting?->kuota }}</h2>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card shadow-soft h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Tanggal Mulai</h6>
                    <h5 class="fw-bold">{{ $setting?->tanggal_mulai }}</h5>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card shadow-soft h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Tanggal Selesai</h6>
                    <h5 class="fw-bold">{{ $setting?->tanggal_selesai }}</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-soft">
        <div class="card-body p-4 p-md-5">
            <h3 class="fw-bold mb-4 text-center">
                Mengapa Memilih SDN Mekar Jaya?
            </h3>

            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-3 text-center">
                <div class="col">
                    <div class="feature-icon">👨‍🏫</div>
                    <p class="mb-0 fw-semibold">Guru Profesional</p>
                </div>
                <div class="col">
                    <div class="feature-icon">🏫</div>
                    <p class="mb-0 fw-semibold">Fasilitas Lengkap</p>
                </div>
                <div class="col">
                    <div class="feature-icon">🎨</div>
                    <p class="mb-0 fw-semibold">Ekstrakurikuler Aktif</p>
                </div>
                <div class="col">
                    <div class="feature-icon">🌱</div>
                    <p class="mb-0 fw-semibold">Lingkungan Nyaman</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection