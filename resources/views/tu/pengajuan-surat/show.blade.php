@extends('layouts.app')

@section('title', 'Detail Pengajuan Surat')

@section('content')

<div class="container-fluid py-4">    

    <div class="card border-0 shadow-sm">

        <div class="card-body  p-4 p-lg-5">
            <div class="mb-4"> 
                <h3 class="text-primary fw-bold text-center mb-1">Detail Pengajuan Surat</h4>
                <p class="text-muted text-center">Informasi lengkap pengajuan surat</p>
            </div>

            <div class="row align-items-center">
                <div class="col-lg-4 text-center mb-4 mb-lg-0">                    
                    <div class="text-center mb-4">
                        <div class="file-icon mx-auto mb-3">
                            <i class="bi bi-file-earmark-pdf-fill"></i>
                        </div>
                        <h6 class="fw-bold text-label mb-1">
                            {{ $pengajuan->nama_surat }}
                        </h6>
                        <small class="text-muted">
                            Siap untuk dilihat atau diunduh
                        </small>
                    </div>
                    <div class="mb-2">
                        <div class="d-flex align-items-start gap-3">                           
                            <div class="flex-grow-1 min-w-0">
                                <small class="form-label">
                                    Status Pengajuan
                                </small>
                                <div class="fw-semibold">
                                    @if ($pengajuan->status == 'draft')                                            
                                        <i class="bi bi-hourglass-split text-primary me-2"></i> Validasi                                                                  
                                    @elseif ($pengajuan->status == 'disetujui')                                            
                                        <i class="bi bi-check-circle-fill text-primary me-2"></i> Valid                                            
                                    @else                                            
                                        <i class="bi bi-x-circle-fill text-primary me-2"></i> Ditolak                                            
                                    @endif
                                </div>                                
                            </div>
                        </div>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="{{ asset('uploads/pengajuan-surat/'.$pengajuan->file_surat) }}"
                            target="_blank"
                            class="btn btn-primary rounded-3">
                            <i class="bi bi-eye-fill me-2"></i>
                            Lihat Surat
                        </a>
                        <a href="{{ asset('uploads/pengajuan-surat/'.$pengajuan->file_surat) }}"
                            download
                            class="btn btn-success rounded-3">
                            <i class="bi bi-download me-2"></i>
                            Download Surat
                        </a>
                    </div>
                </div>                        
                {{-- INFORMASI SURAT --}}
                <div class="col-lg-8">
                    <div class="row mt-2 g-3">
                        <div class="col-12">
                            <div class="d-flex align-items-start gap-3 bg-light px-3 py-2 rounded-3">                            
                                <i class="bi bi-envelope-fill me-2 text-primary fs-5"></i>                           
                                <div class="flex-grow-1 min-w-0">
                                    <small class="form-label">
                                        Nama Surat 
                                    </small>
                                    <div class="fw-semibold text-dark"
                                        style="font-size: 1.05rem;">
                                        {{ $pengajuan->nama_surat }}
                                    </div>                                
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-start gap-3 bg-light px-3 py-2 rounded-3">                            
                                <i class="bi bi-list-nested me-2 text-primary fs-5"></i>                           
                                <div class="flex-grow-1 min-w-0">
                                    <small class="form-label">
                                        Kategori Surat
                                    </small>
                                    <div class="fw-semibold text-dark"
                                        style="font-size: 1.05rem;">
                                        {{ $pengajuan->kategori_surat }}
                                    </div>                                
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-start gap-3 bg-light px-3 py-2 rounded-3">                            
                                <i class="bi bi-chat-right-dots-fill me-2 text-primary fs-5"></i>                           
                                <div class="flex-grow-1 min-w-0">
                                    <small class="form-label">
                                        Perihal
                                    </small>
                                    <div class="fw-semibold text-dark"
                                        style="font-size: 1.05rem;">
                                        {{ $pengajuan->perihal }}
                                    </div>                                
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-start gap-3 bg-light px-3 py-2 rounded-3">                            
                                <i class="bi bi-send-fill me-2 text-primary fs-5"></i>                           
                                <div class="flex-grow-1 min-w-0">
                                    <small class="form-label">
                                        Tujuan Surat
                                    </small>
                                    <div class="fw-semibold text-dark"
                                        style="font-size: 1.05rem;">
                                        {{ $pengajuan->tujuan_surat }}
                                    </div>                                
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">   
                        <div class="col-12 col-sm-4 mb-2">
                            <div class="d-flex align-items-start gap-3 bg-light px-3 py-2 rounded-3">
                                <i class="bi-person-check text-primary fs-5"></i>
                                <div class="flex-grow-1 min-w-0">
                                    <small class="form-label">
                                        Validator
                                    </small>
                                    <div class="fw-semibold">  
                                        {{ $pengajuan->validator->name ?? '-' }} <i class="bi bi-patch-check-fill text-success ms-1"></i>                                   
                                    </div>                                
                                </div>
                            </div>
                        </div>                                                 
                        <div class="col-12 col-sm-4 mb-2">
                            <div class="d-flex align-items-start gap-3 bg-light px-3 py-2 rounded-3">
                                <i class="bi bi-clock-history text-primary fs-5"></i>
                                <div class="flex-grow-1 min-w-0">
                                    <small class="form-label">
                                        Tanggal Dibuat
                                    </small>
                                    <div class="fw-semibold">  
                                        {{ \Carbon\Carbon::parse($pengajuan->created_at)->translatedFormat('d F Y') }}                                      
                                    </div>                                
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="d-flex align-items-start gap-3 bg-light px-3 py-2 rounded-3">
                                <i class="bi bi-clock-history text-primary fs-5"></i>
                                <div class="flex-grow-1 min-w-0">
                                    <small class="form-label">
                                        Tanggal Diterima
                                    </small>
                                    <div class="fw-semibold">  
                                        {{ \Carbon\Carbon::parse($pengajuan->validated_at)->translatedFormat('d F Y') }}                                      
                                    </div>                                
                                </div>
                            </div>
                        </div>
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
<style>
.file-icon{

    width:80px;

    height:80px;

    border-radius:24px;

    background:linear-gradient(135deg,#2563eb,#0f4c81);

    color:#fff;

    display:flex;

    align-items:center;

    justify-content:center;

    font-size:2rem;

}

</style>

@endsection