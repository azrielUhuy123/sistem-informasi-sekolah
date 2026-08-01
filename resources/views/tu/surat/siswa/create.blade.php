@extends('layouts.app')

@section('title', 'Tambah Dokumen Siswa')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="mb-1">Tambah Dokumen Siswa</h1>

            <p class="text-muted mb-0">
                Upload dokumen khusus siswa
            </p>
        </div>
    </div>

    {{-- VALIDATION ERROR --}}
    @if ($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    {{-- FORM CARD --}}
    <div class="card shadow-sm border-0">

        <div class="card-body p-4">

            <form action="{{ route('tu.dokumen.store') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                {{-- Hidden Jenis Dokumen --}}
                <input type="hidden"
                       name="jenis_dokumen"
                       value="siswa">

                {{-- Nama Surat --}}
                <div class="mb-4">

                    <label class="form-label fw-semibold">
                        Nama Surat
                    </label>

                    <input type="text"
                           name="nama_surat"
                           class="form-control rounded-3"
                           placeholder="Masukkan nama surat"
                           value="{{ old('nama_surat') }}"
                           required>

                </div>

                {{-- Kategori Surat --}}
                <div class="mb-4">

                    <label class="form-label fw-semibold">
                        Kategori Surat
                    </label>

                    <input type="text"
                           name="kategori_surat"
                           class="form-control rounded-3"
                           placeholder="Contoh: Akademik, Panduan, Formulir"
                           value="{{ old('kategori_surat') }}"
                           required>

                </div>

                {{-- Upload File --}}
                <div class="mb-4">

                    <label class="form-label fw-semibold">
                        Upload File
                    </label>

                    <input type="file"
                           name="file"
                           class="form-control rounded-3"
                           required>

                    <small class="text-muted">
                        Format file: PDF, DOC, DOCX
                    </small>

                </div>

                {{-- BUTTON --}}
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="{{ route('tu.dokumen.siswa') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save me-1"></i> Simpan Data
                    </button>
                </div>

            </form>

        </div>

    </div>

</div>

@endsection