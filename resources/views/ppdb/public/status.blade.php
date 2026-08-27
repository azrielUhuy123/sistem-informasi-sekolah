@extends('layouts.public')

@section('title','Status PPDB')

@section('content')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-soft">
                <div class="card-body p-5">
                    @if(!isset($viaLink) || !$viaLink)
                        <div class="text-center mb-4">
                            <h2 class="fw-bold">
                                Cek Status PPDB
                            </h2>
                            <p class="text-muted">
                                Masukkan Nomor Pendaftaran untuk melihat status pendaftaran.
                            </p>
                        </div>
                        <form
                            method="POST"
                            action="{{ route('ppdb.status.cek') }}"
                        >
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">
                                    Nomor Pendaftaran
                                </label>
                                <input
                                    type="text"
                                    name="keyword"
                                    class="form-control form-control-lg"
                                    placeholder="Contoh: PPDB-2026-00001"
                                    required
                                >
                            </div>
                            <button
                                type="submit"
                                class="btn btn-primary btn-lg w-100"
                            >
                                Cek Status
                            </button>
                        </form>
                    @endif
                    @isset($data)
                    <hr class="my-4">
                        <h4 class="fw-bold mb-4 mt-4">
                            Status Pendaftaran
                        </h4>
                        <table class="table table-borderless">
                            <tr>
                                <th width="200">Nama</th>
                                <td>{{ $data->nama }}</td>
                            </tr>
                            <tr>
                                <th>No Pendaftaran</th>
                                <td>{{ $data->no_pendaftaran }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    @if($data->status == 'pending' && empty($data->catatan_verifikasi))
                                        <span class="badge bg-warning fs-6">
                                            Menunggu Verifikasi
                                        </span>
                                    @elseif($data->status == 'pending' && !empty($data->catatan_verifikasi))
                                        <span class="badge bg-danger fs-6">
                                            Berkas Perlu Diperbaiki
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
                        @if($data->status == 'pending' && empty($data->catatan_verifikasi))
                            <div class="mt-3 text-muted">
                                Pendaftaran Anda sedang menunggu pemeriksaan berkas oleh petugas PPDB.
                            </div>
                        @elseif($data->status == 'pending' && !empty($data->catatan_verifikasi))
                            <div class="alert alert-warning mt-3">
                                <p class="mb-2 fw-semibold">Catatan Petugas:</p>
                                <p class="mb-2">{{ $data->catatan_verifikasi }}</p>
                                <p class="mb-0">Terdapat berkas yang perlu diperbaiki. Silakan perbaiki berkas sesuai catatan di atas. silahkan cek kembali email anda untuk informasi lebih lanjut.</p>                        
                            </div>
                        @endif
                        <hr>
                        <h6 class="fw-bold mb-3">
                            Progress Pendaftaran
                        </h6>
                        <div class="progress" style="height:10px;">
                            @if($data->status == 'pending')
                                <div
                                    class="progress-bar bg-warning"
                                    style="width:25%"
                                ></div>
                            @elseif($data->status == 'verifikasi')
                                <div
                                    class="progress-bar bg-info"
                                    style="width:60%"
                                ></div>
                            @elseif($data->status == 'diterima')
                                <div
                                    class="progress-bar bg-success"
                                    style="width:100%"
                                ></div>
                            @elseif($data->status == 'ditolak')
                                <div
                                    class="progress-bar bg-danger"
                                    style="width:100%"
                                ></div>
                            @endif
                        </div>
                    @endisset
                </div>
            </div>
        </div>
    </div>
</div>
@endsection