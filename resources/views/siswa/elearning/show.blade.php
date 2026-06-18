@extends('layouts.app')

@section('title', $forum->judul)

@section('content')

<div class="container-fluid py-4">

    <a
        href="{{ route('siswa.e-learning.index') }}"
        class="btn btn-light mb-4">

        <i class="bi bi-arrow-left me-1"></i>

        Kembali

    </a>

    <div class="card border-0 shadow-sm">

        <div class="card-body p-4">

            {{-- HEADER --}}
            <div class="mb-4">

                <span class="badge bg-primary mb-2">

                    {{ $forum->mapel?->nama_mapel }}

                </span>

                <h2 class="fw-bold mb-2">

                    {{ $forum->judul }}

                </h2>

                <div class="text-muted">

                    <i class="bi bi-person me-1"></i>

                    {{ $forum->guru?->nama }}

                    &nbsp; • &nbsp;

                    <i class="bi bi-people me-1"></i>

                    {{ $forum->kelas?->nama_kelas }}

                    &nbsp; • &nbsp;

                    <i class="bi bi-clock me-1"></i>

                    {{ $forum->created_at->diffForHumans() }}

                </div>

            </div>

            {{-- DEADLINE --}}
            @if($forum->deadline)

                <div class="alert alert-warning">

                    <strong>Deadline:</strong>

                    {{ $forum->deadline->format('d M Y H:i') }}

                </div>

            @endif

            {{-- MATERI --}}
            <div class="mb-5">

                <h5 class="fw-bold mb-3">

                    <i class="bi bi-journal-text me-2"></i>

                    Materi Pembelajaran

                </h5>

                <hr>

                <div class="materi-content">

                    {!! $forum->konten !!}

                </div>

            </div>

            {{-- LAMPIRAN --}}
            <div class="mb-5">

                <h5 class="fw-bold mb-3">

                    <i class="bi bi-paperclip me-2"></i>

                    Lampiran

                </h5>

                <hr>

                @forelse($forum->lampiran as $file)

                    <a
                        href="{{ asset('storage/'.$file->path) }}"
                        target="_blank"
                        class="attachment-item">

                        <i class="bi bi-file-earmark-arrow-down"></i>

                        {{ $file->nama_file }}

                    </a>

                @empty

                    <div class="text-muted">

                        Tidak ada lampiran.

                    </div>

                @endforelse

            </div>

            {{-- DISKUSI --}}
            <div>

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <h5 class="fw-bold mb-0">

                        <i class="bi bi-chat-dots me-2"></i>

                        Diskusi Forum

                    </h5>

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

                <div class="card border-0 bg-light mb-4">

                    <div class="card-body">

                        <form
                            method="POST"
                            action="{{ route(
                                'siswa.e-learning.komentar.store',
                                $forum->id
                            ) }}">

                            @csrf

                            <div class="mb-3">

                                <textarea
                                    name="isi"
                                    rows="3"
                                    class="form-control"
                                    placeholder="Tulis komentar..."></textarea>

                            </div>

                            <div class="d-flex gap-2">
                                <button
                                    type="submit"
                                    class="btn btn-primary btn-sm">

                                    Kirim Balasan

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

                @forelse($forum->komentar as $komentar)

                    <div class="comment-card">

                        <div class="comment-avatar">

                            {{ strtoupper(substr($komentar->user->name ?? 'U',0,1)) }}

                        </div>

                        <div class="flex-grow-1">

                            <div class="d-flex justify-content-between">

                                <strong>

                                    {{ $komentar->user->name ?? 'User' }}

                                </strong>

                                <small class="text-muted">

                                    {{ $komentar->created_at->diffForHumans() }}

                                </small>

                            </div>

                            <div class="mt-2">

                                {!! nl2br(e($komentar->isi)) !!}

                            </div>

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

                                <div class="reply-box">

                                    <div class="d-flex justify-content-between">

                                        <strong>
                                            {{ $reply->user->name }}
                                        </strong>

                                        <small>
                                            {{ $reply->created_at->diffForHumans() }}
                                        </small>

                                    </div>

                                    @if($reply->reply_to_name)

                                        <div class="reply-preview">

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

.materi-content img{
    max-width:100%;
    height:auto;
    border-radius:12px;
}

.attachment-item{
    display:flex;
    align-items:center;
    gap:10px;
    padding:12px 15px;
    border:1px solid #e9ecef;
    border-radius:12px;
    text-decoration:none;
    color:#212529;
    margin-bottom:10px;
}

.attachment-item:hover{
    background:#f8f9fa;
}

.comment-card{
    display:flex;
    gap:15px;
    padding:18px;
    border:1px solid #ececec;
    border-radius:16px;
    margin-bottom:15px;
}

.comment-avatar{
    width:45px;
    height:45px;
    border-radius:50%;
    background:#0d6efd;
    color:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:700;
    flex-shrink:0;
}

.reply-box{
    margin-top:12px;
    margin-left:25px;
    padding-left:15px;
    border-left:3px solid #dee2e6;
}

.reply-preview{
    background:#f8f9fa;
    border-left:4px solid #0d6efd;
    padding:10px;
    border-radius:8px;
    margin-bottom:8px;
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