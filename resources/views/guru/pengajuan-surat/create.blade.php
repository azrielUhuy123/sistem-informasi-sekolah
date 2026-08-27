@extends('layouts.app')

@section('title', 'Buat Pengajuan Surat')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="fw-bold mb-1">
                Buat Pengajuan Surat
            </h4>

            <p class="text-muted mb-0">
                Upload draft surat untuk divalidasi kepala sekolah
            </p>

        </div>

        <a href="{{ route('guru.pengajuan-surat.index') }}"
           class="btn btn-secondary rounded-3">

            Kembali

        </a>

    </div>

    {{-- ERROR --}}
    @if ($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-4">

            <form action="{{ route('guru.pengajuan-surat.store') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                {{-- KATEGORI --}}
                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Kategori Surat
                    </label>

                    <input type="text"
                           name="kategori_surat"
                           class="form-control"
                           required>

                </div>

                {{-- NAMA SURAT --}}
                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Nama Surat
                    </label>

                    <input type="text"
                           name="nama_surat"
                           class="form-control"
                           required>

                </div>

                {{-- PERIHAL --}}
                <div class="mb-4">

                    <label class="form-label fw-semibold">
                        Perihal
                    </label>

                    <input
                        type="text"
                        name="perihal"
                        class="form-control"
                        placeholder="Masukkan perihal surat"
                        required
                    >

                </div>

                {{-- TUJUAN --}}
                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Tujuan Surat
                    </label>

                    <input type="text"
                           name="tujuan_surat"
                           class="form-control"
                           required>

                </div>

                {{-- FILE --}}
                <div class="mb-4">

                    <label class="form-label fw-semibold">
                        Upload Draft Surat
                    </label>

                    <input type="file"
                           name="file_surat"
                           class="form-control"
                           required>

                    <small class="text-muted">
                        Format file: PDF, DOC, DOCX
                    </small>

                </div>

                <button type="submit"
                        class="btn btn-primary rounded-3 px-4">

                    <i class="bi bi-send-check"></i>
                    Ajukan Surat

                </button>

            </form>

        </div>

    </div>

</div>

@endsection