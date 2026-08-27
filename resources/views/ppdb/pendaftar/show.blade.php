@extends('layouts.app')

@section('title', 'Detail Pendaftar')

@section('content')

    <div class="container-fluid">
        {{-- ALERT --}}
        <x-alert.flash key="success" type="success"/>

        <x-alert.flash key="error" type="danger"/>

        <div class="card shadow-sm border-0 overflow-hidden">
            <div class="card-body p-4 p-lg-5">

                <div class="row g-4 align-items-start">

                    <div class="col-lg-4">
                        <div class="bg-primary bg-opacity-10 rounded-4 p-4 border h-100">
                            <div class="d-flex flex-column align-items-center text-center gap-3">

                                <div>
                                    <p class="text-muted mb-2">Pendaftar</p>
                                    <h4 class="mb-1 fw-bold">{{ $pendaftar->nama }}</h4>

                                    <div class="d-flex flex-wrap gap-2 justify-content-center mt-2">
                                        <span class="badge bg-light-subtle text-primary-emphasis border">
                                            NIK : {{ $pendaftar->nik }}
                                        </span>
                                        <span class="badge bg-light-subtle text-primary-emphasis border">
                                            NO. KK : {{ $pendaftar->nomor_kk ?? '-' }}
                                        </span>
                                    </div>
                                </div>

                                <div>
                                    <div class="row g-2">
                                        <div class="col-12">
                                            <div class="border rounded-3 p-2 bg-white">
                                                <div class="small text-muted">Status Pendaftar</div>
                                                <div>
                                                    @if($pendaftar->status == 'pending')
                                                        <span class="badge-status pending">
                                                           Pending
                                                        </span>
                                                    @elseif($pendaftar->status == 'verifikasi')
                                                        <span class="badge-status verifikasi">                                                            
                                                            Verifikasi
                                                        </span>
                                                    @elseif($pendaftar->status === 'diterima')
                                                        <span class="badge-status aktif">                                                            
                                                            Diterima
                                                        </span>                                                    
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="card border-0 h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="fw-bold mb-0">Profil Pendaftar</h5>
                                    <span class="badge bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle">
                                        <i class="bi bi-person-lines-fill me-1"></i> Master Data
                                    </span>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="small text-muted mb-1">Tempat, Tanggal Lahir</div>
                                        <div class="fw-semibold">
                                            {{ $pendaftar->tempat_lahir ?? '-' }},
                                            {{ $pendaftar->tanggal_lahir
                                            ? \Carbon\Carbon::parse($pendaftar->tanggal_lahir)->format('d-m-Y')
                                            : '-' }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="small text-muted mb-1">Agama</div>
                                        <div class="fw-semibold">{{ $pendaftar->agama }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="small text-muted mb-1">Alamat</div>
                                        <div class="fw-semibold">{{ $pendaftar->alamat }}</div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="small text-muted mb-2">Jenis Kelamin</div>
                                        <div class="fw-semibold">
                                            @if($pendaftar->jenis_kelamin == 'L') 
                                                <span class="badge bg-indigo rounded-pill px-2 py-1">
                                                    <i class="bi bi-gender-male me-1"></i>
                                                    Laki-Laki
                                                </span>
                                            @else
                                                <span class="badge bg-rose rounded-pill px-2 py-1">
                                                    <i class="bi bi-gender-female me-1"></i>
                                                    Perempuan
                                                </span>
                                            @endif
                                        </div>
                                    </div>   
                                    <div class="col-md-8">
                                        <a
                                        href="{{ route('tu.ppdb.pendaftar.form-verifikasi',$pendaftar) }}"
                                        class="btn btn-primary rounded-3"
                                        >
                                            <i class="bi bi-file-earmark-text-fill me-1"></i>
                                            Detail Berkas <span class="desc-add">{{ $pendaftar->nama }}</span>
                                        </a>  
                                    </div>                               
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <hr class="my-4">

                <div class="row g-4">

                    <div class="col-lg-6">
                        <div class="card border h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-2">
                                        <i class="bi bi-file-earmark-text fs-5 text-primary"></i>
                                    </div>
                                    <h5 class="fw-bold mb-0">Informasi Akademik</h5>
                                </div>

                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Nomor Pendaftaran</div>
                                        <div class="fw-semibold">{{ $pendaftar->no_pendaftaran }}</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Tahun Ajaran</div>
                                        <div class="fw-semibold">{{ $pendaftar->tahunAjaran?->tahun }}</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Tanggal Pendaftaran</div>
                                        <div class="fw-semibold">{{ $pendaftar->tanggal_pendaftaran }}</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Status Daftar Ulang</div>
                                        <div class="fw-semibold">{{ ucfirst($pendaftar->status_daftar_ulang ?? '-') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card border h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle bg-info bg-opacity-10 p-2 me-2">
                                        <i class="bi bi-briefcase fs-5 text-info"></i>
                                    </div>
                                    <h5 class="fw-bold mb-0">Data Ayah</h5>
                                </div>

                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Nama Ayah</div>
                                        <div class="fw-semibold">{{ optional($pendaftar->ayah)->nama }}</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Pekerjaan Ayah</div>
                                        <div class="fw-semibold">{{ optional($pendaftar->ayah)->pekerjaan  }}</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Penghasilan</div>
                                        <div class="fw-semibold">{{ optional($pendaftar->ayah)->penghasilan }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card border h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle bg-info bg-opacity-10 p-2 me-2">
                                        <i class="bi bi-briefcase fs-5 text-info"></i>
                                    </div>
                                    <h5 class="fw-bold mb-0">Data Ibu</h5>
                                </div>

                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Nama Ibu</div>
                                        <div class="fw-semibold">{{ optional($pendaftar->ibu)->nama }}</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Pekerjaan Ibu</div>
                                        <div class="fw-semibold">{{ optional($pendaftar->ibu)->pekerjaan  }}</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Penghasilan</div>
                                        <div class="fw-semibold">{{ optional($pendaftar->ibu)->penghasilan }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card border h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle bg-secondary bg-opacity-10 p-2 me-2">
                                        <i class="bi bi-award fs-5 text-secondary"></i>
                                    </div>
                                    <h5 class="fw-bold mb-0">Data Kependudukan</h5>
                                </div>

                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">NIK</div>
                                        <div class="fw-semibold">{{ $pendaftar->nik }}</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Nomor KK</div>
                                        <div class="fw-semibold">{{ $pendaftar->nomor_kk }}</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Nomor Akta</div>
                                        <div class="fw-semibold">{{ $pendaftar->nomor_akta }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
        <div class="sticky-bottom bg-white border border-light-subtle py-3 mt-4">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('tu.ppdb.pendaftar.index') }}" class="btn btn-outline-secondary rounded-3 ms-4">
                    <i class="bi bi-chevron-left me-1"></i> Kembali
                </a>
                @if($pendaftar->status == 'pending')
                    <form
                        action="{{ route('tu.ppdb.pendaftar.simpan-verifikasi', $pendaftar) }}"
                        method="POST"
                        class="d-inline"
                    >
                        @csrf
                        <button
                            type="submit"
                            class="btn btn-primary rounded-3 me-4"
                        >
                            <i class="bi bi-search me-1"></i>
                            Mulai Verifikasi
                        </button>
                    </form>     
                @else
                    <button 
                        type="button" 
                        class="btn btn-secondary rounded-3 me-4" 
                        disabled
                    >
                            <i class="bi bi-search me-1"></i>
                            Mulai Verifikasi
                    </button>           
                @endif
            </div>
        </div>

    </div>
@endsection
