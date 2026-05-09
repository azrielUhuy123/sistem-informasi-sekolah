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

        <a href="{{ route('tu.pengajuan-surat.index') }}"
           class="btn btn-secondary rounded-3">

            Kembali

        </a>

    </div>

    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-4">

            {{-- NAMA SURAT --}}
            <div class="mb-4">

                <label class="fw-semibold text-muted">
                    Nama Surat
                </label>

                <h5>
                    {{ $pengajuan->nama_surat }}
                </h5>

            </div>

            {{-- KATEGORI --}}
            <div class="mb-4">

                <label class="fw-semibold text-muted">
                    Kategori Surat
                </label>

                <h5>
                    {{ $pengajuan->kategori_surat }}
                </h5>

            </div>

            {{-- TUJUAN --}}
            <div class="mb-4">

                <label class="fw-semibold text-muted">
                    Tujuan Surat
                </label>

                <h5>
                    {{ $pengajuan->tujuan_surat }}
                </h5>

            </div>

            {{-- STATUS --}}
            <div class="mb-4">

                <label class="fw-semibold text-muted">
                    Status
                </label>

                <br>

                @if($pengajuan->status == 'pending')

                    <span class="badge bg-warning text-dark px-3 py-2">
                        Pending
                    </span>

                @elseif($pengajuan->status == 'disetujui')

                    <span class="badge bg-success px-3 py-2">
                        Disetujui
                    </span>

                @else

                    <span class="badge bg-danger px-3 py-2">
                        Ditolak
                    </span>

                @endif

            </div>

            {{-- FILE --}}
            <div class="mb-3">

                <label class="fw-semibold text-muted">
                    File Surat
                </label>

                <br><br>

                <a href="{{ asset('uploads/pengajuan-surat/' . $pengajuan->file_surat) }}"
                   target="_blank"
                   class="btn btn-primary rounded-3">

                    <i class="bi bi-eye"></i>
                    Lihat Surat

                </a>

                @if($pengajuan->status == 'disetujui' && $pengajuan->file_final)

                        <a href="{{ url('uploads/pengajuan-surat/validated/' . $pengajuan->file_final) }}"
                        class="btn btn-success"
                        download>

                            <i class="bi bi-download"></i>
                            Download Surat Final

                        </a>

                    @else

                        <a href="{{ url('uploads/pengajuan-surat/' . $pengajuan->file_surat) }}"
                        class="btn btn-success"
                        download>

                            <i class="bi bi-download"></i>
                            Download Surat

                        </a>

                @endif

            </div>

        </div>

    </div>

</div>

@endsection