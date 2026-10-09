@extends('layouts.public')

@section('title','PPDB SDN Mekar Jaya')

@section('content')

<style>

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
        <div class="mx-auto mt-5 mb-4" style="max-width: 720px;">
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
                <a href="{{ route('ppdb.alur') }}" class="btn btn-light btn-lg btn-hero">
                    Mulai Mendaftar
                </button>
            @else
                <button class="btn btn-secondary btn-lg btn-hero" disabled>
                    PPDB Ditutup
                </button>
            @endif

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
    <div class="text-center mb-5">

    <h1 class="fw-bold">
        Alur PPDB SDN Mekar Jaya
    </h1>

    <p class="text-muted">
        Ikuti tahapan berikut sampai resmi menjadi siswa baru.
    </p>

</div>

<div class="row justify-content-center">

    <div class="col-lg-8">

        <div class="card shadow-soft">

            <div class="card-body p-4">

                <div class="d-flex mb-4">

                    <div class="me-4">
                        <span class="badge bg-primary fs-6">1</span>
                    </div>

                    <div>
                        <h5>Isi Formulir Pendaftaran</h5>
                            Lengkapi seluruh data calon peserta didik.
                        </p>
                    </div>

                </div>

                <hr>

                <div class="d-flex mb-4">

                    <div class="me-4">
                        <span class="badge bg-primary fs-6">2</span>
                    </div>

                    <div>
                        <h5>Simpan Bukti Pendaftaran</h5>
                            Simpan bukti pendaftaran atau salin nomor pendaftaran anda untuk informasi lebih lanjut.
                        </p>
                    </div>

                </div>

                <hr>

                <div class="d-flex mb-4">

                    <div class="me-4">
                        <span class="badge bg-primary fs-6">3</span>
                    </div>

                    <div>
                        <h5>Periksa Email</h5>
                        <p class="text-muted mb-0">
                            Periksa Email yang anda cantumkan secara berkala untuk mengakses informasi lebih lanjut mengenai pendaftaran anda.
                        </p>
                    </div>

                </div>

                <hr>

                <div class="d-flex mb-4">

                    <div class="me-4">
                        <span class="badge bg-primary fs-6">4</span>
                    </div>

                    <div>
                        <h5>Periksa Status Pendaftaran</h5>
                        <p class="text-muted mb-0">
                            Calon siswa dapat melihat status pendaftaran secara online dengan menggunakan nomor pendaftaran dan tanggal lahir anak.
                        </p>
                    </div>

                </div>

                <hr>

                <div class="d-flex mb-4">

                    <div class="me-4">
                        <span class="badge bg-success fs-6">5</span>
                    </div>
                    <div>
                        <h5>Daftar Ulang</h5>
                        <p class="text-muted mb-0">
                            Peserta yang diterima wajib melakukan daftar ulang.
                        </p>
                    </div>
                </div>
                <hr>
                <div class="d-flex">
                    <div class="me-4">
                        <span class="badge bg-success fs-6">✓</span>
                    </div>
                    <div>
                        <h5>Resmi Menjadi Siswa</h5>
                        <p class="text-muted mb-0">
                            Akun siswa dibuat dan siap mengikuti kegiatan belajar.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>  

    
</div>

@endsection
