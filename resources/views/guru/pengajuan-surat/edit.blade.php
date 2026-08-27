@extends('layouts.app')

@section('title', 'Edit Pengajuan Surat')

@section('content')

<div class="container-fluid py-4">

    {{-- HERO --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

        <div class="card-body p-4 p-lg-5 text-white"
            style="background:linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);">

            <div class="row align-items-center">

                <div class="col-lg-8">

                    <h2 class="fw-bold mb-2">

                        Edit Pengajuan Surat

                    </h2>

                    <p class="mb-0 opacity-75">

                        Perbarui informasi pengajuan surat sebelum dikirim atau diproses lebih lanjut.

                    </p>

                </div>

                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">

                    <span class="badge bg-white text-warning rounded-pill px-4 py-3 fs-6">

                        <i class="bi bi-pencil-square me-2"></i>

                        Mode Edit

                    </span>

                </div>

            </div>

        </div>

    </div>

    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white border-0 py-4">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                <div>

                    <h5 class="fw-bold mb-1">

                        Form Edit Pengajuan

                    </h5>

                    <small class="text-muted">

                        Lengkapi seluruh data sebelum menyimpan perubahan.

                    </small>

                </div>

                <a href="{{ route('guru.pengajuan-surat.index') }}"
                    class="btn btn-outline-secondary rounded-3 rounded-pill">

                    <i class="bi bi-arrow-left me-2"></i>

                    Kembali

                </a>

            </div>

        </div>

        <div class="card-body p-4">

            <form
                action="{{ route('guru.pengajuan-surat.update', $pengajuan->id) }}"
                method="POST"
                enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <div class="row g-4">

                                    <div class="col-md-6">

                        <label class="form-label fw-semibold">

                            Nama Surat

                        </label>

                        <input
                            type="text"
                            name="nama_surat"
                            class="form-control"
                            value="{{ old('nama_surat',$pengajuan->nama_surat) }}"
                            required>

                    </div>

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">

                            Kategori Surat

                        </label>

                        <input
                            type="text"
                            name="kategori_surat"
                            class="form-control"
                            value="{{ old('kategori_surat',$pengajuan->kategori_surat) }}"
                            required>

                    </div>

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">

                            Perihal

                        </label>

                        <input
                            type="text"
                            name="perihal"
                            class="form-control"
                            value="{{ old('perihal',$pengajuan->perihal) }}"
                            required>

                    </div>

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">

                            Tujuan Surat

                        </label>

                        <input
                            type="text"
                            name="tujuan_surat"
                            class="form-control"
                            value="{{ old('tujuan_surat',$pengajuan->tujuan_surat) }}"
                            required>

                    </div>

                    <div class="col-12">

                        <label class="form-label fw-semibold">

                            File Surat Saat Ini

                        </label>

                        <div class="current-file">

                            <div class="d-flex align-items-center">

                                <div class="file-icon me-3">

                                    <i class="bi bi-file-earmark-pdf-fill"></i>

                                </div>

                                <div class="flex-grow-1">

                                    <div class="fw-bold">

                                        {{ $pengajuan->nama_surat }}

                                    </div>

                                    <small class="text-muted">

                                        File yang sedang digunakan

                                    </small>

                                </div>

                                <a href="{{ asset('uploads/pengajuan-surat/'.$pengajuan->file_surat) }}"
                                    target="_blank"
                                    class="btn btn-outline-primary rounded-pill">

                                    <i class="bi bi-eye-fill me-1"></i>

                                    Lihat

                                </a>

                            </div>

                        </div>

                    </div>

                    <div class="col-12">

                        <label class="form-label fw-semibold">

                            Upload File Baru

                        </label>

                        <input
                            type="file"
                            name="file_surat"
                            class="form-control">

                        <small class="text-muted">

                            Kosongkan apabila file lama masih digunakan.

                        </small>

                    </div>

                    <div class="col-12">

                        <hr>

                        <div class="d-flex justify-content-end gap-2">

                            <a href="{{ route('guru.pengajuan-surat.index') }}"
                                class="btn btn-outline-secondary rounded-3 rounded-pill px-4">

                                Batal

                            </a>

                            <button
                                type="submit"
                                class="btn btn-primary rounded-pill px-4">

                                <i class="bi bi-check-circle-fill me-2"></i>

                                Update Pengajuan

                            </button>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

<style>

.card{

    transition:.25s;

}

.card:hover{

    box-shadow:0 18px 45px rgba(37,99,235,.08)!important;

}

.form-control{

    border-radius:14px;

    min-height:48px;

}

.form-control:focus{

    border-color:#2563eb;

    box-shadow:0 0 0 .2rem rgba(37,99,235,.15);

}

.current-file{

    background:#f8fafc;

    border-radius:18px;

    padding:18px;

    border:1px solid #eef2f7;

}

.file-icon{

    width:58px;

    height:58px;

    border-radius:18px;

    background:linear-gradient(135deg,#2563eb,#0f4c81);

    color:#fff;

    display:flex;

    align-items:center;

    justify-content:center;

    font-size:1.4rem;

    flex-shrink:0;

}

.btn{

    font-weight:600;

    transition:.2s;

}

.btn:hover{

    transform:translateY(-2px);

}

</style>

@endsection