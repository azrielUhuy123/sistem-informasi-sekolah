@extends('layouts.app')

@section('title', 'Buat Pengajuan Surat')

@section('content')

<div class="container-fluid py-4">

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <div class="mb-4">
                <h3 class="text-primary fw-bold text-center mb-1">
                    Buat Pengajuan Surat
                </h3>

                <p class="text-muted text-center">
                    Upload draft surat untuk divalidasi kepala sekolah
                </p>
            </div>

            {{-- ALERT --}}
            <x-alert.flash key="error" type="danger"/>

            <form action="{{ route('guru.pengajuan-surat.store') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                {{-- Kategori Surat --}}
                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Kategori Surat
                    </label>

                    <input type="text"
                           name="kategori_surat"
                           class="form-control"
                           placeholder="Contoh: Surat Tugas, Surat Edaran, Surat Keputusan"
                           oninput="this.value = this.value.replace(/\s+/g, ' ').replace(/(^|\s)\S/g, c => c.toUpperCase())"
                           required>

                </div>

                {{-- Nama Surat --}}
                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Nama Surat
                    </label>

                    <input type="text"
                           name="nama_surat"
                           class="form-control"
                           placeholder="Masukkan nama surat"
                           oninput="this.value = this.value.replace(/\s+/g, ' ').replace(/(^|\s)\S/g, c => c.toUpperCase())"
                           required>

                </div>

                {{-- Perihal --}}
                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Perihal
                    </label>

                    <input type="text"
                           name="perihal"
                           class="form-control"
                           placeholder="Masukkan perihal surat"
                           oninput="this.value = this.value.replace(/\s+/g, ' ').replace(/(^|\s)\S/g, c => c.toUpperCase())"
                           required>

                </div>

                {{-- Tujuan Surat --}}
                <div class="mb-4">

                    <label class="form-label fw-semibold">
                        Tujuan Surat
                    </label>

                    <input type="text"
                           name="tujuan_surat"
                           class="form-control"
                           placeholder="Masukkan tujuan surat"
                           oninput="this.value = this.value.replace(/\s+/g, ' ').replace(/(^|\s)\S/g, c => c.toUpperCase())"
                           required>

                </div>

                {{-- Upload Draft Surat --}}
                <div class="upload-box">
                    <label class="form-label fw-semibold">
                        Upload Draft Surat
                    </label>
                    <input type="file"
                           name="file_surat"
                           class="form-control modern-input"
                           accept=".pdf,.doc,.docx"
                           required>

                    <div class="upload-info">
                        <i class="bi bi-file-earmark-arrow-up"></i>
                        <span>
                            PDF, DOCX, DOC
                        </span>
                    </div>

                </div>

                {{-- BUTTON --}}
                <div class="d-flex justify-content-between align-items-center mt-4">

                    <a href="{{ route('guru.pengajuan-surat.index') }}"
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
