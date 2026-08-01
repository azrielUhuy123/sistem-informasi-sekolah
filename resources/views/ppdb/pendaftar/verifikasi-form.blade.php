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
                                    <h4 class="mb-1 fw-bold text-uppercase">{{ $pendaftar->nama }}</h4>

                                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                                        <span class="badge bg-light-subtle text-primary-emphasis border">
                                            NIK : {{ $pendaftar->nik }}
                                        </span>
                                        <span class="badge bg-light-subtle text-primary-emphasis border">
                                            NO. KK : {{ $pendaftar->nomor_kk ?? '-' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="w-50">
                                    <div class="row g-2">
                                        <div class="col-12">
                                            <div class="border rounded-3 p-2 bg-white">
                                                <div class="small text-muted">Status Pendaftar</div>
                                                <div class="fw-semibold">
                                                    <span class="badge bg-success">
                                                        {{ strtoupper($pendaftar->status) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="text-end mt-4">
                            <button class="btn btn-primary rounded-pill px-3" onclick="window.location.href='{{ route('tu.ppdb.pendaftar.show', $pendaftar) }}'">
                                <i class="bi bi-person-square me-2"></i>
                                Lihat Profil Pendaftar
                            </button>
                        </div>
                    </div>

                    <div class="col-lg-8">

                        <div class="card border-0 h-100">

                            <div class="card-body">

                                {{-- Kartu Keluarga --}}
                                <div class="d-flex justify-content-between align-items-center border-bottom py-2">

                                    <span class="fw-semibold">
                                        Kartu Keluarga
                                    </span>

                                    @if($pendaftar->kartu_keluarga)

                                        <a
                                            href="{{ asset('storage/'.$pendaftar->kartu_keluarga) }}"
                                            target="_blank"
                                            class="btn btn-light btn-sm border border-primary-subtle rounded-3 p-0 overflow-hidden d-inline-flex align-items-center"
                                        >
                                            <span class="bg-primary text-white px-2 py-1">
                                                <i class="bi bi-cloud-arrow-down"></i>
                                            </span>

                                            <span class="px-3 fw-semibold text-primary-emphasis">
                                                Lihat Berkas
                                            </span>
                                        </a>

                                    @else

                                        <span class="badge bg-danger">
                                            Belum Upload
                                        </span>

                                    @endif

                                </div>

                                {{-- Akta --}}
                                <div class="d-flex justify-content-between align-items-center border-bottom py-2">

                                    <span class="fw-semibold">
                                        Akta Kelahiran
                                    </span>

                                    @if($pendaftar->akta_lahir)

                                        <a
                                            href="{{ asset('storage/'.$pendaftar->akta_lahir) }}"
                                            target="_blank"
                                            class="btn btn-light btn-sm border border-primary-subtle rounded-3 p-0 overflow-hidden d-inline-flex align-items-center"
                                        >
                                            <span class="bg-primary text-white px-2 py-1">
                                                <i class="bi bi-cloud-arrow-down"></i>
                                            </span>

                                            <span class="px-3 fw-semibold text-primary-emphasis">
                                                Lihat Berkas
                                            </span>
                                        </a>

                                    @else

                                        <span class="badge bg-danger">
                                            Belum Upload
                                        </span>

                                    @endif

                                </div>

                                {{-- Pas Foto --}}
                                <div class="d-flex justify-content-between align-items-center py-2">

                                    <span class="fw-semibold">
                                        Pas Foto
                                    </span>

                                    @if($pendaftar->foto)

                                        <a
                                            href="{{ asset('storage/'.$pendaftar->foto) }}"
                                            target="_blank"
                                            class="btn btn-light btn-sm border border-primary-subtle rounded-3 p-0 overflow-hidden d-inline-flex align-items-center"
                                        >
                                            <span class="bg-primary text-white px-2 py-1">
                                                <i class="bi bi-cloud-arrow-down"></i>
                                            </span>

                                            <span class="px-3 fw-semibold text-primary-emphasis">
                                                Lihat Berkas
                                            </span>
                                        </a>

                                    @else

                                        <span class="badge bg-danger">
                                            Belum Upload
                                        </span>

                                    @endif

                                </div>

                                @if($pendaftar->status == 'pending')

                                <hr class="my-3">                                

                                    <form
                                        method="POST"
                                        action="{{ route('tu.ppdb.pendaftar.simpan-verifikasi',$pendaftar) }}"
                                    >

                                        @csrf

                                        <div class="mb-3">

                                            <label class="form-label fw-semibold">
                                                Catatan Verifikasi
                                            </label>

                                            <textarea
                                                name="catatan_verifikasi"
                                                class="form-control rounded-3"
                                                rows="2"
                                                placeholder="Masukkan catatan verifikasi..."
                                            ></textarea>

                                        </div>

                                        <div class="text-end">

                                            <button class="btn btn-success rounded-pill btn-confirm px-4" type="submit"
                                            data-title="VERIFIKASI BERKAS SISWA INI?"
                                            data-message="Berkas siswa ini akan terverifikasi dan tidak dapat diubah kembali"
                                            data-button="btn-primary">

                                                <i class="bi bi-check-circle me-2"></i>

                                                Verifikasi Berkas

                                            </button>

                                        </div>

                                    </form>
                                @endif
                            </div>

                        </div>

                    </div>

                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('tu.ppdb.verifikasi.index') }}" class="btn btn-outline-secondary ml-4">
                                <i class="bi bi-arrow-left me-1"></i> Kembali
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
