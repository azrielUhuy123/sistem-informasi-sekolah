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

    <div class="card border-0 shadow-sm">

        <div class="card-body p-4">

            {{-- HEADER FORUM --}}
            <div class="d-flex justify-content-between align-items-start flex-wrap">

                <div>

                    <span class="badge bg-primary mb-3">

                        {{ $elearning->mapel?->nama_mapel }}

                    </span>

                    <h2 class="fw-bold mb-2">

                        {{ $elearning->judul }}

                    </h2>

                </div>

            </div>

            <div class="row g-3 mb-4">

                <div class="col-lg-4">

                    <div class="border rounded p-3 h-100">

                        <div class="text-muted small mb-1">

                            Kelas

                        </div>

                        <div class="fw-semibold">

                            {{ $elearning->kelas?->nama_kelas ?? '-' }}

                        </div>

                    </div>

                </div>

                <div class="col-lg-4">

                    <div class="border rounded p-3 h-100">

                        <div class="text-muted small mb-1">

                            Guru

                        </div>

                        <div class="fw-semibold">

                            {{ $elearning->guru?->nama ?? '-' }}

                        </div>

                    </div>

                </div>

                <div class="col-lg-4">

                    <div class="border rounded p-3 h-100">

                        <div class="text-muted small mb-1">

                            Deadline

                        </div>

                        <div class="fw-semibold">

                            @if($elearning->deadline)

                                {{ $elearning->deadline->format('d M Y H:i') }}

                            @else

                                -

                            @endif

                        </div>

                    </div>

                </div>

            </div>

            {{-- MATERI --}}
            <div class="mb-5">

                <h5 class="fw-bold mb-3">

                    <i class="bi bi-journal-text me-2"></i>

                    Materi Pembelajaran

                </h5>

                <hr>

                <div class="materi-content">

                    {!! $elearning->konten !!}

                </div>

            </div>

            {{-- LAMPIRAN --}}
            <div class="mb-5">

                <h5 class="fw-bold mb-3">

                    <i class="bi bi-paperclip me-2"></i>

                    Lampiran Materi

                </h5>

                <hr>

                @forelse($elearning->lampiran as $file)

                    <a
                        href="{{ asset('storage/' . $file->path) }}"
                        target="_blank"
                        class="attachment-item">

                        <i class="bi bi-file-earmark-arrow-down"></i>

                        {{ $file->nama_file }}

                    </a>

                @empty

                    <div class="text-muted">

                        Belum ada lampiran.

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

                        {{ $elearning->komentar->count() }} Komentar

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
                            enctype="multipart/form-data"
                            action="{{ route('guru.e-learning.komentar.store', $elearning->id) }}">

                            @csrf

                            <div class="mb-3">

                                <textarea
                                    name="isi"
                                    rows="3"
                                    class="form-control"
                                    placeholder="Tulis komentar atau tanggapan..."></textarea>

                                <input
                                    type="file"
                                    name="lampiran[]"
                                    multiple
                                    class="form-control mt-2">

                            </div>

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

                </div>

                

                @forelse($elearning->komentar as $komentar)

                    <div class="comment-card">

                        <div class="comment-avatar">

                            {{ strtoupper(substr($komentar->user->name ?? 'U', 0, 1)) }}

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

                                @if($komentar->lampiran->count())

                                    <div class="mt-2">

                                        @foreach($komentar->lampiran as $file)

                                            <a
                                                href="{{ asset('storage/'.$file->path) }}"
                                                target="_blank"
                                                class="attachment-item">

                                                <i class="bi bi-paperclip"></i>

                                                {{ $file->nama_file }}

                                            </a>

                                        @endforeach

                                    </div>

                                @endif

                            </div>

                            <div class="mt-3">

                                <div class="comment-actions">

                                    <button
                                        type="button"
                                        class="action-btn"
                                        onclick="document.getElementById('reply-{{ $komentar->id }}').classList.toggle('d-none')">

                                        <i class="bi bi-reply"></i>

                                        Balas

                                    </button>

                                </div>

                            </div>

                            <div
                                id="reply-{{ $komentar->id }}"
                                class="d-none mt-3">

                                <form
                                    method="POST"
                                    enctype="multipart/form-data"
                                    action="{{ route(
                                        'guru.e-learning.reply.store',
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
                                        value="{{ Str::limit(strip_tags($komentar->isi),100) }}">

                                    <div
                                        class="reply-preview-box">

                                        <small class="text-muted">

                                            Membalas
                                            <strong>

                                                {{ $komentar->user->name }}

                                            </strong>

                                        </small>

                                        <div class="small text-dark">

                                            {{ Str::limit(
                                                strip_tags($komentar->isi),
                                                100
                                            ) }}

                                        </div>

                                    </div>

                                    <textarea
                                        name="isi"
                                        rows="2"
                                        class="form-control mb-2"></textarea>
                                    
                                    <input
                                        type="file"
                                        name="lampiran[]"
                                        multiple
                                        class="form-control mt-2">

                                    <button
                                        class="btn btn-sm btn-primary">

                                        Kirim Balasan

                                    </button>

                                </form>

                            </div>

                            @foreach($komentar->replies as $reply)

                            @if($reply->reply_preview)

                            <div class="reply-context">

                                <small class="text-muted">

                                    Membalas komentar:

                                </small>

                                <div class="small">

                                    {{ $reply->reply_preview }}

                                </div>

                            </div>

                            @endif

                            <div class="reply-card">

                                <div class="reply-avatar">

                                    {{ strtoupper(substr($reply->user->name ?? 'U',0,1)) }}

                                </div>  

                                <div class="flex-grow-1">

                                    <div class="d-flex justify-content-between">

                                        <strong>

                                            {{ $reply->user->name }}

                                        </strong>

                                        <small class="text-muted"> 
                                            {{ $reply->created_at->diffForHumans() }}

                                        </small>

                                    </div>

                                    <div>

                                       @if($reply->reply_to_name)

                                        <div class="reply-context">

                                            <small class="text-muted">

                                                Membalas
                                                {{ $reply->reply_to_name }}

                                            </small>

                                            <div class="small">

                                                {{ $reply->reply_preview }}

                                            </div>

                                        </div>

                                        @endif

                                    </div>

                                    <div class="mt-1">

                                        {!! nl2br(e($reply->isi)) !!}
                                        @if($reply->lampiran->count())

                                            <div class="mt-2">

                                                @foreach($reply->lampiran as $file)

                                                    <a
                                                        href="{{ asset('storage/'.$file->path) }}"
                                                        target="_blank"
                                                        class="attachment-item">

                                                        <i class="bi bi-paperclip"></i>

                                                        {{ $file->nama_file }}

                                                    </a>

                                                @endforeach

                                            </div>

                                        @endif

                                        <div class="mt-2">

                                            <button
                                                type="button"
                                                class="action-btn"
                                                onclick="document.getElementById('reply-reply-{{ $reply->id }}').classList.toggle('d-none')">

                                                <i class="bi bi-reply"></i>

                                                Balas

                                            </button>

                                        </div>

                                        <div
                                            id="reply-reply-{{ $reply->id }}"
                                            class="d-none mt-3">

                                            <form
                                                method="POST"
                                                enctype="multipart/form-data"
                                                action="{{ route(
                                                    'guru.e-learning.reply.store',
                                                    $komentar->id
                                                ) }}">

                                                @csrf

                                                <input
                                                    type="hidden"
                                                    name="reply_to_reply_id"
                                                    value="{{ $reply->id }}">

                                                <input
                                                    type="hidden"
                                                    name="reply_to_name"
                                                    value="{{ $reply->user->name }}">

                                                <input
                                                    type="hidden"
                                                    name="reply_preview"
                                                    value="{{ \Illuminate\Support\Str::limit(strip_tags($reply->isi),100) }}">

                                                <div class="reply-preview-box">

                                                    <small>

                                                        Membalas
                                                        {{ $reply->user->name }}

                                                    </small>

                                                    <div>

                                                        {{ \Illuminate\Support\Str::limit(strip_tags($reply->isi),100) }}

                                                    </div>

                                                </div>

                                                <textarea
                                                    name="isi"
                                                    rows="2"
                                                    class="form-control mb-2"></textarea>

                                                <input
                                                    type="file"
                                                    name="lampiran[]"
                                                    multiple
                                                    class="form-control mb-2">

                                                <button
                                                    class="btn btn-sm btn-primary">

                                                    Kirim Balasan

                                                </button>

                                            </form>

                                        </div>

                                    </div>

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

                        <p class="text-muted mb-0">

                            Komentar akan muncul di sini setelah fitur diskusi diaktifkan.

                        </p>

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
    transition:.2s;
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
.reply-card{
    display:flex;
    gap:12px;
    margin-top:15px;
    margin-left:55px;
    padding:12px;
    border-radius:12px;
    background:#f8f9fa;
}

.reply-avatar{
    width:35px;
    height:35px;
    border-radius:50%;
    background:#6c757d;
    color:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:12px;
    font-weight:600;
    flex-shrink:0;
}

.comment-actions{
    margin-top:10px;
}

.action-btn{
    border:none;
    background:none;
    color:#6c757d;
    font-size:.9rem;
    font-weight:500;
    padding:0;
}

.action-btn:hover{
    color:#0d6efd;
}

.reply-preview-box{
    background:#f8f9fa;
    border-left:4px solid #0d6efd;
    padding:10px 12px;
    border-radius:8px;
    margin-bottom:10px;
}

.reply-context{
    background:#f8f9fa;
    border-left:3px solid #adb5bd;
    padding:8px 10px;
    border-radius:8px;
    margin-bottom:8px;
}

</style>

@endsection