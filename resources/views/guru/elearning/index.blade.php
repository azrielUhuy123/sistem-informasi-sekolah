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

                        <i class="bi bi-easel2-fill"></i>

                    </div>

                    <div class="ms-4">

                        <div class="hero-label">

                            E-LEARNING GURU

                        </div>

                        <h1 class="hero-title">

                            Forum Pembelajaran

                        </h1>

                        <p class="hero-subtitle">

                            Kelola forum pembelajaran, diskusi, materi, dan tugas untuk siswa.

                        </p>

                        <a
                            href="{{ route('guru.e-learning.create') }}"
                            class="btn btn-light hero-btn mt-3">

                            <i class="bi bi-plus-circle me-2"></i>

                            Buat Forum Baru

                        </a>

                    </div>

                </div>

            </div>

            <div class="col-lg-4">

                <div class="hero-stat">

                    <div class="hero-box">

                        <i class="bi bi-journal-bookmark-fill"></i>

                        <div>

                            <h3>

                                {{ $forums->count() }}

                            </h3>

                            <span>

                                Total Forum

                            </span>

                        </div>

                    </div>

                    <div class="hero-box">

                        <i class="bi bi-paperclip"></i>

                        <div>

                            <h3>

                                {{ $forums->sum(fn($forum) => $forum->lampiran->count()) }}

                            </h3>

                            <span>

                                Total Lampiran

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

        $color = $colors[$loop->index % count($colors)];

        @endphp

           <div class="col-xl-4 col-lg-6 col-md-6 mb-4">

                <a
                    href="{{ route('guru.e-learning.show', $forum->id) }}"
                    class="text-decoration-none">

                    <div class="forum-modern {{ $color }}">

                        {{-- ================= HEADER ================= --}}

                        <div class="forum-header">

                            <span class="forum-badge">

                                @if($forum->pengampu_id)

                                    {{ $forum->mapel?->nama_mapel }}

                                @else

                                    Wali Kelas

                                @endif

                            </span>

                            <div class="forum-kelas-info">

                                <div class="kelas-label">

                                    <i class="bi bi-mortarboard-fill"></i>

                                    <small>Kelas</small>

                                </div>

                                <strong>

                                    {{ $forum->kelas?->nama_kelas_label }}

                                </strong>

                            </div>

                        </div>


                        {{-- ================= TITLE ================= --}}

                        <h3 class="forum-title">

                            {{ $forum->judul }}

                        </h3>


                        {{-- ================= GURU ================= --}}

                        <div class="forum-teacher">

                            <div class="teacher-avatar">

                                <i class="bi bi-person-workspace"></i>

                            </div>

                            <div class="teacher-info">

                                <div class="teacher-name">

                                    {{ $forum->guru?->nama }}

                                </div>

                                <div class="teacher-role">

                                    @if($forum->pengampu_id)

                                        Guru Mata Pelajaran

                                    @else

                                        Wali Kelas

                                    @endif

                                </div>

                            </div>

                        </div>

                        {{-- ================= PREVIEW ================= --}}

                        <div class="forum-preview">

                            {{ Str::limit(strip_tags($forum->konten), 120) }}

                        </div>


                        {{-- ================= FOOTER ================= --}}

                        <div class="forum-footer">

                            <div class="forum-meta">

                                <i class="bi bi-paperclip"></i>

                                <span>{{ $forum->lampiran->count() }} Lampiran</span>

                            </div>

                            <div class="forum-meta">

                                <i class="bi bi-clock-history"></i>

                                <span>{{ $forum->created_at->diffForHumans() }}</span>

                            </div>

                        </div>

                        <div class="btn-forum">

                            <span>Masuk Forum</span>

                            <i class="bi bi-arrow-right"></i>

                        </div>
                    </div>
                </a>
            </div>

        @endforeach

    </div>

    <div class="d-flex justify-content-center mt-5">

        {{ $forums->links() }}

    </div>

@else

<div class="card border-0 shadow-sm rounded-4">

    <div class="card-body text-center py-5">

        <div class="empty-icon mb-4">

            <i class="bi bi-journal-bookmark-fill"></i>

        </div>

        <h3 class="fw-bold mb-3">

            Belum Ada Forum Pembelajaran

        </h3>

        <p class="text-muted mb-4">

            Belum ada forum yang dibuat.
            Silakan mulai membuat forum pembelajaran pertama
            agar siswa dapat mengakses materi dan diskusi.

        </p>

        <a
            href="{{ route('guru.e-learning.create') }}"
            class="btn btn-primary rounded-pill px-4">

            <i class="bi bi-plus-circle me-2"></i>

            Buat Forum Pertama

        </a>

    </div>

</div>

@endif

</div>

<style>
/*==================================================
HERO
==================================================*/

.elearning-hero{

    background:linear-gradient(
        135deg,
        #2563eb,
        #4f46e5,
        #7c3aed
    );

    border-radius:28px;

    padding:45px;

    color:#fff;

    position:relative;

    overflow:hidden;

    box-shadow:
        0 20px 45px rgba(37,99,235,.25);

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

    backdrop-filter:blur(12px);

    display:flex;

    justify-content:center;

    align-items:center;

    font-size:40px;

}

.hero-label{

    text-transform:uppercase;

    letter-spacing:3px;

    font-size:15px;

    font-weight:600;

    opacity:.9;

}

.hero-title{

    font-size:44px;

    font-weight:800;

    margin:8px 0;

    color:#fff;

}

.hero-subtitle{

    font-size:17px;

    opacity:.92;

    max-width:620px;

    margin-bottom:22px;

}

.hero-btn{

    border-radius:14px;

    padding:13px 22px;

    font-weight:700;

    box-shadow:
        0 10px 25px rgba(255,255,255,.15);

}

.hero-stat{

    display:flex;

    flex-direction:column;

    gap:18px;

}

.hero-box{

    background:rgba(255,255,255,.12);

    backdrop-filter:blur(12px);

    border-radius:18px;

    padding:20px;

    display:flex;

    align-items:center;

    gap:18px;

}

.hero-box i{

    font-size:28px;

}

.hero-box h3{

    margin:0;

    font-size:34px;

    font-weight:800;

    color:#fff;

}

.hero-box span{

    opacity:.9;

}

/*==================================================
FORUM CARD
==================================================*/

.forum-modern{

    background:#fff;

    border-radius:26px;

    padding:28px;

    height:100%;

    display:flex;

    flex-direction:column;

    border:1px solid #edf2f7;

    border-top:6px solid #2563eb;

    box-shadow:
        0 12px 30px rgba(15,23,42,.06);

    transition:
        transform .35s ease,
        box-shadow .35s ease;

    overflow:hidden;

}

.forum-modern:hover{

    transform:translateY(-10px);

    box-shadow:
        0 22px 50px rgba(37,99,235,.16);

}


/*==================================================
HEADER
==================================================*/

.forum-header{

    display:flex;

    justify-content:space-between;

    align-items:flex-start;

    gap:20px;

    margin-bottom:24px;

}

.forum-badge{

    display:inline-flex;

    align-items:center;

    padding:8px 16px;

    border-radius:999px;

    font-size:12px;

    font-weight:700;

}

.forum-kelas-info{

    text-align:right;

}

.kelas-label{

    display:flex;

    justify-content:flex-end;

    align-items:center;

    gap:6px;

    font-size:12px;

    color:#94a3b8;

}

.kelas-label i{

    font-size:13px;

}

.forum-kelas-info strong{

    display:block;

    margin-top:5px;

    font-size:15px;

    font-weight:700;

}


/*==================================================
TITLE
==================================================*/

.forum-title{

    font-size:30px;

    font-weight:800;

    color:#0f172a;

    line-height:1.35;

    margin-bottom:28px;

}


/*==================================================
TEACHER
==================================================*/

.forum-teacher{

    display:flex;

    align-items:center;

    gap:15px;

    margin-bottom:22px;

}

.teacher-avatar{

    width:58px;

    height:58px;

    border-radius:50%;

    display:flex;

    justify-content:center;

    align-items:center;

    color:#fff;

    font-size:24px;

    flex-shrink:0;

}

.teacher-info{

    flex:1;

}

.teacher-name{

    font-size:17px;

    font-weight:700;

    color:#0f172a;

}

.teacher-role{

    font-size:13px;

    color:#94a3b8;

    margin-top:2px;

}


/*==================================================
PREVIEW
==================================================*/

.forum-preview{

    color:#64748b;

    line-height:1.8;

    min-height:90px;

    margin-bottom:26px;

}

/*==================================================
FOOTER
==================================================*/

.forum-footer{

    display:flex;

    justify-content:space-between;

    align-items:center;

    margin-top:auto;

    padding-top:18px;

    border-top:1px solid #edf2f7;

    margin-bottom:24px;

}

.forum-meta{

    display:flex;

    align-items:center;

    gap:7px;

    color:#64748b;

    font-size:13px;

}

.forum-meta i{

    font-size:15px;

}


/*==================================================
BUTTON
==================================================*/

.btn-forum{

    display:flex;

    justify-content:center;

    align-items:center;

    gap:10px;

    padding:14px 20px;

    border-radius:14px;

    border:2px solid currentColor;

    font-weight:700;

    transition:.3s;

    background:#fff;

}

.btn-forum i{

    transition:.3s;

}

.forum-modern:hover .btn-forum i{

    transform:translateX(5px);

}

.btn-forum:hover{

    color:#fff;

}


/*==================================================
WARNA
==================================================*/

.forum-modern.blue{

    border-top-color:#2563eb;

}

.forum-modern.green{

    border-top-color:#22c55e;

}

.forum-modern.orange{

    border-top-color:#f97316;

}

.forum-modern.purple{

    border-top-color:#8b5cf6;

}

.forum-modern.cyan{

    border-top-color:#06b6d4;

}

.forum-modern.pink{

    border-top-color:#ec4899;

}

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


/*==================================================
BADGE
==================================================*/

.blue .forum-badge{

    background:#dbeafe;

    color:#2563eb;

}

.green .forum-badge{

    background:#dcfce7;

    color:#22c55e;

}

.orange .forum-badge{

    background:#ffedd5;

    color:#f97316;

}

.purple .forum-badge{

    background:#ede9fe;

    color:#8b5cf6;

}

.cyan .forum-badge{

    background:#cffafe;

    color:#06b6d4;

}

.pink .forum-badge{

    background:#fce7f3;

    color:#ec4899;

}


/*==================================================
AVATAR + BUTTON
==================================================*/

.blue .teacher-avatar,
.blue .btn-forum:hover{

    background:#2563eb;

}

.green .teacher-avatar,
.green .btn-forum:hover{

    background:#22c55e;

}

.orange .teacher-avatar,
.orange .btn-forum:hover{

    background:#f97316;

}

.purple .teacher-avatar,
.purple .btn-forum:hover{

    background:#8b5cf6;

}

.cyan .teacher-avatar,
.cyan .btn-forum:hover{

    background:#06b6d4;

}

.pink .teacher-avatar,
.pink .btn-forum:hover{

    background:#ec4899;

}

/*==================================================
EMPTY STATE
==================================================*/

.empty-state{

    background:#fff;

    border-radius:26px;

    padding:70px 30px;

    text-align:center;

    box-shadow:0 12px 35px rgba(15,23,42,.06);

    border:1px solid #eef2f7;

}

.empty-state i{

    font-size:72px;

    color:#2563eb;

    margin-bottom:20px;

}

.empty-state h4{

    font-weight:800;

    color:#0f172a;

    margin-bottom:10px;

}

.empty-state p{

    color:#64748b;

    max-width:500px;

    margin:auto;

    line-height:1.8;

}


/*==================================================
PAGINATION
==================================================*/

.pagination{

    gap:8px;

}

.page-link{

    border:none;

    border-radius:12px !important;

    min-width:44px;

    height:44px;

    display:flex;

    align-items:center;

    justify-content:center;

    font-weight:700;

    color:#2563eb;

    box-shadow:0 5px 15px rgba(0,0,0,.05);

    transition:.25s;

}

.page-link:hover{

    background:#2563eb;

    color:#fff;

}

.page-item.active .page-link{

    background:#2563eb;

    border-color:#2563eb;

    color:#fff;

}


/*==================================================
HOVER EFFECT
==================================================*/

.forum-modern:hover .forum-title{

    color:#2563eb;

}

.forum-modern:hover .teacher-avatar{

    transform:scale(1.08);

}

.teacher-avatar{

    transition:.3s;

}

.forum-title{

    transition:.3s;

}

.btn-forum{

    transition:all .3s ease;

}

.forum-modern{

    transition:
        transform .35s ease,
        box-shadow .35s ease;

}


/*==================================================
RESPONSIVE
==================================================*/

@media (max-width:991px){

    .elearning-hero{

        padding:35px;

    }

    .hero-title{

        font-size:34px;

    }

    .hero-stat{

        margin-top:30px;

    }

}

@media (max-width:768px){

    .elearning-hero{

        padding:28px;

    }

    .hero-icon{

        width:72px;

        height:72px;

        font-size:30px;

    }

    .hero-title{

        font-size:28px;

    }

    .hero-subtitle{

        font-size:15px;

    }

    .forum-title{

        font-size:24px;

    }

    .forum-modern{

        padding:22px;

    }

    .forum-header{

        flex-direction:column;

        align-items:flex-start;

    }

    .forum-kelas-info{

        text-align:left;

    }

    .kelas-label{

        justify-content:flex-start;

    }

    .forum-footer{

        flex-direction:column;

        align-items:flex-start;

        gap:12px;

    }

}

@media (max-width:576px){

    .hero-title{

        font-size:24px;

    }

    .hero-btn{

        width:100%;

        justify-content:center;

    }

    .btn-forum{

        width:100%;

    }

}
</style>
@endsection