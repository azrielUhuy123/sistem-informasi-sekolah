@extends('layouts.public')

@section('title','PPDB SDN Mekar Jaya')

@section('content')

<div class="hero-gradient text-white py-5">


<div class="container text-center">

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

    @if($setting?->status == 'dibuka')

        <a
            href="{{ route('ppdb.create') }}"
            class="btn btn-light btn-lg me-2"
        >
            Daftar Sekarang
        </a>

    @else

        <button
            class="btn btn-secondary btn-lg"
            disabled
        >
            PPDB Ditutup
        </button>

    @endif

    <a
        href="{{ route('ppdb.alur') }}"
        class="btn btn-outline-light btn-lg"
    >
        Lihat Alur
    </a>

    <a
        href="{{ route('gateway') }}"
        class="btn btn-outline-light btn-lg"
    >
        Kembali
    </a>

</div>
```

</div>

<div class="container py-5">

```
<div class="row g-4 mb-5">

    <div class="col-md-4">

        <div class="card shadow-soft h-100">

            <div class="card-body text-center">

                <h6 class="text-muted">
                    Kuota Siswa
                </h6>

                <h2 class="fw-bold text-primary">
                    {{ $setting?->kuota }}
                </h2>

            </div>

        </div>

    </div>

    <div class="col-md-4">

        <div class="card shadow-soft h-100">

            <div class="card-body text-center">

                <h6 class="text-muted">
                    Tanggal Mulai
                </h6>

                <h5 class="fw-bold">
                    {{ $setting?->tanggal_mulai }}
                </h5>

            </div>

        </div>

    </div>

    <div class="col-md-4">

        <div class="card shadow-soft h-100">

            <div class="card-body text-center">

                <h6 class="text-muted">
                    Tanggal Selesai
                </h6>

                <h5 class="fw-bold">
                    {{ $setting?->tanggal_selesai }}
                </h5>

            </div>

        </div>

    </div>

</div>

<div class="card shadow-soft">

    <div class="card-body p-5">

        <h3 class="fw-bold mb-4 text-center">
            Mengapa Memilih SDN Mekar Jaya?
        </h3>

        <div class="row text-center">

            <div class="col-md-3">
                <h5>👨‍🏫</h5>
                <p>Guru Profesional</p>
            </div>

            <div class="col-md-3">
                <h5>🏫</h5>
                <p>Fasilitas Lengkap</p>
            </div>

            <div class="col-md-3">
                <h5>🎨</h5>
                <p>Ekstrakurikuler Aktif</p>
            </div>

            <div class="col-md-3">
                <h5>🌱</h5>
                <p>Lingkungan Nyaman</p>
            </div>

        </div>

    </div>

</div>

</div>


@endsection