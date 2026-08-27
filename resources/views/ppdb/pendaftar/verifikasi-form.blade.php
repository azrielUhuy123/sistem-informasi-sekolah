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
                                <h5 class="fw-bold mb-3">Berkas Pendaftar</h5>

                                {{-- Kartu Keluarga --}}
                                <div class="d-flex justify-content-between align-items-center border-bottom py-2">

                                    <span class="fw-semibold">
                                        Kartu Keluarga
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
                                            class="btn btn-primary btn-sm rounded-3"
                                        >
                                            <i class="bi bi-file-earmark-text-fill me-2"></i>
                                            Lihat <span class="desc-add">Berkas</span>                                              
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
                                            class="btn btn-primary btn-sm rounded-3"
                                        >
                                            <i class="bi bi-file-earmark-text-fill me-2"></i>
                                            Lihat <span class="desc-add">Berkas</span>                             
                                        </a>

                                    @else

                                        <span class="badge bg-danger">
                                            Belum Upload
                                        </span>

                                    @endif

                                </div>

                                @if($pendaftar->status == 'verifikasi')

                                    <hr class="my-4">

                                    <div class="d-flex justify-content-end gap-2">

                                        {{-- FORM TOLAK BERKAS --}}
                                        <form
                                            method="POST"
                                            action="{{ route('tu.ppdb.pendaftar.tolak-berkas', $pendaftar) }}"
                                            id="tolak-berkas-form"
                                            class="m-0"
                                        >
                                            @csrf

                                            <button
                                                type="button"
                                                class="btn btn-outline-danger rounded-3"
                                                data-bs-toggle="modal"
                                                data-bs-target="#tolakBerkasModal"
                                            >
                                                <i class="bi bi-x-circle me-1"></i>
                                                Tolak <span class="desc-add">Berkas</span>
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
<div
    class="modal fade"
    id="tolakBerkasModal"
    tabindex="-1"
    aria-labelledby="tolakBerkasModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">

            <form
                method="POST"
                action="{{ route('tu.ppdb.pendaftar.tolak-berkas', $pendaftar) }}"
                id="tolak-berkas-modal-form"
                novalidate
            >
                @csrf

                <div class="modal-header border-0 px-4 pt-4">                    
                    <div class="mb-0 w-100">
                        <h5
                            class="modal-title fw-bold text-danger text-center"
                            id="tolakBerkasModalLabel"
                        >
                            Kembalikan Berkas
                        </h5>
                        <p class="text-muted text-center mb-0">
                            Berikan catatan perbaikan yang harus dilakukan
                            oleh pendaftar.
                        </p>
                    </div>                    
                </div>

                <div class="modal-body px-4 py-0">

                    <label
                        for="catatan_verifikasi"
                        class="form-label fw-semibold"
                    >
                        Catatan Verifikasi
                        <span class="text-danger">*</span>
                    </label>

                    <textarea
                        name="catatan_verifikasi"
                        id="catatan_verifikasi"
                        class="form-control"
                        rows="4"
                        placeholder="Contoh: Kartu Keluarga kurang jelas, silakan upload ulang..."
                        required
                    ></textarea>

                    <div
                        id="catatan-verifikasi-error"
                        class="invalid-feedback"
                    >
                        Catatan verifikasi wajib diisi.
                    </div>

                </div>

                <div class="modal-footer border-0 px-4 pb-4">

                    <button
                        type="button"
                        class="btn btn-light rounded-3 px-3"
                        data-bs-dismiss="modal"
                    >
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="btn btn-danger rounded-3 px-3 btn-confirm"
                        data-title="KEMBALIKAN BERKAS UNTUK PERBAIKAN?"
                        data-message="Berkas akan dikembalikan kepada pendaftar untuk diperbaiki."
                        data-button="btn-danger"
                    >
                        <i class="bi bi-send me-1"></i>
                        Kirim Perbaikan
                    </button>

                </div>

            </form>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('tolak-berkas-modal-form');
    const textarea = document.getElementById('catatan_verifikasi');
    const confirmButton = form?.querySelector('.btn-confirm');

    if (!form || !textarea || !confirmButton) {
        return;
    }

    /*
     * Validasi dilakukan SEBELUM .btn-confirm global diproses.
     */
    confirmButton.addEventListener('click', function (event) {

        if (!textarea.value.trim()) {

            event.preventDefault();
            event.stopImmediatePropagation();

            textarea.classList.add('is-invalid');
            textarea.focus();

            return;
        }

        textarea.classList.remove('is-invalid');

    }, true);


    /*
     * Hilangkan error ketika user mulai mengetik.
     */
    textarea.addEventListener('input', function () {

        if (this.value.trim()) {
            this.classList.remove('is-invalid');
        }

    });

});
</script>          
@endsection
