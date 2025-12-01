@extends('layouts.tu')

@section('title', 'Tambah Dokumen')

@section('content')

<h1 class="mb-4">Tambah Dokumen</h1>

@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Terjadi kesalahan:</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-body">

        <form action="{{ route('tu.arsip-dokumen.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Nama Dokumen --}}
            <div class="mb-3">
                <label>Nama Dokumen / Nama Surat</label>
                <input type="text" name="nama_surat" class="form-control" required>
            </div>

            {{-- Nomor Surat --}}
            <div class="mb-3">
                <label>Nomor Surat (opsional)</label>
                <input type="text" name="nomor_surat" class="form-control">
            </div>

            {{-- Tanggal --}}
            <div class="mb-3">
                <label>Tanggal Dokumen</label>
                <input type="date" name="tanggal_dibuat" class="form-control">
            </div>

            {{-- Kategori --}}
            <div class="mb-3">
                <label>Kategori Dokumen</label>
                <select name="kategori" class="form-control">
                    <option value="">-- Pilih Kategori --</option>
                    <option>Surat Masuk</option>
                    <option>Surat Keluar</option>
                    <option>Dokumen Sekolah</option>
                    <option>SK</option>
                    <option>Lainnya</option>
                </select>
            </div>

            {{-- File Upload --}}
            <div class="mb-3">
                <label>Upload File Dokumen</label>
                <input type="file" name="file_dokumen" class="form-control" required>
                <small class="text-muted">
                    Format diperbolehkan: PDF, DOC, DOCX (maksimal 50MB)
                </small>
            </div>

            {{-- Keterangan --}}
            <div class="mb-3">
                <label>Keterangan (opsional)</label>
                <textarea name="keterangan" class="form-control" rows="3"></textarea>
            </div>

            <button class="btn btn-primary">Simpan</button>
        </form>

    </div>
</div>

@endsection
