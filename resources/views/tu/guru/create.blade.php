@extends('layouts.app')

@section('title', 'Tambah Guru')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="fw-bold mb-0">
                Tambah Guru
            </h4>

            <small class="text-muted">
                Form input data guru
            </small>
        </div>

    </div>

    {{-- CARD --}}
    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <form
                action="{{ route('tu.guru.store') }}"
                method="POST"
                enctype="multipart/form-data"
            >

                @csrf

                <div class="row g-4">

                    {{-- NAMA --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Nama Guru
                        </label>

                        <input
                            type="text"
                            name="nama"
                            class="form-control"
                            required
                        >

                    </div>

                    {{-- NIP --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            NIP
                        </label>

                        <input
                            type="text"
                            name="nip"
                            class="form-control"
                        >

                    </div>

                    {{-- JENIS KELAMIN --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Jenis Kelamin
                        </label>

                        <select
                            name="jenis_kelamin"
                            class="form-select"
                            required
                        >

                            <option value="">
                                -- Pilih --
                            </option>

                            <option value="L">
                                Laki-laki
                            </option>

                            <option value="P">
                                Perempuan
                            </option>

                        </select>

                    </div>

                    {{-- EMAIL --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Email Login
                        </label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            required
                        >

                    </div>

                    {{-- PASSWORD --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Password Default
                        </label>

                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            required
                        >

                        <small class="text-muted">
                            Guru dapat mengganti password setelah login
                        </small>

                    </div>

                    {{-- TELEPON --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Telepon
                        </label>

                        <input
                            type="text"
                            name="telepon"
                            class="form-control"
                        >

                    </div>

                    {{-- ALAMAT --}}
                    <div class="col-12">

                        <label class="form-label fw-semibold">
                            Alamat
                        </label>

                        <textarea
                            name="alamat"
                            rows="3"
                            class="form-control"
                        ></textarea>

                    </div>

                    {{-- FOTO --}}
                    <div class="col-12">

                        <label class="form-label fw-semibold">
                            Foto Guru
                        </label>

                        <input
                            type="file"
                            name="foto"
                            class="form-control"
                        >

                    </div>

                </div>

                {{-- BUTTON --}}
                <div class="d-flex gap-2 mt-4">

                    <button class="btn btn-primary">

                        Simpan Guru

                    </button>

                    <a
                        href="{{ route('tu.guru.index') }}"
                        class="btn btn-light border"
                    >

                        Kembali

                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection