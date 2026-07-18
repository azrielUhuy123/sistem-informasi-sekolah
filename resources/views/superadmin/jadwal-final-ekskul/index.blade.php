@extends('layouts.app')

@section('title', 'Jadwal Final Ekskul')

@section('content')

<div class="container-fluid py-3">

    {{-- ================= HERO ================= --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

        <div
            class="card-body p-4 p-lg-5 text-white"
            style="background:linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);"
        >

            <div class="d-flex justify-content-between align-items-center flex-wrap">

                <div>

                    <h2 class="fw-bold mb-2">

                        Jadwal Final Ekskul

                    </h2>

                    <p class="mb-0 opacity-75">

                        Jadwal ekstrakurikuler resmi yang telah dipublikasikan dan siap digunakan sebagai acuan kegiatan sekolah.

                    </p>

                </div>

                <div class="mt-4 mt-lg-0">

                    <div class="bg-white rounded-4 px-4 py-3 text-center">

                        <h2 class="fw-bold text-primary mb-0">

                            {{ $hari->count() }}

                        </h2>

                        <small class="text-muted">

                            Hari Aktif

                        </small>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- ================= CONTENT ================= --}}
    <div class="row">

        @foreach($hari as $h)

            <div class="col-lg-4 col-md-6 mb-4">

                <div class="card border-0 shadow-sm rounded-4 h-100 hari-card">

                    <div
                        class="card-header border-0 text-white fw-bold py-3"
                        style="background:linear-gradient(135deg,#2563eb,#0f4c81);"
                    >

                        <i class="bi bi-calendar-week-fill me-2"></i>

                        {{ $h->nama }}

                    </div>

                    <div class="card-body">

                        @forelse($grid[$h->id] ?? [] as $j)

                            <div class="schedule-card mb-3">

                                <div class="d-flex justify-content-between align-items-start mb-3">

                                    <div>

                                        <h6 class="fw-bold text-primary mb-1">

                                            {{ $j->ekskul->nama }}

                                        </h6>

                                        <small class="text-muted">

                                            Ekstrakurikuler

                                        </small>

                                    </div>

                                    <span class="badge bg-success rounded-pill">

                                        Published

                                    </span>

                                </div>

                                <div class="info-item">

                                    <i class="bi bi-person-fill"></i>

                                    <span>

                                        {{ $j->guru->pegawai->nama }}

                                    </span>

                                </div>

                                <div class="info-item">

                                    <i class="bi bi-clock-fill"></i>

                                    <span>

                                        {{ substr($j->jam_mulai,0,5) }}
                                        -
                                        {{ substr($j->jam_selesai,0,5) }}

                                    </span>

                                </div>

                            </div>

                        @empty

                            <div class="empty-state">

                                <i class="bi bi-calendar-x display-6 text-secondary"></i>

                                <h6 class="mt-3 mb-1">

                                    Tidak Ada Jadwal

                                </h6>

                                <small class="text-muted">

                                    Belum terdapat jadwal ekstrakurikuler.

                                </small>

                            </div>

                        @endforelse

                    </div>

                </div>

            </div>

        @endforeach

    </div>

</div>

<style>

.card{

    transition:.25s;

}

.card:hover{

    box-shadow:0 18px 45px rgba(37,99,235,.08)!important;

}

.hari-card{

    overflow:hidden;

}

.schedule-card{

    background:linear-gradient(135deg,#f8fbff,#ffffff);

    border:1px solid #dbeafe;

    border-radius:18px;

    padding:18px;

    transition:.25s;

}

.schedule-card:hover{

    transform:translateY(-3px);

    box-shadow:0 14px 35px rgba(37,99,235,.12);

}

.info-item{

    display:flex;

    align-items:center;

    gap:10px;

    margin-top:10px;

    color:#475569;

    font-size:.92rem;

}

.info-item i{

    color:#2563eb;

    width:20px;

}

.badge{

    font-weight:600;

    letter-spacing:.3px;

}

.empty-state{

    text-align:center;

    padding:40px 20px;

    border:2px dashed #d1d5db;

    border-radius:18px;

    color:#94a3b8;

    transition:.25s;

}

.empty-state:hover{

    background:#f8fafc;

}

@media(max-width:768px){

    .schedule-card{

        padding:15px;

    }

}

</style>

@endsection