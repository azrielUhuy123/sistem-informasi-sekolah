@extends('layouts.app')

@section('title', 'Edit Pengajuan Surat')

@section('content')

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Edit Pengajuan Surat</h4>
            <p class="text-muted mb-0">Upload draft surat untuk divalidasi kepala sekolah</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
           <form
                action="{{ route('tu.pengajuan-surat.update', $pengajuan->id) }}"
                method="POST"
                enctype="multipart/form-data"
            >
                
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div>
                        <label class="form-label">Kategori Surat</label>
                        <input type="text"
                           name="kategori_surat"
                           class="form-control rounded-3" value="{{ $pengajuan->kategori_surat }}"
                           required>
                    </div>
                    <div>
                        <label class="form-label">Nama Surat</label>
                        <input type="text"
                           name="nama_surat"
                           class="form-control rounded-3" value="{{ $pengajuan->nama_surat }}"
                           required>
                    </div>
                    <div>
                        <label class="form-label">Perihal</label>
                        <input
                            type="text"
                            name="perihal"
                            class="form-control rounded-3" value="{{ $pengajuan->perihal }}"
                            placeholder="Masukkan perihal surat"
                            required
                        >
                    </div>
                    <div>
                        <label class="form-label">Tujuan Surat</label>
                        <input type="text"
                           name="tujuan_surat"
                           class="form-control rounded-3" value="{{ $pengajuan->tujuan_surat }}"
                           required>
                    </div>
                    <div>
                        <label class="form-label">Upload Draft Surat</label>
                        <input type="file"
                           name="file_surat"
                           class="form-control rounded-3" value="{{ $pengajuan->file_surat }}" 
                           required>
                           <small class="text-muted">
                                Format file: PDF, DOC, DOCX
                            </small>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="{{ route('tu.pengajuan-surat.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save me-1"></i> Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection