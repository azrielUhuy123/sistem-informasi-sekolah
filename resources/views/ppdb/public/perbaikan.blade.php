@extends('layouts.public')

@section('title', 'Perbaikan Berkas PPDB')

@section('content')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-soft">
                <div class="card-body p-5">

                    <h3 class="fw-bold mb-4">
                        Perbaikan Berkas Pendaftaran
                    </h3>

                    <p>
                        Yth. <strong>{{ $pendaftar->nama }}</strong>,
                    </p>

                    <p>
                        Silakan lakukan perbaikan berkas sesuai dengan catatan petugas PPDB.
                    </p>

                    <div class="alert alert-warning mt-4">
                        <strong>Catatan Petugas:</strong>
                        <div class="mt-2">
                            {{ $pendaftar->catatan_verifikasi }}
                        </div>
                    </div>

                    <div class="mt-4 mb-4">
                        <strong>Nomor Pendaftaran:</strong>
                        {{ $pendaftar->no_pendaftaran }}
                    </div>

                    <div class="alert alert-info">
                        Setelah berkas dikirim, pendaftaran akan kembali ke tahap menunggu verifikasi.
                    </div>

                    <p class="mt-4 mb-4">
                        Silakan upload kembali seluruh berkas yang diperlukan.
                        Berkas sebelumnya tidak perlu ditampilkan.
                    </p>

                    <form method="POST"
                          action="{{ request()->fullUrl() }}"
                          enctype="multipart/form-data"
                    >
                        @csrf

                        <div class="mb-4">
                            <label for="kartu_keluarga" class="form-label fw-semibold">Kartu Keluarga</label>
                            <input
                                id="kartu_keluarga"
                                type="file"
                                name="kartu_keluarga"
                                class="form-control"
                                accept=".pdf,.jpg,.jpeg"
                                required
                            >
                            <div class="form-text">
                                Format PDF, JPG atau JPEG. Ukuran maksimal 2 MB.
                            </div>
                            @error('kartu_keluarga')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="akta_lahir" class="form-label fw-semibold">Akta Kelahiran</label>
                            <input
                                id="akta_lahir"
                                type="file"
                                name="akta_lahir"
                                class="form-control"
                                accept=".pdf,.jpg,.jpeg"
                                required
                            >
                            <div class="form-text">
                                Format PDF, JPG atau JPEG. Ukuran maksimal 2 MB.
                            </div>
                            @error('akta_lahir')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="foto" class="form-label fw-semibold">Pas Foto</label>
                            <input
                                id="foto"
                                type="file"
                                name="foto"
                                class="form-control"
                                accept=".jpg,.jpeg,.png"
                                required
                            >
                            <div class="form-text">
                                Format JPG, JPEG atau PNG. Ukuran maksimal 2 MB.
                            </div>
                            @error('foto')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                            Ajukan Perbaikan Berkas
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
