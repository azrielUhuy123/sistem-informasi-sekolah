@extends('layouts.ppdb-form')

@section('title','Status PPDB')

@section('content')

<style>
    .card.shadow {
        border: none;
        border-radius: 24px;
        overflow: hidden;
    }
    .card-header {
        border-bottom: none;            
    }        
    .card-body {
        padding: 1.5rem 1.25rem;
    }
    
    .tittle-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px;
    }
    
    .tittle-logo {
        width: 60px;            
    }

    .tittle-school {
        color: #0881c7;
        font-size: 14px;
        line-height: 1.2;
    }

    .tittle-school span {
        font-size: 26px;
        font-weight: 600;
        margin-bottom: 6px;
    }       

    @media (max-width: 768px) {                                
        .tittle-logo {
            margin-bottom: 18px;
        }
        .tittle-school {
            font-size: 13px;
        }
        .tittle-school h2 {
            font-size: 24px;
        }
            
    }

    @media (max-width: 575.98px) {
        .card-body {
            padding: 1.25rem 0.75rem;
        }
    }

    .status-progress {
        display: flex;
        align-items: flex-start;
        width: 100%;
    }

    .status-progress-item {
        display: flex;
        flex: 1 1 0;
        min-width: 0;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .status-progress-indicator {
        font-size: 1rem;
        line-height: 1;
    }

    .status-progress-item[data-status="completed"] .status-progress-indicator {
        color: #198754;
    }

    .status-progress-item[data-status="active"] .status-progress-indicator {
        color: #17416e;
    }

    .status-progress-item[data-status="pending"] .status-progress-indicator {
        color: #6c757d;
    }

    .status-progress-item[data-status="rejected"] .status-progress-indicator {
        color: #dc3545;
    }

    .status-progress-label {
        margin-top: 0.5rem;
        font-size: 0.875rem;
        line-height: 1.25;
        overflow-wrap: anywhere;
    }

    .status-progress-connector {
        flex: 1 1 0;
        min-width: 0.75rem;
        height: 2px;
        margin-top: 0.75rem;
        background: #dee2e6;
    }

    .status-progress-connector[data-status="completed"] {
        background: #198754;
    }

    @media (max-width: 575.98px) {
        .status-progress-indicator {
            font-size: 1.25rem;
        }

        .status-progress-label {
            font-size: 0.75rem;
        }

        .status-progress-connector {
            min-width: 0.5rem;
        }
    }
</style>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header">                
                    <div class="tittle-header">
                        <img src="{{ asset('assets/images/logo-kolaan.png') }}" class="tittle-logo">
                        <div class="tittle-school">
                            <span>Status Pendaftaran</span><br>
                            Sekolah Dasar Negeri<br>Mekar Jaya
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if((!isset($viaLink) || !$viaLink) && !isset($data))
                        <div class="text-center mb-4">
                            <h2 class="pt-4">
                                Sesi Telah Berakhir.
                            </h2>
                            <p class="text-muted">
                                Silahkan Kembali ke Halaman Utama dan Masukkan Ulang Nomor Pendaftaran Anda Untuk Mengecek Kembali Status Pendaftaran Anda.
                            </p>
                        </div>    
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="{{ route('ppdb.informasi') }}" class="btn btn-outline-secondary">Kembali ke Halaman Utama</a>  
                        </div>                   
                    @endif
                    @isset($data)                                            
                        @php
                            $nameParts = preg_split('/\s+/u', trim((string) $data->nama), -1, PREG_SPLIT_NO_EMPTY) ?: [];
                            $maskedName = implode(' ', array_map(function ($namePart) {
                                $length = mb_strlen($namePart);

                                if ($length <= 1) {
                                    return $length === 1 ? mb_substr($namePart, 0, 1) . '*' : '*';
                                }

                                if ($length === 2) {
                                    return mb_substr($namePart, 0, 1) . '*';
                                }

                                return mb_substr($namePart, 0, 1)
                                    . str_repeat('*', $length - 2)
                                    . mb_substr($namePart, -1);
                            }, $nameParts));
                        @endphp
                        <table class="table table-borderless">
                            <tr>
                                <th width="200">Nama</th>
                                <td>{{ $maskedName }}</td>
                            </tr>
                            <tr>
                                <th>No Pendaftaran</th>
                                <td>{{ $data->no_pendaftaran }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                
                                    @if($data->status == 'pending')
                                        <span class="badge bg-warning fs-6">
                                            Menunggu Keputusan Admin
                                        </span>
                                    @elseif($data->status == 'verifikasi')
                                        <span class="badge bg-info fs-6">
                                            Sedang Diverifikasi
                                        </span>
                                    @elseif($data->status == 'diterima')
                                        <span class="badge bg-success fs-6">
                                            Diterima
                                        </span>
                                    @elseif($data->status == 'ditolak')
                                        <span class="badge bg-danger fs-6">
                                            Ditolak
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                        @if($data->status == 'pending')
                            <div class="mt-3 text-muted">
                                Pendaftaran Anda sedang menunggu keputusan admin.
                            </div>
                        @endif
                        <hr>
                        <h6 class="mb-3">
                            Proses Pendaftaran
                        </h6>
                        <div class="status-progress" role="list" aria-label="Tahapan status pendaftaran">
                            <div
                                class="status-progress-item"
                                data-status="{{ $data->status === 'pending' ? 'active' : 'completed' }}"
                                data-progress-stage="pengajuan"
                                role="listitem"
                            >
                                <span class="status-progress-indicator" data-progress-indicator aria-hidden="true">
                                    @if($data->status === 'pending')
                                        <i class="bi bi-circle-fill"></i>
                                    @else
                                        <i class="bi bi-check-circle-fill"></i>
                                    @endif
                                </span>
                                <span class="status-progress-label">Pendaftaran</span>
                            </div>

                            <span
                                class="status-progress-connector"
                                data-progress-connector
                                data-status="{{ $data->status === 'pending' ? 'pending' : 'completed' }}"
                                aria-hidden="true"
                            ></span>

                            <div
                                class="status-progress-item"
                                data-progress-stage="keputusan"
                                data-status="{{ $data->status === 'pending' ? 'pending' : ($data->status === 'ditolak' ? 'rejected' : 'completed') }}"
                                role="listitem"
                            >
                                <span class="status-progress-indicator" data-progress-indicator aria-hidden="true">
                                    @if($data->status === 'ditolak')
                                        <i class="bi bi-x-circle-fill"></i>
                                    @elseif($data->status === 'pending')
                                        <i class="bi bi-circle"></i>
                                    @else
                                        <i class="bi bi-check-circle-fill"></i>
                                    @endif
                                </span>
                                <span class="status-progress-label">Keputusan Sekolah</span>
                            </div>

                            <span
                                class="status-progress-connector"
                                data-progress-connector
                                data-status="{{ $data->status === 'diterima' ? 'completed' : 'pending' }}"
                                aria-hidden="true"
                            ></span>

                            <div
                                class="status-progress-item"
                                data-progress-stage="daftar-ulang"
                                data-status="{{ $data->status === 'diterima' && $data->status_daftar_ulang === 'belum' ? 'active' : ($data->status === 'diterima' && $data->status_daftar_ulang === 'sudah' ? 'completed' : 'pending') }}"
                                aria-label="Daftar Ulang"
                                role="listitem"
                            >
                                <span class="status-progress-indicator" data-progress-indicator aria-hidden="true">
                                    @if($data->status === 'diterima' && $data->status_daftar_ulang === 'belum')
                                        <i class="bi bi-circle-fill"></i>
                                    @elseif($data->status === 'diterima' && $data->status_daftar_ulang === 'sudah')
                                        <i class="bi bi-check-circle-fill"></i>
                                    @else
                                        <i class="bi bi-circle"></i>
                                    @endif
                                </span>
                                <span class="status-progress-label">Daftar Ulang</span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="{{ route('ppdb.informasi') }}" class="btn btn-outline-secondary">Kembali ke Halaman Utama</a>  
                        </div>
                    @endisset
                </div>
            </div>
        </div>
    </div>
</div>
@endsection