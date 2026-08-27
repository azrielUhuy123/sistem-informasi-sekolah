@extends('layouts.app')

@section('title', 'Tambah Dokumen Sekolah')

@section('content')

<div class="container-fluid py-4">
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="mb-4"> 
                <h3 class="text-primary fw-bold text-center mb-1">Tambah Dokumen Sekolah</h4>
                <p class="text-muted text-center">Upload dokumen internal sekolah</p>
            </div>
            {{-- ALERT --}}            
 
            <x-alert.flash key="error" type="danger"/>

            <form action="{{ route('tu.dokumen.store') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                {{-- Hidden Jenis Dokumen --}}
                <input type="hidden"
                       name="jenis_dokumen"
                       value="sekolah">

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

                {{-- Kategori Surat --}}
                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Kategori Surat
                    </label>

                    <input type="text"
                           name="kategori_surat"
                           class="form-control"
                           placeholder="Contoh: SK, Internal, Guru"
                           oninput="this.value = this.value.replace(/\s+/g, ' ').replace(/(^|\s)\S/g, c => c.toUpperCase())"
                        required>

                </div>

                {{-- Upload File --}}
                <div class="mb-5">

                    <label class="form-label fw-semibold">
                        Upload File
                    </label>

                    <input type="file"
                           name="file"
                           class="form-control"
                           required>

                    <small class="text-muted">
                        Format file: PDF, DOC, DOCX
                    </small>

                </div>

                {{-- BUTTON --}}
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="{{ route('tu.dokumen.sekolah') }}" class="btn btn-outline-secondary rounded-3">
                        <i class="bi bi-chevron-left me-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        Simpan <i class="bi bi-save ms-1"></i>
                    </button>
                </div>

            </form>

        </div>

    </div>

</div>

@endsection