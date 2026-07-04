@extends('layouts.app')

@section('title', 'E-Learning')

@section('content')

<div class="container-fluid py-4">

    {{-- ==========================================================
    HERO
    ========================================================== --}}

    <div class="elearning-hero mb-5">

        <div class="row align-items-center">

            <div class="col-lg-8">

                <div class="d-flex align-items-center">

                    <div class="hero-icon">

                        <i class="bi bi-mortarboard-fill"></i>

                    </div>

                    <div class="ms-4">

                        <div class="hero-label">

                            E-LEARNING

                        </div>

                        <h1 class="hero-title">

                            Selamat Datang 👋

                        </h1>

                        <p class="hero-subtitle">

                            Jelajahi seluruh mata pelajaran, forum diskusi,
                            materi, dan tugas yang diberikan guru.

                        </p>

                    </div>

                </div>

            </div>

            <div class="col-lg-4">

                <div class="hero-stat">

                    <div class="hero-box">

                        <i class="bi bi-book-half"></i>

                        <div>

                            <h3>

                                {{ $mapels->count() }}

                            </h3>

                            <span>

                                Mata Pelajaran

                            </span>

                        </div>

                    </div>

                    <div class="hero-box">

                        <i class="bi bi-chat-dots-fill"></i>

                        <div>

                            <h3>

                                {{ $mapels->flatten()->count() }}

                            </h3>

                            <span>

                                Total Forum

                            </span>

                        </div>

                    </div>

                </div>

            </div>

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

            @php

            $colors = [
                'blue',
                'green',
                'orange',
                'purple',
                'cyan',
                'pink'
            ];

            $icons = [
                'bi-book',
                'bi-calculator',
                'bi-globe2',
                'bi-palette',
                'bi-bank',
                'bi-journal-bookmark',
                'bi-laptop',
                'bi-lightbulb'
            ];

            $color = $colors[$loop->index % count($colors)];
            $icon  = $icons[$loop->index % count($icons)];

            @endphp

            <div class="col-xl-4 col-lg-6 col-md-6 mb-4">

                <a
                    href="{{ route('siswa.e-learning.mapel',$mapelId) }}"
                    class="text-decoration-none">

                    <div class="course-modern {{ $color }}">

                        {{-- HEADER --}}
                        <div class="course-header">

                            <span class="course-badge">

                                {{ $items->count() }} Forum

                            </span>

                            <div class="course-kelas">

                                <div class="kelas-label">

                                    <i class="bi bi-mortarboard-fill"></i>

                                    <small>Kelas</small>

                                </div>

                                <strong>

                                    {{ $items->first()?->kelas?->nama_kelas }}

                                </strong>

                            </div>

                        </div>

                        {{-- TITLE --}}
                        <h4 class="course-title">

                            {{ $mapel?->nama_mapel }}

                        </h4>

                        {{-- GURU --}}
                        <div class="teacher-row">

                            <div class="teacher-avatar">

                                <i class="bi bi-person-fill"></i>

                            </div>

                            <div>

                                <div class="teacher-name">

                                    {{ $guru?->nama }}

                                </div>

                                <small>Guru Mata Pelajaran</small>

                            </div>

                        </div>


                        {{-- BUTTON --}}
                        <button class="btn-course">

                            Buka Mata Pelajaran

                            <i class="bi bi-arrow-right ms-2"></i>

                        </button>

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

/*==================================================
HERO
==================================================*/

.elearning-hero{

    background:
        linear-gradient(
            135deg,
            #2563eb,
            #4f46e5,
            #7c3aed
        );

    border-radius:28px;

    padding:42px;

    color:white;

    position:relative;

    overflow:hidden;

    box-shadow:
        0 18px 40px rgba(37,99,235,.25);

}

.elearning-hero::before{

    content:"";

    position:absolute;

    width:320px;

    height:320px;

    border-radius:50%;

    background:rgba(255,255,255,.08);

    right:-120px;

    top:-120px;

}

.elearning-hero::after{

    content:"";

    position:absolute;

    width:220px;

    height:220px;

    border-radius:50%;

    background:rgba(255,255,255,.05);

    left:-80px;

    bottom:-80px;

}

.hero-icon{

    width:90px;

    height:90px;

    border-radius:22px;

    background:rgba(255,255,255,.15);

    display:flex;

    align-items:center;

    justify-content:center;

    font-size:40px;

    backdrop-filter:blur(10px);

}

.hero-label{

    text-transform:uppercase;

    letter-spacing:3px;

    font-size:18px;

    opacity:.85;

}

.hero-title{

    font-size:42px;

    font-weight:800;

    margin:8px 0;

}

.hero-subtitle{

    font-size:16px;

    opacity:.92;

    max-width:600px;

}

.hero-stat{

    display:flex;

    flex-direction:column;

    gap:18px;

}

.hero-box{

    background:rgba(255,255,255,.14);

    backdrop-filter:blur(12px);

    border-radius:18px;

    padding:18px;

    display:flex;

    align-items:center;

    gap:18px;

}

.hero-box i{

    font-size:26px;

}

.hero-box h3{

    margin:0;

    font-size:30px;

    font-weight:700;

}

.hero-box span{

    opacity:.9;

}

/*==================================================
COURSE CARD MODERN
==================================================*/

.course-modern{

    background:#fff;

    border-radius:24px;

    padding:26px;

    height:100%;

    border-top:5px solid #2563eb;

    box-shadow:0 10px 28px rgba(15,23,42,.06);

    transition:.35s;

    display:flex;

    flex-direction:column;

}

.course-modern:hover{

    transform:translateY(-8px);

    box-shadow:0 18px 42px rgba(37,99,235,.18);

}

/*========== WARNA ==========*/

.course-modern.blue{

    border-color:#2563eb;

}

.course-modern.green{

    border-color:#22c55e;

}

.course-modern.orange{

    border-color:#f97316;

}

.course-modern.purple{

    border-color:#8b5cf6;

}

.course-modern.cyan{

    border-color:#06b6d4;

}

.course-modern.pink{

    border-color:#ec4899;

}

/*========== HEADER ==========*/

.course-header{

    display:flex;

    justify-content:space-between;

    align-items:center;

    margin-bottom:20px;

}

.course-header i{

    font-size:34px;

    opacity:.18;

}

/*========== BADGE ==========*/

.course-badge{

    padding:8px 16px;

    border-radius:999px;

    border:2px solid currentColor;

    font-size:13px;

    font-weight:700;

}

/*========== TITLE ==========*/

.course-title{

    font-size:28px;

    font-weight:700;

    color:#0f172a;

    line-height:1.35;

    margin-bottom:28px;

}

/*========== GURU ==========*/

.teacher-row{

    display:flex;

    align-items:center;

    gap:14px;

    margin-bottom:22px;

}

.teacher-avatar{

    width:54px;

    height:54px;

    border-radius:50%;

    display:flex;

    justify-content:center;

    align-items:center;

    color:white;

    font-size:24px;

}

.teacher-name{

    font-weight:700;

}

.teacher-row small{

    color:#94a3b8;

}

/*========== KELAS ==========*/

.course-kelas{

    display:flex;

    align-items:center;

    gap:8px;

    font-weight:700;

    color:currentColor;

}

.kelas-label{

    display:flex;

    align-items:center;

    gap:6px;

}

.kelas-label i{

    font-size:15px;

    opacity:.9;

}

.kelas-label small{

    font-size:13px;

    font-weight:600;

    text-transform:uppercase;

    letter-spacing:.5px;

    color:currentColor;

    opacity:.75;

}

.course-kelas strong{

    font-size:15px;

    font-weight:800;

    color:currentColor;

    line-height:5;

}

/*========== BUTTON ==========*/

.btn-course{

    margin-top:auto;

    width:100%;

    padding:14px;

    border-radius:14px;

    border:2px solid currentColor;

    background:white;

    font-weight:700;

    transition:.25s;

}

.btn-course:hover{

    color:white;

}

/*========== WARNA ==========*/

.blue{

    color:#2563eb;

}

.green{

    color:#22c55e;

}

.orange{

    color:#f97316;

}

.purple{

    color:#8b5cf6;

}

.cyan{

    color:#06b6d4;

}

.pink{

    color:#ec4899;

}

.blue .teacher-avatar,
.blue .btn-course:hover{

    background:#2563eb;

}

.green .teacher-avatar,
.green .btn-course:hover{

    background:#22c55e;

}

.orange .teacher-avatar,
.orange .btn-course:hover{

    background:#f97316;

}

.purple .teacher-avatar,
.purple .btn-course:hover{

    background:#8b5cf6;

}

.cyan .teacher-avatar,
.cyan .btn-course:hover{

    background:#06b6d4;

}

.pink .teacher-avatar,
.pink .btn-course:hover{

    background:#ec4899;

}

</style>

@endsection