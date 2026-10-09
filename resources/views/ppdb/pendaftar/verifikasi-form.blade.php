@extends('layouts.app')

@section('title', 'Detail Pendaftar')

@section('content')

    <div class="container-fluid">
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
                                                           Belum Diputuskan
                                                        </span>
                                                    @elseif($pendaftar->status == 'verifikasi')
                                                        <span class="badge-status verifikasi">                                                            
                                                            Tahap Verifikasi (Data Lama)
                                                        </span>
                                                    @elseif($pendaftar->status === 'diterima')
                                                        <span class="badge-status aktif">                                                            
                                                            Diterima
                                                        </span>
                                                    @elseif($pendaftar->status === 'ditolak')
                                                        <span class="badge-status nonaktif">
                                                            Ditolak
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
                                <h5 class="fw-bold mb-3">Berkas Pendaftar</h5>

                                {{-- Kartu Keluarga --}}
                                <div class="d-flex justify-content-between align-items-center border-bottom py-2">

                                    <span class="fw-semibold">
                                        Kartu Keluarga <span class="text-danger">(Wajib)</span>
                                    </span>

                                    @if($pendaftar->kartu_keluarga)

                                        <a
                                            href="{{ asset('storage/'.$pendaftar->kartu_keluarga) }}"
                                            target="_blank"
                                            class="btn btn-primary btn-sm rounded-3"
                                        >
                                            <i class="bi bi-file-earmark-text-fill me-2"></i>
                                            Lihat <span class="desc-add">Berkas</span>                                           
                                        </a>

                                    @else

                                        <span class="badge bg-secondary">
                                            <i class="bi bi-dash-lg"></i>
                                        </span>

                                    @endif

                                </div>

                                {{-- KTP --}}
                                <div class="d-flex justify-content-between align-items-center border-bottom py-2">

                                    <span class="fw-semibold">
                                        KTP Orangtua <span class="text-danger">(Wajib)</span>
                                    </span>

                                    @if($pendaftar->ktp)

                                        <a
                                            href="{{ asset('storage/'.$pendaftar->ktp) }}"
                                            target="_blank"
                                            class="btn btn-primary btn-sm rounded-3"
                                        >
                                            <i class="bi bi-file-earmark-text-fill me-2"></i>
                                            Lihat <span class="desc-add">Berkas</span>
                                        </a>

                                    @else

                                        <span class="badge bg-secondary">
                                            <i class="bi bi-dash-lg"></i>
                                        </span>

                                    @endif

                                </div>

                                {{-- Ijazah --}}
                                <div class="d-flex justify-content-between align-items-center border-bottom py-2">

                                    <span class="fw-semibold">
                                        Ijazah <span class="text-muted">(Opsional)</span>
                                    </span>

                                    @if($pendaftar->ijazah)

                                        <a
                                            href="{{ asset('storage/'.$pendaftar->ijazah) }}"
                                            target="_blank"
                                            class="btn btn-primary btn-sm rounded-3"
                                        >
                                            <i class="bi bi-file-earmark-text-fill me-2"></i>
                                            Lihat <span class="desc-add">Berkas</span>
                                        </a>

                                    @else

                                        <span class="badge bg-secondary">
                                            <i class="bi bi-dash-lg"></i>
                                        </span>

                                    @endif

                                </div>

                                {{-- Akta --}}
                                <div class="d-flex justify-content-between align-items-center border-bottom py-2">

                                    <span class="fw-semibold">
                                        Akta Kelahiran <span class="text-muted">(Opsional)</span>
                                    </span>

                                    @if($pendaftar->akta_lahir)

                                        <a
                                            href="{{ asset('storage/'.$pendaftar->akta_lahir) }}"
                                            target="_blank"
                                            class="btn btn-primary btn-sm rounded-3"
                                        >
                                            <i class="bi bi-file-earmark-text-fill me-2"></i>
                                            Lihat <span class="desc-add">Berkas</span>                                              
                                        </a>

                                    @else

                                        <span class="badge bg-secondary">
                                            <i class="bi bi-dash-lg"></i>
                                        </span>

                                    @endif

                                </div>

                                {{-- KIP --}}
                                <div class="d-flex justify-content-between align-items-center border-bottom py-2">

                                    <span class="fw-semibold">
                                        Kartu Indonesia Pintar <span class="text-muted">(Opsional)</span>
                                    </span>

                                    @if($pendaftar->kartu_kip)

                                        <a
                                            href="{{ asset('storage/'.$pendaftar->kartu_kip) }}"
                                            target="_blank"
                                            class="btn btn-primary btn-sm rounded-3"
                                        >
                                            <i class="bi bi-file-earmark-text-fill me-2"></i>
                                            Lihat <span class="desc-add">Berkas</span>
                                        </a>

                                    @else

                                        <span class="badge bg-secondary">
                                            <i class="bi bi-dash-lg"></i>
                                        </span>

                                    @endif

                                </div>

                                {{-- Pas Foto --}}
                                <div class="d-flex justify-content-between align-items-center py-2">

                                    <span class="fw-semibold">
                                        Pas Foto <span class="text-danger">(Wajib)</span>
                                    </span>

                                    @if($pendaftar->foto)

                                        <a
                                            href="{{ asset('storage/'.$pendaftar->foto) }}"
                                            target="_blank"
                                            class="btn btn-primary btn-sm rounded-3"
                                        >
                                            <i class="bi bi-file-earmark-text-fill me-2"></i>
                                            Lihat <span class="desc-add">Berkas</span>                             
                                        </a>

                                    @else

                                        <span class="badge bg-secondary">
                                            <i class="bi bi-dash-lg"></i>
                                        </span>

                                    @endif

                                </div>

                                @if(in_array($pendaftar->status, ['pending', 'verifikasi'], true))

                                    <hr class="my-4">

                                    <div class="d-flex justify-content-end gap-2">

                                        {{-- FORM KEPUTUSAN DITOLAK --}}
                                        <form
                                            method="POST"
                                            action="{{ route('tu.ppdb.pendaftar.tolak', $pendaftar) }}"
                                            class="m-0"
                                        >
                                            @csrf

                                            <button
                                                type="submit"
                                                class="btn btn-outline-danger rounded-3 btn-confirm"
                                                data-title="TOLAK PENDAFTAR INI?"
                                                data-message="Penolakan adalah keputusan akhir dan tidak dapat diajukan ulang."
                                                data-button="btn-danger"
                                            >
                                                <i class="bi bi-x-circle me-1"></i>
                                                Tolak
                                            </button>
                                        </form>

                                        {{-- FORM TERIMA --}}
                                        <form
                                            method="POST"
                                            action="{{ route('tu.ppdb.pendaftar.terima', $pendaftar) }}"
                                            id="terima-form"
                                            class="m-0"
                                        >
                                            @csrf

                                            <button
                                                type="submit"
                                                class="btn btn-success rounded-3 btn-confirm"
                                                data-title="TERIMA PESERTA INI?"
                                                data-message="Peserta akan dinyatakan diterima dan dapat melanjutkan ke proses daftar ulang."
                                                data-button="btn-success"
                                            >
                                                <i class="bi bi-check-circle me-1"></i>
                                                Terima <span class="desc-add">Peserta</span>
                                            </button>
                                        </form>

                                    </div>

                                @endif
                            </div>

                        </div>

                    </div>
                    <hr class="my-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <button  
                                type="button"
                                class="btn btn-outline-secondary rounded-3"
                                onclick="history.back()"
                            >
                                <i class="bi bi-chevron-left me-1"></i> Kembali
                            </button>
                        </div>
                    </div>
    
                </div>
            </div>
        </div>
    </div>
@endsection
