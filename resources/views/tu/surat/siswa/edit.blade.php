@extends('layouts.app')

@section('title', 'Edit Dokumen Siswa')

@section('content')

<div class="container-fluid py-4">

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="mb-4"> 
                <h3 class="text-primary fw-bold text-center mb-1">Edit Dokumen Siswa</h4>
                <p class="text-muted text-center">Perbarui data dokumen siswa</p>
            </div>
             {{-- ALERT --}}            
 
            <x-alert.flash key="error" type="danger"/>

            <form action="{{ route('tu.dokumen.update', $dokumen->id) }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf
                @method('PUT')

                {{-- Nama Surat --}}
                <div class="mb-3">

                    <label class="fw-semibold form-label">
                        Nama Surat
                    </label>

                    <input type="text"
                           name="nama_surat"
                           class="form-control"
                           value="{{ old('nama_surat', $dokumen->nama_surat) }}"
                           oninput="this.value = this.value.replace(/\s+/g, ' ').replace(/(^|\s)\S/g, c => c.toUpperCase())"
                        required>

                </div>

                {{-- Kategori Surat --}}
                <div class="mb-3">

                    <label class="fw-semibold form-label">
                        Kategori Surat
                    </label>

                    <input type="text"
                           name="kategori_surat"
                           class="form-control"
                           value="{{ old('kategori_surat', $dokumen->kategori_surat) }}"
                           oninput="this.value = this.value.replace(/\s+/g, ' ').replace(/(^|\s)\S/g, c => c.toUpperCase())"
                        required>

                </div>

                {{-- FILE SAAT INI --}}
                <div class="mb-3">

                    <label class="fw-semibold form-label d-block">
                        File Saat Ini
                    </label>

                    <a href="{{ asset('uploads/dokumen/' . $dokumen->file) }}"
                       target="_blank"
                       class="btn btn-primary btn-sm rounded-3">

                        <i class="bi bi-file-earmark-text-fill me-2"></i>
                        Lihat Berkas

                    </a>

                </div>

                {{-- GANTI FILE --}}
                <div class="mb-5">

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
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="{{ route('tu.dokumen.siswa') }}" class="btn btn-outline-secondary rounded-3">
                        <i class="bi bi-chevron-left me-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary rounded-3">
                        Simpan <i class="bi bi-save ms-1"></i>
                    </button>
                </div>

            </form>

        </div>

    </div>

</div>

@endsection