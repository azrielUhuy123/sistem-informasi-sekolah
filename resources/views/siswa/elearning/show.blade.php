@extends('layouts.app')

@section('title', $forum->judul)

@section('content')


<div class="forum-wrapper">

    {{-- HEADER FORUM --}}
    <div class="forum-header-sticky">

        <div class="forum-top-header">

            <a
                href="{{ route('siswa.e-learning.index') }}"
                class="btn-back-forum">

                <i class="bi bi-arrow-left"></i>

                BACK

            </a>

            <h2 class="forum-title">
                {{ $forum->judul }}
            </h2>

        </div>

    </div>

            {{-- CARD GURU --}}
            <div class="teacher-card">

                <div class="teacher-card-header">

                    <div class="d-flex align-items-center">

                        <img
                            src="https://ui-avatars.com/api/?name={{ urlencode($forum->guru?->nama ?? 'Guru') }}"
                            class="teacher-avatar">

                        <div class="ms-3">

                            <h5 class="mb-1 fw-bold">

                                {{ $forum->guru?->nama }}

                                @if($forum->mapel)

                                    <span class="text-muted fw-normal">

                                        - {{ $forum->mapel->nama_mapel }}

                                    </span>

                                @endif

                            </h5>

                            <div>

                                <i class="bi bi-mortarboard"></i>

                                {{ $forum->kelas?->nama_kelas_label }}
                                <i></i>  -

                                <i class="bi bi-clock-history"></i>

                                Diposting

                                {{ $forum->created_at->translatedFormat('d M Y H:i') }}

                            </div>

                        </div>

                    </div>

                </div>

                <div class="teacher-card-body">

                    {!! $forum->konten !!}

                </div>

                @if($forum->lampiran->count())

                    <div class="teacher-card-files">

                        <h6 class="fw-bold mb-3">

                            Lampiran Materi

                        </h6>

                        @foreach($forum->lampiran as $file)

                            <a
                                href="{{ asset('storage/'.$file->path) }}"
                                target="_blank"
                                class="teacher-file-item">

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

            {{-- DISKUSI --}}
            <div class="forum-discussion">

                <div class="discussion-header">

                    <h4 class="fw-bold mb-0">

                        Diskusi Forum

                    </h4>

                    <span class="badge bg-secondary">

                        {{ $forum->komentar->count() }} Komentar

                    </span>

                </div>

                <hr>

                @if(session('success'))

                    <div class="alert alert-success">

                        {{ session('success') }}

                    </div>

                @endif

                {{-- FORM KOMENTAR --}}
                <div class="discussion-form-card">

                    <form
                        method="POST"
                        enctype="multipart/form-data"
                        action="{{ route('siswa.e-learning.komentar.store', $forum->id) }}">

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

                @forelse($forum->komentar as $komentar)

                    <div class="discussion-item">

                        <div class="discussion-avatar">

                            {{ strtoupper(substr($komentar->user->name ?? 'U',0,1)) }}

                        </div>

                        <div class="discussion-content">

                            <div class="d-flex justify-content-between">

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

                            <div class="mt-2">

                                {!! nl2br(e($komentar->isi)) !!}

                            </div>

                            @if($komentar->lampiran->count())

                                <div class="mt-2">

                                    @foreach($komentar->lampiran as $lampiran)

                                        <a
                                            href="{{ asset('storage/'.$lampiran->path) }}"
                                            target="_blank"
                                            class="attachment-item">

                                            <i class="bi bi-paperclip"></i>

                                            {{ $lampiran->nama_file }}

                                        </a>

                                    @endforeach

                                </div>

                            @endif

                            <div class="mt-2">

                                    <a
                                        href="javascript:void(0)"
                                        class="small text-primary"
                                        onclick="document.getElementById(
                                            'reply-comment-{{ $komentar->id }}'
                                        ).classList.toggle('d-none')">

                                        Balas

                                    </a>

                                </div>

                                <div
                                    id="reply-comment-{{ $komentar->id }}"
                                    class="d-none mt-3">

                                    <div
                                        class="bg-light border-start border-primary border-3 p-2 rounded mb-2">

                                        <small class="text-muted">

                                            Membalas
                                            <strong>{{ $komentar->user->name }}</strong>

                                        </small>

                                        <div class="small text-muted">

                                            {{ Str::limit($komentar->isi,50) }}

                                        </div>

                                    </div>

                                    <form
                                        method="POST"
                                        enctype="multipart/form-data"
                                        action="{{ route(
                                            'siswa.e-learning.reply.store',
                                            $komentar->id
                                        ) }}">

                                        @csrf

                                        <input
                                            type="hidden"
                                            name="reply_to_name"
                                            value="{{ $komentar->user->name }}">

                                        <input
                                            type="hidden"
                                            name="reply_preview"
                                            value="{{ Str::limit($komentar->isi,50) }}">

                                        <textarea
                                            name="isi"
                                            class="form-control mb-2"
                                            rows="3"
                                            placeholder="Tulis balasan..."></textarea>

                                        <input
                                            type="file"
                                            name="lampiran[]"
                                            class="form-control mb-2">

                                        <div class="d-flex gap-2">

                                            <button
                                                type="submit"
                                                class="btn btn-primary btn-sm">

                                                Kirim Balasan

                                            </button>

                                            <button
                                                type="button"
                                                class="btn btn-light btn-sm"
                                                onclick="document.getElementById(
                                                    'reply-comment-{{ $komentar->id }}'
                                                ).classList.add('d-none')">

                                                Batal

                                            </button>

                                        </div>

                                    </form>

                                </div>

                            {{-- LIST REPLIES --}}
                            @foreach($komentar->replies as $reply)

                                <div class="reply-modern">

                                    <div class="d-flex justify-content-between">

                                        <strong>
                                            {{ $reply->user->name }}
                                        </strong>

                                        <small>
                                            {{ $reply->created_at->diffForHumans() }}
                                        </small>

                                    </div>

                                    @if($reply->reply_to_name)

                                        <div class="reply-target">

                                            <small class="text-muted">

                                                Membalas

                                                <strong>
                                                    {{ $reply->reply_to_name }}
                                                </strong>

                                            </small>

                                            <div class="small text-muted">

                                                {{ $reply->reply_preview }}

                                            </div>

                                        </div>

                                    @endif

                                    <div>

                                        {{ $reply->isi }}

                                    </div>

                                    @if($reply->lampiran->count())

                                        <div class="mt-2">

                                            @foreach($reply->lampiran as $lampiran)

                                                <a
                                                    href="{{ asset('storage/'.$lampiran->path) }}"
                                                    target="_blank"
                                                    class="attachment-item">

                                                    <i class="bi bi-paperclip"></i>

                                                    {{ $lampiran->nama_file }}

                                                </a>

                                            @endforeach

                                        </div>

                                    @endif

                                    <div class="mt-2">

                                        <a
                                            href="javascript:void(0)"
                                            class="small text-primary"
                                            onclick="showReplyForm(
                                                '{{ $reply->id }}',
                                                '{{ $reply->id }}',
                                                '{{ $reply->user->name }}',
                                                '{{ Str::limit($reply->isi,50) }}'
                                            )">

                                            Balas

                                        </a>

                                    </div>

                                    {{-- FORM REPLY TEPAT DI BAWAH REPLY YANG DIKLIK --}}
                                    <div
                                        id="reply-form-{{ $reply->id }}"
                                        class="reply-form-container d-none mt-3">

                                        <div
                                            class="bg-light border-start border-primary border-3 p-2 rounded mb-2">

                                            <div
                                                id="reply-preview-box-{{ $reply->id }}">
                                            </div>

                                        </div>

                                        <form
                                            method="POST"
                                            enctype="multipart/form-data"
                                            action="{{ route(
                                                'siswa.e-learning.reply.store',
                                                $komentar->id
                                            ) }}">

                                            @csrf

                                            <input
                                                type="hidden"
                                                name="reply_to_reply_id"
                                                id="reply_to_reply_id_{{ $reply->id }}">

                                            <input
                                                type="hidden"
                                                name="reply_to_name"
                                                id="reply_to_name_{{ $reply->id }}">

                                            <input
                                                type="hidden"
                                                name="reply_preview"
                                                id="reply_preview_{{ $reply->id }}">

                                            <textarea
                                                name="isi"
                                                class="form-control mb-2"
                                                rows="3"
                                                placeholder="Tulis balasan..."></textarea>

                                            <input
                                                type="file"
                                                name="lampiran[]"
                                                class="form-control mb-2">

                                            <div class="d-flex gap-2">

                                                <button
                                                    type="submit"
                                                    class="btn btn-primary btn-sm">

                                                    Kirim Balasan

                                                </button>

                                                <button
                                                    type="button"
                                                    class="btn btn-light btn-sm"
                                                    onclick="hideReplyForm('{{ $reply->id }}')">

                                                    Batal

                                                </button>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    </div>

                @empty

                    <div class="text-center py-5">

                        <i
                            class="bi bi-chat-square-text text-muted"
                            style="font-size:60px"></i>

                        <h6 class="mt-3">

                            Belum ada diskusi

                        </h6>

                    </div>

                @endforelse

            </div>

        </div>

    </div>

</div>

<style>

/* =========================
   HEADER MATERI
========================= */
/* =========================
   STICKY HEADER MENTARI STYLE
========================= */

.forum-header-sticky{

    position: sticky;
    top: 0;
    z-index: 9999;

    background: rgba(255,255,255,.35);

    backdrop-filter: blur(32px) saturate(180%);
    -webkit-backdrop-filter: blur(32px) saturate(180%);

    border: 1px solid rgba(255,255,255,.3);

    box-shadow:
        0 8px 32px rgba(0,0,0,.08);

}

.forum-header-sticky::after{

    content:'';

    position:absolute;
    inset:0;

    background:
        linear-gradient(
            135deg,
            rgba(255,255,255,.35),
            rgba(255,255,255,.05)
        );

    pointer-events:none;
}
.forum-header-sticky::before{
    content:'';

    position:absolute;
    inset:0;

    background:
        linear-gradient(
            180deg,
            rgba(255,255,255,.65),
            rgba(255,255,255,.25)
        );

    pointer-events:none;
}
.forum-header-sticky{
    position:sticky;
    top:0;
    z-index:9999;

    margin-bottom:20px;
}

.forum-top-header{

    position:relative;

    display:flex;
    align-items:center;
    justify-content:center;

    min-height:72px;

    background:rgba(255,255,255,.75);

    backdrop-filter:blur(25px);
    -webkit-backdrop-filter:blur(25px);

    border-bottom:1px solid #e5e7eb;

    box-shadow:
        0 10px 30px rgba(0,0,0,.08);
}

.btn-back-forum{
    position:absolute;
    left:24px;

    display:flex;
    align-items:center;
    gap:8px;

    padding:10px 18px;

    border:1px solid #ef4444;
    border-radius:12px;

    background:white;

    color:#ef4444;
    text-decoration:none;

    font-size:14px;
    font-weight:600;

    transition:.2s;
}

.btn-back-forum:hover{
    background:#ef4444;
    color:white;
}

.forum-title{
    margin:0;

    font-size:2.2rem;
    font-weight:700;

    color:#1f2937;

    text-align:center;
}

.forum-meta{
    display:flex;
    gap:20px;
    flex-wrap:wrap;

    color:#6b7280;
    font-size:.9rem;
}

.forum-meta i{
    margin-right:6px;
}

/* =========================
   CARD GURU
========================= */

.teacher-card{
    background:#fff;
    border:1px solid #edf0f5;
    border-radius:18px;
    overflow:hidden;
    margin-bottom:30px;
}

.teacher-card-header{
    padding:25px 30px;
    border-bottom:1px solid #edf0f5;
}

.teacher-avatar{
    width:70px;
    height:70px;
    border-radius:50%;
    object-fit:cover;
    border:3px solid #f3f4f6;
}

.teacher-card-body{
    padding:30px;
    line-height:1.9;
    font-size:15px;
}

.teacher-card-body img{
    max-width:100%;
    height:auto;
    border-radius:14px;
    margin:10px 0;
}

.teacher-card-body table{
    width:100%;
}

.teacher-card-files{
    padding:30px;
    border-top:1px solid #edf0f5;
}

.teacher-file-item{
    display:flex;
    justify-content:space-between;
    align-items:center;
    text-decoration:none;
    color:#212529;
    padding:14px 18px;
    border:1px solid #e9ecef;
    border-radius:14px;
    margin-bottom:10px;
    transition:.2s;
}

.teacher-file-item:hover{
    background:#f8fafc;
}

/* =========================
   DISKUSI
========================= */

.forum-discussion{
    margin-top:30px;
}

.discussion-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

.discussion-form-card{
    background:#fff;
    border:1px solid #edf0f5;
    border-radius:18px;
    padding:20px;
    margin-bottom:25px;
}

.discussion-item{
    display:flex;
    gap:15px;
    background:#fff;
    border:1px solid #edf0f5;
    border-radius:18px;
    padding:20px;
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

.discussion-content strong{
    font-size:15px;
}

.discussion-content .badge{
    font-size:11px;
}

.discussion-content .text-muted{
    font-size:13px;
}

/* =========================
   FILE KOMENTAR
========================= */

.attachment-item{
    display:inline-flex;
    align-items:center;
    gap:8px;
    padding:7px 12px;
    border-radius:30px;
    background:#f8f9fa;
    border:1px solid #e9ecef;
    text-decoration:none;
    color:#495057;
    margin-right:6px;
    margin-top:5px;
    font-size:13px;
}

/* =========================
   REPLY
========================= */

.reply-modern{
    margin-top:15px;
    margin-left:40px;
    padding:15px;
    background:#f8fafc;
    border-radius:14px;
    border:1px solid #edf0f5;
}

.reply-target{
    display:inline-block;
    padding:5px 10px;
    background:#eef4ff;
    border-radius:10px;
    color:#0d6efd;
    font-size:13px;
    margin-top:8px;
    margin-bottom:8px;
}

.reply-form-container{
    margin-left:40px;
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

    .forum-meta{
        flex-direction:column;
        gap:8px;
    }

    .teacher-card-header,
    .teacher-card-body,
    .teacher-card-files{
        padding:20px;
    }

    .discussion-item{
        padding:15px;
    }

    .reply-modern{
        margin-left:10px;
    }

    .reply-form-container{
        margin-left:10px;
    }

    .teacher-avatar{
        width:55px;
        height:55px;
    }
}

</style>

<script>

function showReplyForm(
    komentarId,
    replyId,
    replyName,
    preview
){

    document
        .querySelectorAll(
            '.reply-form-container'
        )
        .forEach(el => {
            el.classList.add('d-none');
        });

    const formContainer =
        document.getElementById(
            'reply-form-' + komentarId
        );

    if (!formContainer) return;

    formContainer.classList.remove(
        'd-none'
    );

    document.getElementById(
        'reply_to_reply_id_' + komentarId
    ).value = replyId;

    document.getElementById(
        'reply_to_name_' + komentarId
    ).value = replyName;

    document.getElementById(
        'reply_preview_' + komentarId
    ).value = preview;

    document.getElementById(
        'reply-preview-box-' + komentarId
    ).innerHTML =
        '<small class="text-muted">Membalas <b>' +
        replyName +
        '</b></small><br>' +
        preview;

}

function hideReplyForm(
    komentarId
){

    const container =
        document.getElementById(
            'reply-form-' + komentarId
        );

    if (!container) return;

    container.classList.add(
        'd-none'
    );

    const textarea =
        container.querySelector(
            'textarea'
        );

    if (textarea) {
        textarea.value = '';
    }

    const fileInput =
        container.querySelector(
            'input[type=file]'
        );

    if (fileInput) {
        fileInput.value = '';
    }

}

</script>

@endsection