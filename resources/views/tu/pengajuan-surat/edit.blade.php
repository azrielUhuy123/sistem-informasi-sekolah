@extends('layouts.app')

@section('title', 'Edit Pengajuan Surat')

@section('content')

<div class="container-fluid py-4">

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <div class="mb-4">
                <h3 class="text-primary fw-bold text-center mb-1">
                    Edit Pengajuan Surat
                </h3>

                <p class="text-muted text-center">
                    Perbarui data pengajuan surat
                </p>
            </div>

            {{-- ALERT --}}
            <x-alert.flash key="error" type="danger"/>

            <form action="{{ route('tu.pengajuan-surat.update', $pengajuan->id) }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf
                @method('PUT')

                {{-- Kategori Surat --}}
                <div class="mb-3">

                    <label class="fw-semibold form-label">
                        Kategori Surat
                    </label>

                    <input type="text"
                           name="kategori_surat"
                           class="form-control"
                           value="{{ old('kategori_surat', $pengajuan->kategori_surat) }}"
                           oninput="this.value = this.value.replace(/\s+/g, ' ').replace(/(^|\s)\S/g, c => c.toUpperCase())"
                           required>

                </div>

                {{-- Nama Surat --}}
                <div class="mb-3">

                    <label class="fw-semibold form-label">
                        Nama Surat
                    </label>

                    <input type="text"
                           name="nama_surat"
                           class="form-control"
                           value="{{ old('nama_surat', $pengajuan->nama_surat) }}"
                           oninput="this.value = this.value.replace(/\s+/g, ' ').replace(/(^|\s)\S/g, c => c.toUpperCase())"
                           required>

                </div>

                {{-- Perihal --}}
                <div class="mb-3">

                    <label class="fw-semibold form-label">
                        Perihal
                    </label>

                    <input type="text"
                           name="perihal"
                           class="form-control"
                           value="{{ old('perihal', $pengajuan->perihal) }}"
                           placeholder="Masukkan perihal surat"
                           oninput="this.value = this.value.replace(/\s+/g, ' ').replace(/(^|\s)\S/g, c => c.toUpperCase())"
                           required>

                </div>

                {{-- Tujuan Surat --}}
                <div class="mb-3">

                    <label class="fw-semibold form-label">
                        Tujuan Surat
                    </label>

                    <input type="text"
                           name="tujuan_surat"
                           class="form-control"
                           value="{{ old('tujuan_surat', $pengajuan->tujuan_surat) }}"
                           placeholder="Masukkan tujuan surat"
                           oninput="this.value = this.value.replace(/\s+/g, ' ').replace(/(^|\s)\S/g, c => c.toUpperCase())"
                           required>

                </div>

                {{-- FILE SAAT INI --}}
                <div class="mb-3">

                    <label class="fw-semibold form-label d-block">
                        File Saat Ini
                    </label>

                    <a href="{{ asset('uploads/pengajuan-surat/' . $pengajuan->file_surat) }}"
                       target="_blank"
                       class="btn btn-primary btn-sm rounded-3">

                        <i class="bi bi-file-earmark-text-fill me-2"></i>
                        Lihat Berkas

                    </a>

                </div>

                {{-- GANTI FILE --}}
                <div class="mb-5">

                    <label class="fw-semibold form-label">
                        Upload Draft Surat Baru
                    </label>

                    <input type="file"
                           name="file_surat"
                           class="form-control"
                           accept=".pdf,.doc,.docx">

                    <small class="text-muted">
                        Kosongkan jika tidak ingin mengganti file.
                        Format file: PDF, DOC, DOCX
                    </small>

                </div>

                {{-- BUTTON --}}
                <div class="d-flex justify-content-between align-items-center mt-4">

                    <a href="{{ route('tu.pengajuan-surat.index') }}"
                       class="btn btn-outline-secondary rounded-3">
                        <i class="bi bi-chevron-left me-1"></i>
                        Kembali
                    </a>

                    <button type="submit"
                            class="btn btn-primary rounded-3">
                        Simpan
                        <i class="bi bi-save ms-1"></i>
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>

@endsection
