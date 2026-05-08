@extends('layouts.app')

@section('title', 'Tambah Siswa')

@section('content')

<h1 class="mb-4">Tambah Siswa</h1>

<div class="card">
    <div class="card-body">

        {{-- ALERT VALIDASI --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('tu.siswa.store') }}" method="POST">
            @csrf

            {{-- =========================
                 ANGKATAN (AUTO LOCK)
            ========================= --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Angkatan</label>
                <input
                    type="text"
                    class="form-control"
                    value="{{ $angkatan }}"
                    readonly
                >
                <input type="hidden" name="angkatan" value="{{ $angkatan }}">
            </div>

            {{-- =========================
                 IDENTITAS SISWA
            ========================= --}}
            <div class="mb-3">
                <label class="form-label">Nama Siswa <span class="text-danger">*</span></label>
                <input
                    type="text"
                    name="nama"
                    class="form-control"
                    value="{{ old('nama') }}"
                    required
                >
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">NIS <span class="text-danger">*</span></label>
                    <input
                        type="text"
                        name="nis"
                        class="form-control"
                        value="{{ old('nis') }}"
                        required
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">NISN</label>
                    <input
                        type="text"
                        name="nisn"
                        class="form-control"
                        value="{{ old('nisn') }}"
                    >
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                <select name="jenis_kelamin" class="form-select" required>
                    <option value="">-- Pilih --</option>
                    <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>
                        Laki-laki
                    </option>
                    <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>
                        Perempuan
                    </option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Tanggal Lahir</label>
                <input
                    type="date"
                    name="tanggal_lahir"
                    class="form-control"
                    value="{{ old('tanggal_lahir') }}"
                >
            </div>

            {{-- =========================
                 DATA ALAMAT & WALI
            ========================= --}}
            <div class="mb-3">
                <label class="form-label">Alamat</label>
                <textarea
                    name="alamat"
                    class="form-control"
                    rows="2"
                >{{ old('alamat') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Nama Wali</label>
                <input
                    type="text"
                    name="nama_wali"
                    class="form-control"
                    value="{{ old('nama_wali') }}"
                >
            </div>

            <div class="mb-4">
                <label class="form-label">Telepon Wali</label>
                <input
                    type="text"
                    name="telepon_wali"
                    class="form-control"
                    value="{{ old('telepon_wali') }}"
                >
            </div>

            {{-- =========================
                 AKSI
            ========================= --}}
            <div class="d-flex gap-2">
                <button class="btn btn-primary">
                    Simpan
                </button>

                <a
                    href="{{ route('tu.siswa.index', ['angkatan' => $angkatan]) }}"
                    class="btn btn-secondary"
                >
                    Batal
                </a>
            </div>

        </form>

    </div>
</div>

@endsection
