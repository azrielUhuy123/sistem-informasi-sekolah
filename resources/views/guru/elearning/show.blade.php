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
                            action="{{ route('guru.e-learning.komentar.store', $elearning->id) }}">

                            @csrf

                            <div class="mb-3">

                                <textarea
                                    name="isi"
                                    rows="3"
                                    class="form-control"
                                    placeholder="Tulis komentar atau tanggapan..."></textarea>

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

                            </div>

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

</style>

@endsection