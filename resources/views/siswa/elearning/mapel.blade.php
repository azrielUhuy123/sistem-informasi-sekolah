@extends('layouts.app')

@section('title', 'Forum Mata Pelajaran')

@section('content')

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <a
                href="{{ route('siswa.e-learning.index') }}"
                class="btn btn-light btn-sm mb-3">

                <i class="bi bi-arrow-left"></i>

                Kembali

            </a>

            <h3 class="fw-bold mb-1">

                {{ $forums->first()?->mapel?->nama_mapel }}

            </h3>

            <p class="text-muted mb-0">

                Daftar forum pembelajaran

            </p>

        </div>

    </div>

    @if($forums->count())

        <div class="row">

            @foreach($forums as $forum)

                <div class="col-xl-4 col-lg-6 col-md-6 mb-4">

                    <div class="card forum-card h-100 border-0">

                        <div class="card-body d-flex flex-column">

                            <div
                                class="d-flex justify-content-between align-items-center mb-3">

                                <span class="badge bg-primary">

                                    {{ $forum->kelas?->nama_kelas }}

                                </span>

                                <small class="text-muted">

                                    {{ $forum->created_at->diffForHumans() }}

                                </small>

                            </div>

                            <h5 class="fw-bold mb-3">

                                {{ $forum->judul }}

                            </h5>

                            <div
                                class="text-muted small mb-3">

                                <i class="bi bi-person"></i>

                                {{ $forum->guru?->nama }}

                            </div>

                            <div
                                class="forum-preview text-muted mb-4">

                                {!! \Illuminate\Support\Str::limit(
                                    strip_tags($forum->konten),
                                    120
                                ) !!}

                            </div>

                            <div
                                class="d-flex justify-content-between small text-muted mb-4">

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

                            <div class="mt-auto">

                                <a
                                    href="{{ route('siswa.e-learning.show', $forum->id) }}"
                                    class="btn btn-primary w-100">

                                    <i class="bi bi-box-arrow-in-right"></i>

                                    Masuk Forum

                                </a>

                            </div>

                        </div>

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

.forum-card{

    border-radius:24px;

    transition:.25s;

    overflow:hidden;

    box-shadow:
        0 4px 18px rgba(0,0,0,.08);
}

.forum-card:hover{

    transform:translateY(-5px);

    box-shadow:
        0 15px 35px rgba(0,0,0,.12);
}

.forum-preview{

    min-height:60px;
}

</style>

@endsection