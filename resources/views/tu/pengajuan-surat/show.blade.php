@extends('layouts.app')

@section('title', 'Detail Pengajuan Surat')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">
                    Detail Pengajuan Surat
                </h4>

                <p class="text-muted mb-0">
                    Informasi lengkap pengajuan surat
                </p>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="border rounded-4 p-3 h-100">
                            <small class="text-muted">
                                Nama Surat
                            </small>

                            <div class="fw-bold fs-5">
                                {{ $pengajuan->nama_surat }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded-4 p-3 h-100">
                            <small class="text-muted">
                                Kategori
                            </small>

                            <div class="fw-bold fs-5">
                                {{ $pengajuan->kategori_surat }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 bg-light rounded-4 mt-4">
                    <div class="card-body">
                        <small class="text-muted">
                            Perihal
                        </small>

                        <p class="mb-0 mt-2">
                            {{ $pengajuan->perihal }}
                        </p>
                    </div>
                </div>

                <div class="card border-0 bg-light rounded-4 mt-4">
                    <div class="card-body">
                        <small class="text-muted">
                            Tujuan Surat
                        </small>

                        <p class="mb-0 mt-2">
                            {{ $pengajuan->tujuan_surat }}
                        </p>
                    </div>
                </div>

                {{-- STATUS --}}
                <div class="mb-4 mt-4 d-flex flex-wrap align-items-center gap-2">
                    <label class="fw-semibold text-muted mb-0">
                        Status
                    </label>

                    @if ($pengajuan->status == 'draft')
                        <span class="badge bg-secondary rounded-pill px-3 py-2">
                            Draft
                        </span>
                    @elseif ($pengajuan->status == 'disetujui')
                        <span class="badge bg-success rounded-pill px-3 py-2">
                            Valid
                        </span>
                    @else
                        <span class="badge bg-danger rounded-pill px-3 py-2">
                            Ditolak
                        </span>
                    @endif
                </div>

                <div class="d-flex flex-wrap justify-content-between align-items-center mt-4 gap-2">
                    <a
                        href="{{ route('tu.pengajuan-surat.index') }}"
                        class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-arrow-left me-1"></i>
                        Kembali
                    </a>

                    <div class="d-flex flex-wrap gap-2">
                        <a
                            href="{{ asset('uploads/pengajuan-surat/' . $pengajuan->file_surat) }}"
                            target="_blank"
                            class="btn btn-primary rounded-pill px-4">
                            <i class="bi bi-eye me-1"></i>
                            Lihat Surat
                        </a>

                        <a
                            href="{{ asset('uploads/pengajuan-surat/' . $pengajuan->file_surat) }}"
                            download
                            class="btn btn-success rounded-pill px-4">
                            <i class="bi bi-download me-1"></i>
                            Download
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection