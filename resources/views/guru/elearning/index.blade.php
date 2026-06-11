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

        <a
            href="{{ route('guru.e-learning.create') }}"
            class="btn btn-primary">

            <i class="bi bi-plus-lg me-1"></i>
            Buat Forum

        </a>

    </div>

    @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif

    @if($forums->count())

    <div class="row">

        @foreach($forums as $forum)

            <div class="col-xl-4 col-lg-6 col-md-6 mb-4">

                <div class="card forum-card border-0 h-100">

                    <div class="card-body d-flex flex-column">

                        {{-- Badge Mapel --}}
                        <div class="mb-3">

                            <span class="badge bg-primary">

                                {{ $forum->mapel?->nama_mapel ?? 'Tanpa Mapel' }}

                            </span>

                        </div>

                        {{-- Judul --}}
                        <h5 class="fw-bold mb-3">

                            {{ $forum->judul }}

                        </h5>

                        {{-- Info --}}
                        <div class="small text-muted mb-3">

                            <div class="mb-1">

                                <i class="bi bi-person me-1"></i>

                                {{ $forum->guru?->nama ?? '-' }}

                            </div>

                            <div class="mb-1">

                                <i class="bi bi-people me-1"></i>

                                {{ $forum->kelas?->nama_kelas ?? '-' }}

                            </div>

                            <div>

                                <i class="bi bi-clock me-1"></i>

                                {{ $forum->created_at->diffForHumans() }}

                            </div>

                        </div>

                        {{-- Preview Konten --}}
                        <div class="forum-preview text-muted mb-4">

                            {!! \Illuminate\Support\Str::limit(strip_tags($forum->konten), 120) !!}
                            
                        </div>

                        {{-- Statistik --}}
                        <div class="d-flex justify-content-between align-items-center mb-3">

                            <span class="small text-muted">

                                <i class="bi bi-paperclip me-1"></i>

                                {{ $forum->lampiran->count() }}

                                Lampiran

                            </span>

                            {{-- Nanti dipakai saat komentar sudah dibuat --}}
                            <span class="small text-muted">

                                <i class="bi bi-chat-dots me-1"></i>

                                0 Komentar

                            </span>

                        </div>

                        {{-- Tombol --}}
                        <div class="mt-auto">

                            <a
                                href="{{ route('guru.e-learning.show', $forum->id) }}"
                                class="btn btn-primary w-100">

                                <i class="bi bi-box-arrow-in-right me-1"></i>

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
                class="bi bi-mortarboard"
                style="font-size:70px"></i>

            <h5 class="mt-3">

                Belum ada forum pembelajaran

            </h5>

            <p class="text-muted">

                Mulai buat forum pertama untuk siswa.

            </p>

            <a
                href="{{ route('guru.e-learning.create') }}"
                class="btn btn-primary">

                Buat Forum

            </a>

        </div>

    </div>

@endif

</div>

<style>

.forum-card{
    border-radius:20px;
    overflow:hidden;
    transition:.3s;
    box-shadow:0 5px 20px rgba(0,0,0,.08);
}

.forum-card:hover{
    transform:translateY(-5px);
    box-shadow:0 10px 30px rgba(0,0,0,.15);
}

</style>

@endsection