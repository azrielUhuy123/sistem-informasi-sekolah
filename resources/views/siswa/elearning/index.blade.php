@extends('layouts.app')

@section('title', 'E-Learning')

@section('content')

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                E-Learning
            </h3>

            <p class="text-muted mb-0">
                Forum pembelajaran dan tugas siswa
            </p>
        </div>

    </div>

    @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif

    @if($mapels->count())

    <div class="row">

        @foreach($mapels as $mapelId => $items)

            @php
                $mapel = $items->first()->mapel;
                $guru  = $items->first()->guru;
            @endphp

            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">

                <a
                    href="{{ route('siswa.e-learning.mapel', $mapelId) }}"
                    class="text-decoration-none">

                    <div class="course-card">

                        <div class="course-badge">

                            {{ $items->count() }}
                            Forum

                        </div>

                        <h5 class="course-title">

                            {{ $mapel?->nama_mapel ?? 'Tanpa Mapel' }}

                        </h5>

                        <div class="course-info">

                            <div>

                                <span class="text-muted">
                                    Guru
                                </span>

                                <br>

                                {{ $guru?->nama ?? '-' }}

                            </div>

                            <div class="mt-3">

                                <span class="text-muted">
                                    Kelas
                                </span>

                                <br>

                                {{ $items->first()?->kelas?->nama_kelas }}

                            </div>

                        </div>

                        <div class="course-arrow">

                            <i class="bi bi-arrow-right"></i>

                        </div>

                    </div>

                </a>

            </div>

        @endforeach

    </div>

@else

    <div class="card border-0 shadow-sm">

        <div class="card-body text-center py-5">

            <i
                class="bi bi-journal-bookmark"
                style="font-size:70px"></i>

            <h5 class="mt-3">

                Belum ada mata pelajaran

            </h5>

        </div>

    </div>

@endif

</div>

<style>

.course-card{

    position:relative;

    height:320px;

    background:#fff;

    border-radius:24px;

    padding:28px;

    border:1px solid #e5e7eb;

    overflow:hidden;

    transition:.25s;

    box-shadow:
        0 4px 20px rgba(0,0,0,.06);
}

.course-card:hover{

    transform:translateY(-5px);

    box-shadow:
        0 15px 40px rgba(0,0,0,.12);
}

.course-title{

    font-size:1.5rem;

    font-weight:700;

    color:#0d47a1;

    margin-top:15px;

    margin-bottom:20px;
}

.course-info{

    color:#374151;

    font-size:.95rem;
}

.course-badge{

    display:inline-block;

    padding:6px 12px;

    border-radius:999px;

    background:#eff6ff;

    color:#2563eb;

    font-size:.8rem;

    font-weight:600;
}

.course-arrow{

    position:absolute;

    right:24px;

    bottom:24px;

    width:52px;

    height:52px;

    border-radius:50%;

    border:3px solid #374151;

    display:flex;

    align-items:center;

    justify-content:center;

    font-size:24px;

    color:#374151;

    transition:.25s;
}

.course-card:hover .course-arrow{

    background:#2563eb;

    color:white;

    border-color:#2563eb;
}

</style>

@endsection