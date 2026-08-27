@extends('layouts.app')

@section('title', 'Detail Pengajuan Surat')

@section('content')

<div class="container-fluid py-4">    

    <div class="card border-0 shadow-sm">

        <div class="card-body">
            <div class="mb-4"> 
                <h3 class="text-primary fw-bold text-center mb-1">Detail Pengajuan Surat</h4>
                <p class="text-muted text-center">Informasi lengkap pengajuan surat</p>
            </div>

            {{-- INFORMASI SURAT --}}
            <div class="row g-4 mt-4">

                <div class="col-md-6">

                    <div class="px-3 mb-1">

                        <small class="text-muted">
                           <i class="bi bi-envelope-fill me-2 text-primary fs-5"></i> Nama Surat
                        </small>

                        <div class="fw-bold fs-5">
                            {{ $pengajuan->nama_surat }}
                        </div>

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="px-3 mb-1">

                        <small class="text-muted">
                           <i class="bi bi-list-nested me-2 text-primary fs-5"></i> Kategori
                        </small>

                        <div class="fw-bold fs-5">
                            {{ $pengajuan->kategori_surat }}
                        </div>

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="px-3 mb-1">

                        <small class="text-muted">
                            <i class="bi bi-chat-right-dots-fill me-2 text-primary fs-5"></i> Perihal
                        </small>

                        <div class="fw-semibold">
                            {{ $pengajuan->perihal }}
                        </div>

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="px-3 mb-1">

                        <small class="text-muted">
                           <i class="bi bi-send-fill me-2 text-primary fs-5"></i> Tujuan Surat
                        </small>

                        <div class="fw-semibold ">
                            {{ $pengajuan->tujuan_surat }}
                        </div>

                    </div>

                </div>

                <div class="col-md-12">
                    <div class="px-3 d-flex flex-wrap align-items-center gap-2">
                        <small class="text-muted fw-semibold">
                           Status
                        </small>
                        @if ($pengajuan->status == 'draft')
                            <span class="badge-status pending">
                                <i class="bi bi-hourglass-split me-2"></i> Validasi
                            </span>                        
                        @elseif ($pengajuan->status == 'disetujui')
                            <span class="badge-status aktif">
                                <i class="bi bi-check-circle-fill me-2"></i> Valid
                            </span>
                        @else
                            <span class="badge-status nonaktif">
                                <i class="bi bi-x-circle-fill me-2"></i> Ditolak
                            </span>
                        @endif
                    </div>
                </div> 
                <div class="col-md-6">                                  
                    <div class="d-flex flex-wrap gap-2 px-3 mb-3">  
                        <a
                            href="{{ asset('uploads/pengajuan-surat/' . $pengajuan->file_surat) }}"
                            target="_blank"
                            class="btn btn-primary btn-sm rounded-3"
                            >
                            <i class="bi bi-file-earmark-text-fill me-1"></i>
                            Lihat Berkas
                        </a>                  

                        <a
                            href="{{ asset('uploads/pengajuan-surat/' . $pengajuan->file_surat) }}"
                            download
                            class="btn btn-success btn-sm rounded-3"
                        >
                            <i class="bi bi-download me-1"></i>
                            Download
                        </a>

                    </div>
                </div>           
            </div>

            <hr class="my-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center mt-4 gap-2">

                <a
                    href="{{ route('tu.pengajuan-surat.index') }}"
                    class="btn btn-outline-secondary rounded-3"
                >
                    <i class="bi bi-chevron-left me-1"></i> <span class="desc-add">Kembali</span>                    
                </a>             
            </div>                    
        </div>

    </div>

</div>

@endsection