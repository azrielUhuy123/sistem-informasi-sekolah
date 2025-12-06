@extends('layouts.app') {{-- sesuaikan jika layout berbeda --}}

@section('content')
<div class="container">

    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h3 class="fw-bold">Edit Dokumen</h3>
        <a href="{{ route('tu.arsip-dokumen.index') }}" class="btn btn-secondary">
            Kembali
        </a>
    </div>

    {{-- Error Validasi --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mt-2 mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="card shadow-sm">
        <div class="card-body">

            <form action="{{ route('tu.arsip-dokumen.update', $doc->id) }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf
                @method('PUT')

                {{-- Nama Dokumen --}}
                <div class="mb-3">
                    <label class="fw-bold">Nama Dokumen / Nama Surat</label>
                    <input type="text" name="nama_surat" class="form-control"
                           value="{{ $doc->nama_surat }}" required>
                </div>

                {{-- Nomor Surat --}}
                <div class="mb-3">
                    <label class="fw-bold">Nomor Surat (opsional)</label>
                    <input type="text" name="nomor_surat" class="form-control"
                           value="{{ $doc->nomor_surat }}">
                </div>

                {{-- Tanggal Dokumen --}}
                <div class="mb-3">
                    <label class="fw-bold">Tanggal Dokumen</label>
                    <input type="date" name="tanggal_dibuat" class="form-control"
                           value="{{ $doc->tanggal_dibuat }}">
                </div>

                {{-- Kategori --}}
                <div class="mb-3">
                    <label class="fw-bold">Kategori</label>
                    <select name="kategori" class="form-select">
                        <option value="">Pilih kategori (opsional)</option>
                        <option {{ $doc->kategori == 'Surat Masuk' ? 'selected' : '' }}>Surat Masuk</option>
                        <option {{ $doc->kategori == 'Surat Keluar' ? 'selected' : '' }}>Surat Keluar</option>
                        <option {{ $doc->kategori == 'Dokumen Sekolah' ? 'selected' : '' }}>Dokumen Sekolah</option>
                        <option {{ $doc->kategori == 'SK' ? 'selected' : '' }}>SK</option>
                        <option {{ $doc->kategori == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>

                {{-- File Lama --}}
                <div class="mb-3">
                    <label class="fw-bold d-block">File Saat Ini:</label>

                    @if (pathinfo($doc->file_path, PATHINFO_EXTENSION) === 'pdf')
                        <a href="{{ route('tu.arsip-dokumen.preview', $doc->id) }}" 
                           target="_blank" 
                           class="btn btn-sm btn-secondary mb-2">
                            Lihat PDF
                        </a>
                    @else
                        <a href="{{ route('tu.arsip-dokumen.download', $doc->id) }}"
                           class="btn btn-sm btn-secondary mb-2">
                            Download File
                        </a>
                    @endif

                    <p class="text-muted">{{ $doc->file_path }}</p>
                </div>

                {{-- Keterangan --}}
                <div class="mb-3">
                    <label class="fw-bold">Keterangan (opsional)</label>
                    <textarea name="keterangan" class="form-control" rows="3">{{ $doc->keterangan }}</textarea>
                </div>

                {{-- Tombol Update --}}
                <button type="submit" class="btn btn-primary w-100">
                    Update Dokumen
                </button>

            </form>

        </div>
    </div>

</div>
@endsection
