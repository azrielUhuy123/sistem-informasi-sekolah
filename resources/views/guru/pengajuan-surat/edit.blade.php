@extends('layouts.app')

@section('title', 'Edit Pengajuan Surat')

@section('content')

<div class="container-fluid">

    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-4">

            <h4 class="fw-bold mb-4">
                Edit Pengajuan Surat
            </h4>

            <form
                action="{{ route('guru.pengajuan-surat.update', $pengajuan->id) }}"
                method="POST"
                enctype="multipart/form-data"
            >

                @csrf
                @method('PUT')

                <div class="mb-3">

                    <label class="form-label">
                        Nama Surat
                    </label>

                    <input
                        type="text"
                        name="nama_surat"
                        class="form-control"
                        value="{{ $pengajuan->nama_surat }}"
                        required
                    >

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Kategori Surat
                    </label>

                    <input
                        type="text"
                        name="kategori_surat"
                        class="form-control"
                        value="{{ $pengajuan->kategori_surat }}"
                        required
                    >

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Perihal
                    </label>

                    <input
                        type="text"
                        name="perihal"
                        class="form-control"
                        value="{{ $pengajuan->perihal }}"
                        required
                    >

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Tujuan Surat
                    </label>

                    <input
                        type="text"
                        name="tujuan_surat"
                        class="form-control"
                        value="{{ $pengajuan->tujuan_surat }}"
                        required
                    >

                </div>

                <div class="mb-4">

                    <label class="form-label">
                        Upload File Baru
                    </label>

                    <input
                        type="file"
                        name="file_surat"
                        class="form-control"
                    >

                    <small class="text-muted">
                        Kosongkan jika tidak ingin mengganti file
                    </small>

                </div>

                <button class="btn btn-primary rounded-3">

                    Update Pengajuan

                </button>

            </form>

        </div>

    </div>

</div>

@endsection