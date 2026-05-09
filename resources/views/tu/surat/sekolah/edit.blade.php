@extends('layouts.app')

@section('title', 'Edit Dokumen Sekolah')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="mb-4 d-flex justify-content-between align-items-center">

        <div>
            <h3 class="fw-bold mb-1">
                Edit Dokumen Sekolah
            </h3>

            <p class="text-muted mb-0">
                Perbarui data dokumen sekolah
            </p>
        </div>

        <a href="{{ route('tu.dokumen.sekolah') }}"
           class="btn btn-secondary">

            <i class="fas fa-arrow-left me-1"></i>
            Kembali

        </a>

    </div>

    {{-- ERROR VALIDASI --}}
    @if ($errors->any())

        <div class="alert alert-danger">

            <strong>
                Terjadi kesalahan:
            </strong>

            <ul class="mt-2 mb-0">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    {{-- CARD --}}
    <div class="card shadow-sm border-0">

        <div class="card-body p-4">

            <form action="{{ route('tu.dokumen.update', $dokumen->id) }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf
                @method('PUT')

                {{-- Nama Surat --}}
                <div class="mb-4">

                    <label class="fw-semibold form-label">
                        Nama Surat
                    </label>

                    <input type="text"
                           name="nama_surat"
                           class="form-control"
                           value="{{ old('nama_surat', $dokumen->nama_surat) }}"
                           required>

                </div>

                {{-- Kategori Surat --}}
                <div class="mb-4">

                    <label class="fw-semibold form-label">
                        Kategori Surat
                    </label>

                    <input type="text"
                           name="kategori_surat"
                           class="form-control"
                           value="{{ old('kategori_surat', $dokumen->kategori_surat) }}"
                           required>

                </div>

                {{-- FILE SAAT INI --}}
                <div class="mb-4">

                    <label class="fw-semibold form-label d-block">
                        File Saat Ini
                    </label>

                    <a href="{{ asset('uploads/dokumen/' . $dokumen->file) }}"
                       target="_blank"
                       class="btn btn-secondary btn-sm">

                        <i class="fas fa-download me-1"></i>
                        Lihat / Download File

                    </a>

                    <div class="mt-2">
                        <small class="text-muted">
                            {{ $dokumen->file }}
                        </small>
                    </div>

                </div>

                {{-- GANTI FILE --}}
                <div class="mb-4">

                    <label class="fw-semibold form-label">
                        Upload File Baru
                    </label>

                    <input type="file"
                           name="file"
                           class="form-control">

                    <small class="text-muted">
                        Kosongkan jika tidak ingin mengganti file
                    </small>

                </div>

                {{-- BUTTON --}}
                <div class="d-flex justify-content-end">

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="fas fa-save me-1"></i>
                        Update Dokumen

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection