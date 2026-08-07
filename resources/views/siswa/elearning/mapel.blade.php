@extends('layouts.app')

@section('title', 'Forum Mata Pelajaran')

@section('content')

<div class="container-fluid py-4">

    {{-- HERO HEADER --}}
    <div class="forum-hero mb-5">

        <div class="row align-items-center">

            <div class="col-lg-8">

                <a href="{{ route('siswa.e-learning.index') }}"
                class="btn btn-back mb-4">

                    <i class="bi bi-arrow-left-circle"></i>

                    Kembali ke Mata Pelajaran

                </a>

                <div class="d-flex align-items-center">

                    <div class="hero-icon">

                        <i class="bi bi-journal-richtext"></i>

                    </div>

                    <div class="ms-4">

                        <div class="hero-label">

                            E-Learning

                        </div>

                        <h1 class="hero-title">

                            {{ $forums->first()?->mapel?->nama_mapel }}

                        </h1>

                        <p class="hero-subtitle">

                            Pelajari materi, ikuti diskusi, kirim tugas,
                            dan berinteraksi bersama guru pada forum
                            pembelajaran.

                        </p>

                    </div>

                </div>

            </div>

            <div class="col-lg-4">

                <div class="hero-stats">

                    <div class="stat-box">

                        <div class="stat-icon bg-primary">

                            <i class="bi bi-journal-text"></i>

                        </div>

                        <div>

                            <h3>

                                {{ $forums->total() }}

                            </h3>

                            <span>

                                Total Forum

                            </span>

                        </div>

                    </div>

                    <div class="stat-box">

                        <div class="stat-icon bg-success">

                            <i class="bi bi-chat-dots"></i>

                        </div>

                        <div>

                            <h3>

                                {{ $forums->sum(fn($f)=>$f->komentar->count()) }}

                            </h3>

                            <span>

                                Diskusi

                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- =========================
    FILTER BAR
    ========================= --}}

    <div class="forum-toolbar mb-4">

        <div class="row">

            <div class="col-15">

                <div class="search-box">

                    <i class="bi bi-search"></i>

                   <input
                    type="text"
                    id="searchForum"
                    class="form-control"
                    placeholder="Cari judul forum...">
                </div>

            </div>

        </div>

    </div>

    @if($forums->count())

        <div class="row">

            @foreach($forums as $forum)

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
                        'bi-dribbble',
                        'bi-stars',
                        'bi-bank',
                        'bi-globe2',
                        'bi-palette',
                        'bi-journal-bookmark'
                    ];

                    $color = $colors[$loop->index % count($colors)];

                    $icon = $icons[$loop->index % count($icons)];

                 @endphp

                <div class="col-xl-4 col-lg-6 col-md-6 mb-4 forum-item">

                    <div class="forum-modern {{ $color }}" data-title="{{ strtolower($forum->judul) }}">

                        {{-- HEADER --}}
                        <div class="forum-top">

                            <span class="kelas-badge">

                                {{ $forum->kelas?->nama_kelas_label }}

                            </span>

                            <div class="top-action">

                                <small>

                                    <i class="bi bi-clock-history"></i>

                                    {{ $forum->created_at->diffForHumans() }}

                                </small>

                                <button
                                    class="btn btn-link p-0">

                                    <i class="bi bi-three-dots-vertical"></i>

                                </button>

                            </div>

                        </div>

                        {{-- JUDUL --}}
                        <h4 class="forum-title">

                            {{ $forum->judul }}

                        </h4>

                        {{-- GURU --}}
                        <div class="teacher-box">

                            <div class="teacher-avatar">

                                @if($forum->guru?->jenis_kelamin == 'L')

                                    <i class="bi bi-person-fill"></i>

                                @else

                                    <i class="bi bi-person-heart"></i>

                                @endif

                            </div>

                            <div>

                                <div class="teacher-name">

                                    {{ $forum->guru?->nama }}

                                </div>

                                <small>

                                    Guru Mata Pelajaran

                                </small>

                            </div>

                        </div>

                        {{-- DESKRIPSI --}}
                        <p class="forum-desc">

                            {!! Str::limit(strip_tags($forum->konten),90) !!}

                        </p>

                        {{-- FOOTER --}}
                        <div class="forum-footer">

                            <span>

                                <i class="bi bi-paperclip"></i>

                                {{ $forum->lampiran->count() }}

                                Lampiran

                            </span>

                            <span>

                                <i class="bi bi-chat-dots"></i>

                                {{ $forum->komentar->count() }}

                                Komentar

                            </span>

                        </div>

                        <a
                            href="{{ route('siswa.e-learning.show',$forum->id) }}"
                            class="btn btn-forum">

                            Masuk Forum

                            <i class="bi bi-arrow-right ms-2"></i>

                        </a>

                    </div>

                </div>

                @endforeach

        </div>

        <div class="mt-4">

            {{ $forums->links() }}

        </div>

    @else

        <div class="card border-0 shadow-sm">

            <div class="card-body text-center py-5">

                <i
                    class="bi bi-journal-bookmark"
                    style="font-size:70px"></i>

                <h5 class="mt-3">

                    Belum ada forum

                </h5>

            </div>

        </div>

    @endif

</div>

<style>

/* =======================================================
   HERO
======================================================= */

.forum-hero{

    position:relative;

    overflow:hidden;

    padding:45px;

    border-radius:28px;

    background:
        linear-gradient(
            135deg,
            #2563eb,
            #4f46e5,
            #7c3aed
        );

    color:white;

    box-shadow:
        0 18px 45px rgba(37,99,235,.25);

}

.forum-hero::before{

    content:"";

    position:absolute;

    width:320px;

    height:320px;

    border-radius:50%;

    background:rgba(255,255,255,.08);

    top:-120px;

    right:-120px;

}

.forum-hero::after{

    content:"";

    position:absolute;

    width:220px;

    height:220px;

    border-radius:50%;

    background:rgba(255,255,255,.06);

    bottom:-80px;

    left:-80px;

}

.btn-back{

    background:rgba(255,255,255,.18);

    color:white;

    border:none;

    backdrop-filter:blur(10px);

    padding:10px 18px;

    border-radius:15px;

    transition:.3s;

}

.btn-back:hover{

    background:white;

    color:#2563eb;

}

.hero-icon{

    width:90px;

    height:90px;

    border-radius:24px;

    display:flex;

    align-items:center;

    justify-content:center;

    background:rgba(255,255,255,.18);

    backdrop-filter:blur(10px);

    font-size:40px;

}

.hero-label{

    text-transform:uppercase;

    letter-spacing:2px;

    font-size:13px;

    opacity:.85;

}

.hero-title{

    font-size:42px;

    font-weight:800;

    margin:5px 0;

}

.hero-subtitle{

    font-size:16px;

    opacity:.9;

    max-width:650px;

    margin-bottom:0;

}

.hero-stats{

    display:flex;

    flex-direction:column;

    gap:18px;

}

.stat-box{

    background:rgba(255,255,255,.15);

    backdrop-filter:blur(12px);

    border-radius:20px;

    padding:18px;

    display:flex;

    align-items:center;

    gap:18px;

}

.stat-icon{

    width:55px;

    height:55px;

    border-radius:16px;

    display:flex;

    align-items:center;

    justify-content:center;

    color:white;

    font-size:24px;

}

.stat-box h3{

    margin:0;

    font-size:28px;

    font-weight:700;

}

.stat-box span{

    opacity:.85;

}
/*==================================================
FORUM CARD MODERN
==================================================*/

.forum-modern{

    position:relative;

    background:#fff;

    border-radius:22px;

    border:1px solid #edf2ff;

    padding:24px;

    overflow:hidden;

    display:flex;

    flex-direction:column;

    height:100%;

    transition:.35s ease;

    box-shadow:
        0 10px 30px rgba(15,23,42,.06);

}

.forum-modern:hover{

    transform:translateY(-8px);

    box-shadow:
        0 18px 45px rgba(37,99,235,.15);

}


/*==================================================
WARNA CARD
==================================================*/

.forum-modern.blue{

    border-top:5px solid #2563eb;

}

.forum-modern.green{

    border-top:5px solid #22c55e;

}

.forum-modern.orange{

    border-top:5px solid #f97316;

}

.forum-modern.purple{

    border-top:5px solid #8b5cf6;

}

.forum-modern.cyan{

    border-top:5px solid #06b6d4;

}

.forum-modern.pink{

    border-top:5px solid #ec4899;

}

.pink .kelas-badge{

    color:#ec4899;

    border-color:#ec4899;

}

.pink .teacher-avatar{

    background:#ec4899;

    box-shadow:0 10px 22px rgba(236,72,153,.25);

}

.pink .forum-bg-icon{

    color:#ec4899;

}

.pink .btn-forum{

    border-color:#ec4899;

    color:#ec4899;

}

.pink .btn-forum:hover{

    background:#ec4899;

}

/*==================================================
HEADER
==================================================*/

.forum-top{

    display:flex;

    justify-content:space-between;

    align-items:center;

    margin-bottom:22px;

}

.kelas-badge{

    display:inline-flex;

    align-items:center;

    justify-content:center;

    min-width:48px;

    height:32px;

    padding:0 16px;

    border-radius:999px;

    background:#fff;

    border:2px solid;

    font-size:13px;

    font-weight:700;

    transition:.3s;

}

.blue .kelas-badge{

    color:#2563eb;

    border-color:#2563eb;

}

.green .kelas-badge{

    color:#22c55e;

    border-color:#22c55e;

}

.orange .kelas-badge{

    color:#f97316;

    border-color:#f97316;

}

.purple .kelas-badge{

    color:#8b5cf6;

    border-color:#8b5cf6;

}

.cyan .kelas-badge{

    color:#06b6d4;

    border-color:#06b6d4;

}

.forum-modern:hover .kelas-badge{

    transform:translateY(-2px);

}

.blue .teacher-avatar{

    background:#2563eb;

    box-shadow:0 10px 22px rgba(37,99,235,.25);

}

.green .teacher-avatar{

    background:#22c55e;

    box-shadow:0 10px 22px rgba(34,197,94,.25);

}

.orange .teacher-avatar{

    background:#f97316;

    box-shadow:0 10px 22px rgba(249,115,22,.25);

}

.purple .teacher-avatar{

    background:#8b5cf6;

    box-shadow:0 10px 22px rgba(139,92,246,.25);

}

.cyan .teacher-avatar{

    background:#06b6d4;

    box-shadow:0 10px 22px rgba(6,182,212,.25);

}

.top-action{

    display:flex;

    align-items:center;

    gap:12px;

}

.top-action small{

    color:#64748b;

    font-size:13px;

}

.top-action button{

    color:#94a3b8;

}

.top-action button:hover{

    color:#2563eb;

}


/*==================================================
JUDUL
==================================================*/

.forum-title{

    font-size:25px;

    font-weight:700;

    line-height:1.35;

    color:#1e293b;

    margin-bottom:18px;

}


/*==================================================
GURU
==================================================*/

.teacher-box{

    display:flex;

    align-items:center;

    gap:14px;

    margin-bottom:22px;

}

.teacher-avatar{

    width:56px;

    height:56px;

    border-radius:50%;

    display:flex;

    justify-content:center;

    align-items:center;

    flex-shrink:0;

    color:#fff;

    transition:.3s;

}

.teacher-avatar i{

    font-size:30px;

    line-height:1;

}

.teacher-name{

    font-size:15px;

    font-weight:700;

    color:#1e293b;

}

.teacher-box small{

    color:#94a3b8;

}

/*==================================================
DESKRIPSI
==================================================*/

.forum-desc{

    color:#64748b;

    line-height:1.7;

    font-size:14px;

    margin-bottom:25px;

    flex:1;

}


/*==================================================
FOOTER
==================================================*/

.forum-footer{

    display:flex;

    justify-content:space-between;

    align-items:center;

    padding-top:16px;

    border-top:1px solid #eef2ff;

    margin-bottom:18px;

    color:#64748b;

    font-size:13px;

}

.forum-footer span{

    display:flex;

    align-items:center;

    gap:6px;

}

.forum-footer i{

    color:#2563eb;

}


/*==================================================
BUTTON
==================================================*/

.btn-forum{

    width:100%;

    border-radius:12px;

    border:2px solid #2563eb;

    background:white;

    color:#2563eb;

    font-weight:700;

    padding:12px;

    transition:.25s;

}

.btn-forum:hover{

    background:#2563eb;

    color:white;

}

.blue .btn-forum{

border-color:#2563eb;
color:#2563eb;

}

.blue .btn-forum:hover{

background:#2563eb;

}

.green .btn-forum{

border-color:#22c55e;
color:#22c55e;

}

.green .btn-forum:hover{

background:#22c55e;

}

.orange .btn-forum{

border-color:#f97316;
color:#f97316;

}

.orange .btn-forum:hover{

background:#f97316;

}

.purple .btn-forum{

border-color:#8b5cf6;
color:#8b5cf6;

}

.purple .btn-forum:hover{

background:#8b5cf6;

}

.cyan .btn-forum{

border-color:#06b6d4;
color:#06b6d4;

}

.cyan .btn-forum:hover{

background:#06b6d4;

}

.btn-forum:hover{

color:white;

}


/*=====================================================
RESPONSIVE
=====================================================*/

@media(max-width:768px){

    .forum-card{

        border-radius:18px;

    }

    .forum-header{

        padding:16px;

    }

    .forum-body{

        padding:16px;

    }

    .forum-footer{

        padding:16px;

    }

    .forum-title{

        font-size:18px;

    }

}


/*=====================================================
RESPONSIVE
=====================================================*/

@media (max-width:768px){

    .forum-banner{

        height:85px;

    }

    .guru-section{

        margin-top:-38px;

    }

    .forum-title{

        font-size:18px;

    }

}

/*==================================================
SEARCH BAR
==================================================*/

.forum-toolbar{

    margin-bottom:32px;

}

.search-box{

    position:relative;

}

.search-box i{

    position:absolute;

    left:22px;

    top:50%;

    transform:translateY(-50%);

    color:#94a3b8;

    font-size:18px;

}

.search-box input{

    height:60px;

    border:none;

    border-radius:18px;

    padding-left:58px;

    font-size:15px;

    background:#fff;

    box-shadow:
        0 8px 20px rgba(15,23,42,.05);

    transition:.3s;

}

.search-box input::placeholder{

    color:#94a3b8;

}

.search-box input:focus{

    border:none;

    outline:none;

    box-shadow:
        0 10px 28px rgba(37,99,235,.15);

}
</style>

<script>

document.addEventListener("DOMContentLoaded", function () {

    const search = document.getElementById("searchForum");

    const forums = document.querySelectorAll(".forum-item");

    search.addEventListener("keyup", function () {

        let keyword = this.value.toLowerCase();

        forums.forEach(function(item){

            let title = item
                .querySelector(".forum-modern")
                .dataset.title;

            if(title.includes(keyword))
            {

                item.style.display="block";

            }
            else
            {

                item.style.display="none";

            }

        });

    });

});

</script>

@endsection