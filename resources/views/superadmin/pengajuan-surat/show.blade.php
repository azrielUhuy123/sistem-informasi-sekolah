@extends('layouts.app')

@section('title', 'Detail Validasi Surat')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="fw-bold mb-1">
                Detail Pengajuan Surat
            </h4>

            <p class="text-muted mb-0">
                Validasi dan persetujuan surat keluar
            </p>

        </div>

        <a href="{{ route('superadmin.pengajuan-surat.index') }}"
           class="btn btn-secondary rounded-3">

            Kembali

        </a>

    </div>

    {{-- ALERT --}}
    @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif

    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-4">

            {{-- NAMA SURAT --}}
            <div class="mb-4">

                <label class="text-muted fw-semibold">
                    Nama Surat
                </label>

                <h5 class="mb-0">
                    {{ $pengajuan->nama_surat }}
                </h5>

            </div>

            {{-- KATEGORI --}}
            <div class="mb-4">

                <label class="text-muted fw-semibold">
                    Kategori Surat
                </label>

                <h5 class="mb-0">
                    {{ $pengajuan->kategori_surat }}
                </h5>

            </div>

            {{-- TUJUAN --}}
            <div class="mb-4">

                <label class="text-muted fw-semibold">
                    Tujuan Surat
                </label>

                <h5 class="mb-0">
                    {{ $pengajuan->tujuan_surat }}
                </h5>

            </div>

            {{-- STATUS --}}
            <div class="mb-4">

                <label class="text-muted fw-semibold">
                    Status
                </label>

                <br>

                @if($pengajuan->status == 'pending')

                    <span class="badge bg-warning text-dark px-3 py-2">
                        Pending
                    </span>

                @elseif($pengajuan->status == 'disetujui')

                    <span class="badge bg-success px-3 py-2">
                        Disetujui
                    </span>

                @else

                    <span class="badge bg-danger px-3 py-2">
                        Ditolak
                    </span>

                @endif

            </div>

            {{-- FILE --}}
            <div class="mb-5">

                <label class="text-muted fw-semibold">
                    File Surat
                </label>

                <br><br>

                {{-- LIHAT --}}
                <a href="{{ asset('uploads/pengajuan-surat/' . $pengajuan->file_surat) }}"
                   target="_blank"
                   class="btn btn-primary rounded-3">

                    <i class="bi bi-eye"></i>
                    Lihat Surat

                </a>

                {{-- DOWNLOAD --}}
                <a href="{{ asset('uploads/pengajuan-surat/' . $pengajuan->file_surat) }}"
                   download
                   class="btn btn-success rounded-3">

                    <i class="bi bi-download"></i>
                    Download

                </a>

            </div>

            {{-- ACTION VALIDASI --}}
            @if($pengajuan->status == 'pending')

                <div class="d-flex gap-2">

                    {{-- VALIDASI --}}
                    <form action="{{ route('superadmin.pengajuan.validasi', $pengajuan->id) }}"
                        method="POST">

                        @csrf
                        @method('PATCH')

                        <button type="submit"
                                class="btn btn-success rounded-3">

                            <i class="bi bi-check-circle"></i>
                            Validasi Surat

                        </button>

                    </form>

                    {{-- TOLAK --}}
                    <form action="{{ route('superadmin.pengajuan.tolak', $pengajuan->id) }}"
                            method="POST">

                        @csrf
                        @method('PATCH')

                        <button type="submit"
                                class="btn btn-danger rounded-3">

                            <i class="bi bi-x-circle"></i>
                            Tolak

                        </button>

                    </form>

                </div>

            @endif

            {{-- VALIDATOR --}}
            @if($pengajuan->validated_by)

                <hr class="my-4">

                <div>

                    <label class="text-muted fw-semibold">
                        Divalidasi Oleh
                    </label>

                    <h6 class="mb-1">
                        {{ $pengajuan->validator->name ?? '-' }}
                    </h6>

                    <small class="text-muted">

                        {{ $pengajuan->validated_at }}

                    </small>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection