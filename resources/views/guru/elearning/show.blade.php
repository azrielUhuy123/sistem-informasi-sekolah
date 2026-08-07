@extends('layouts.app')

@section('title', $elearning->judul)

@section('content')

<div class="container-fluid py-4">

    <a
        href="{{ route('guru.e-learning.index') }}"
        class="btn btn-light mb-4">

        <i class="bi bi-arrow-left me-1"></i>

        Kembali

    </a>

        <div class="card border-0 shadow-sm forum-card">

            <div class="card-body p-0">

                {{-- HEADER GURU --}}
                <div class="forum-header">

                    <div class="d-flex align-items-start">

                        <img
                            src="https://ui-avatars.com/api/?name={{ urlencode($elearning->guru?->nama ?? 'Guru') }}"
                            class="guru-avatar">

                        <div class="ms-3">

                            <h5 class="mb-1 fw-bold">

                                {{ $elearning->guru?->nama }}

                            </h5>

                            <div class="text-muted small">

                                Diposting
                                {{ $elearning->created_at->translatedFormat('d F Y H:i') }}

                            </div>

                        </div>

                    </div>

                </div>

                {{-- JUDUL FORUM --}}
                <div class="forum-title-area">

                    <span class="badge bg-primary mb-3">

                        {{ $elearning->mapel?->nama_mapel }}

                    </span>

                    <h2 class="forum-title">

                        {{ $elearning->judul }}

                    </h2>

                    <div class="forum-meta">

                        <span>

                            <i class="bi bi-mortarboard"></i>

                            {{ $elearning->kelas?->nama_kelas_label }}

                        </span>

                        @if($elearning->deadline)

                            <span>

                                <i class="bi bi-clock"></i>

                                Deadline:
                                {{ $elearning->deadline->format('d M Y H:i') }}

                            </span>

                        @endif

                    </div>

                </div>

                {{-- ISI MATERI --}}
                <div class="forum-content">

                    {!! $elearning->konten !!}

                </div>

                {{-- LAMPIRAN --}}
                @if($elearning->lampiran->count())

                    <div class="forum-files">

                        <h5 class="fw-bold mb-3">

                            Lampiran Materi

                        </h5>

                        @foreach($elearning->lampiran as $file)

                            <a
                                href="{{ asset('storage/'.$file->path) }}"
                                target="_blank"
                                class="forum-file-item">

                                <div>

                                    <i class="bi bi-file-earmark-arrow-down me-2"></i>

                                    {{ $file->nama_file }}

                                </div>

                                <i class="bi bi-download"></i>

                            </a>

                        @endforeach

                    </div>

                @endif

            </div>

        </div>

            {{-- DISKUSI --}}
            <div class="forum-discussion mt-4">

                <div class="forum-section-header">

                    <h4 class="mb-0 fw-bold">

                        Diskusi Forum

                    </h4>

                    <span class="badge bg-secondary">

                        {{ $elearning->komentar->count() }} Komentar

                    </span>

                </div>

                {{-- FORM KOMENTAR --}}
                <div class="discussion-form-card">

                    <form
                        method="POST"
                        enctype="multipart/form-data"
                        action="{{ route('guru.e-learning.komentar.store', $elearning->id) }}">

                        @csrf

                        <textarea
                            name="isi"
                            rows="4"
                            class="form-control mb-3"
                            placeholder="Tulis tanggapan atau diskusi..."></textarea>

                        <input
                            type="file"
                            name="lampiran[]"
                            multiple
                            class="form-control mb-3">

                        <div class="text-end">

                            <button
                                type="submit"
                                class="btn btn-primary">

                                <i class="bi bi-send me-1"></i>

                                Kirim Komentar

                            </button>

                        </div>

                    </form>

                </div>

                {{-- LIST KOMENTAR --}}
                @forelse($elearning->komentar as $komentar)

                    <div class="discussion-item">

                        <div class="discussion-avatar">

                            {{ strtoupper(substr($komentar->user->name ?? 'U',0,1)) }}

                        </div>

                        <div class="discussion-content">

                            <div class="discussion-header">

                                <div>

                                    <strong>

                                        {{ $komentar->user->name }}

                                    </strong>

                                    <span class="badge bg-light text-dark border ms-1">

                                        {{ strtoupper($komentar->user->role) }}

                                    </span>

                                </div>

                                <small class="text-muted">

                                    {{ $komentar->created_at->diffForHumans() }}

                                </small>

                            </div>

                            <div class="discussion-body">

                                {!! nl2br(e($komentar->isi)) !!}

                            </div>

                            @if($komentar->lampiran->count())

                                <div class="mt-3">

                                    @foreach($komentar->lampiran as $file)

                                        <a
                                            href="{{ asset('storage/'.$file->path) }}"
                                            target="_blank"
                                            class="forum-file-mini">

                                            <i class="bi bi-paperclip"></i>

                                            {{ $file->nama_file }}

                                        </a>

                                    @endforeach

                                </div>

                            @endif

                            <div class="mt-3">

                                <button
                                    type="button"
                                    class="btn btn-sm btn-light"
                                    onclick="document.getElementById('reply-{{ $komentar->id }}').classList.toggle('d-none')">

                                    <i class="bi bi-reply"></i>

                                    Balas

                                </button>

                            </div>

                            {{-- FORM REPLY --}}
                            <div
                                id="reply-{{ $komentar->id }}"
                                class="d-none mt-3">

                                <form
                                    method="POST"
                                    enctype="multipart/form-data"
                                    action="{{ route('guru.e-learning.reply.store',$komentar->id) }}">

                                    @csrf

                                    <input
                                        type="hidden"
                                        name="reply_to_name"
                                        value="{{ $komentar->user->name }}">

                                    <input
                                        type="hidden"
                                        name="reply_preview"
                                        value="{{ Str::limit(strip_tags($komentar->isi),100) }}">

                                    <textarea
                                        name="isi"
                                        rows="2"
                                        class="form-control mb-2"
                                        placeholder="Tulis balasan..."></textarea>

                                    <input
                                        type="file"
                                        name="lampiran[]"
                                        multiple
                                        class="form-control mb-2">

                                    <button
                                        class="btn btn-primary btn-sm">

                                        Kirim Balasan

                                    </button>

                                </form>

                            </div>

                            {{-- LIST REPLY --}}
                            @foreach($komentar->replies as $reply)

                                <div class="reply-modern">

                                    <div class="reply-modern-avatar">

                                        {{ strtoupper(substr($reply->user->name ?? 'U',0,1)) }}

                                    </div>

                                    <div class="reply-modern-content">

                                        <div class="d-flex justify-content-between">

                                            <strong>

                                                {{ $reply->user->name }}

                                            </strong>

                                            <small class="text-muted">

                                                {{ $reply->created_at->diffForHumans() }}

                                            </small>

                                        </div>

                                        @if($reply->reply_to_name)

                                            <div class="reply-target">

                                                Membalas

                                                <strong>

                                                    {{ $reply->reply_to_name }}

                                                </strong>

                                            </div>

                                        @endif

                                        <div class="mt-1">

                                            {!! nl2br(e($reply->isi)) !!}

                                        </div>

                                        @if($reply->lampiran->count())

                                            <div class="mt-2">

                                                @foreach($reply->lampiran as $file)

                                                    <a
                                                        href="{{ asset('storage/'.$file->path) }}"
                                                        target="_blank"
                                                        class="forum-file-mini">

                                                        <i class="bi bi-paperclip"></i>

                                                        {{ $file->nama_file }}

                                                    </a>

                                                @endforeach

                                            </div>

                                        @endif

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    </div>

                @empty

                    <div class="text-center py-5">

                        <i
                            class="bi bi-chat-square-text text-muted"
                            style="font-size:70px"></i>

                        <h5 class="mt-3">

                            Belum Ada Diskusi

                        </h5>

                    </div>

                @endforelse

            </div>

<style>

.forum-card{
    overflow:hidden;
    border-radius:18px;
}

/* =========================
   HEADER GURU
========================= */

.forum-header{
    padding:25px 30px;
    background:#fff;
    border-bottom:1px solid #edf0f5;
}

.guru-avatar{
    width:65px;
    height:65px;
    border-radius:50%;
    object-fit:cover;
    border:3px solid #f1f3f5;
}

/* =========================
   JUDUL FORUM
========================= */

.forum-title-area{
    padding:30px;
    border-bottom:1px solid #edf0f5;
}

.forum-title{
    font-size:2rem;
    font-weight:700;
    margin-bottom:15px;
    line-height:1.3;
}

.forum-meta{
    display:flex;
    gap:20px;
    flex-wrap:wrap;
    color:#6c757d;
    font-size:.95rem;
}

/* =========================
   KONTEN
========================= */

.forum-content{
    padding:30px;
    line-height:1.9;
    font-size:15px;
}

.forum-content img{
    max-width:100%;
    border-radius:14px;
    margin-top:10px;
    margin-bottom:10px;
}

.forum-content table{
    width:100%;
}

/* =========================
   FILE MATERI
========================= */

.forum-files{
    padding:30px;
    border-top:1px solid #edf0f5;
}

.forum-file-item{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:15px 18px;
    margin-bottom:10px;
    border-radius:14px;
    border:1px solid #e9ecef;
    text-decoration:none;
    color:#212529;
    transition:.2s;
}

.forum-file-item:hover{
    background:#f8fafc;
}

.forum-file-mini{
    display:inline-flex;
    align-items:center;
    gap:6px;
    padding:6px 12px;
    margin-right:8px;
    margin-top:5px;
    border-radius:20px;
    background:#f8f9fa;
    text-decoration:none;
    color:#495057;
    border:1px solid #e9ecef;
    font-size:.85rem;
}

/* =========================
   DISKUSI
========================= */

.forum-discussion{
    margin-top:25px;
}

.forum-section-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

.discussion-form-card{
    background:#fff;
    border-radius:18px;
    padding:20px;
    border:1px solid #edf0f5;
    margin-bottom:25px;
}

.discussion-item{
    display:flex;
    gap:15px;
    background:#fff;
    border-radius:18px;
    padding:20px;
    border:1px solid #edf0f5;
    margin-bottom:18px;
}

.discussion-avatar{
    width:48px;
    height:48px;
    border-radius:50%;
    background:#0d6efd;
    color:#fff;
    font-weight:700;
    display:flex;
    align-items:center;
    justify-content:center;
    flex-shrink:0;
}

.discussion-content{
    flex:1;
}

.discussion-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:10px;
}

.discussion-body{
    line-height:1.8;
    color:#212529;
}

/* =========================
   REPLY
========================= */

.reply-modern{
    display:flex;
    gap:12px;
    margin-top:15px;
    margin-left:35px;
    padding:14px;
    background:#f8fafc;
    border-radius:14px;
    border:1px solid #edf0f5;
}

.reply-modern-avatar{
    width:38px;
    height:38px;
    border-radius:50%;
    background:#6c757d;
    color:#fff;
    font-size:13px;
    font-weight:700;
    display:flex;
    align-items:center;
    justify-content:center;
    flex-shrink:0;
}

.reply-modern-content{
    flex:1;
}

.reply-target{
    display:inline-block;
    margin-top:6px;
    margin-bottom:8px;
    padding:4px 10px;
    background:#eef4ff;
    border-radius:10px;
    font-size:.85rem;
    color:#0d6efd;
}

/* =========================
   FORM
========================= */

.form-control{
    border-radius:12px;
}

.btn{
    border-radius:10px;
}

/* =========================
   MOBILE
========================= */

@media(max-width:768px){

    .forum-title{
        font-size:1.5rem;
    }

    .forum-title-area,
    .forum-content,
    .forum-files{
        padding:20px;
    }

    .discussion-item{
        padding:15px;
    }

    .reply-modern{
        margin-left:10px;
    }

    .forum-meta{
        flex-direction:column;
        gap:8px;
    }
}

</style>

@endsection