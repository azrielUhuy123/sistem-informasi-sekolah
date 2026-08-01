@extends('layouts.app')

@section('title', 'Edit Siswa')

@section('content')

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Edit Siswa</h4>
            <p class="text-muted mb-0">
                Perbarui Data Siswa
            </p>
        </div>
    </div>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <form
                action="{{ route('tu.siswa.update', $siswa->id) }}"
                method="POST"
            >

                @csrf
                @method('PUT')

                <input
                    type="hidden"
                    name="redirect_to"
                    value="{{ url()->previous() }}"
                >

                <div class="row g-4">

                    <div class="col-md-6">
                        <label class="form-label">
                            Nama Siswa
                        </label>

                        <input
                            type="text"
                            name="nama"
                            class="form-control rounded-3"
                            value="{{ old('nama', $siswa->nama) }}"
                            required
                        >
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">
                            NIS
                        </label>

                        <input
                            type="text"
                            name="nis"
                            class="form-control rounded-3"
                            value="{{ old('nis', $siswa->nis) }}"
                            required
                        >
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">
                            NISN
                        </label>

                        <input
                            type="text"
                            name="nisn"
                            class="form-control rounded-3"
                            value="{{ old('nisn', $siswa->nisn) }}"
                        >
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Jenis Kelamin
                        </label>

                        <select
                            name="jenis_kelamin"
                            class="form-select rounded-3"
                            required
                        >
                            <option value="L" @selected(old('jenis_kelamin', $siswa->jenis_kelamin) == 'L')>
                                Laki-laki
                            </option>

                            <option value="P" @selected(old('jenis_kelamin', $siswa->jenis_kelamin) == 'P')>
                                Perempuan
                            </option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Tanggal Lahir
                        </label>

                        <input
                            type="date"
                            name="tanggal_lahir"
                            class="form-control rounded-3"
                            value="{{ old('tanggal_lahir', $siswa->tanggal_lahir) }}"
                        >
                    </div>

                    <div class="col-12">
                        <label class="form-label">
                            Alamat
                        </label>

                        <textarea
                            name="alamat"
                            rows="3"
                            class="form-control rounded-3"
                        >{{ old('alamat', $siswa->alamat) }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Nama Wali
                        </label>

                        <input
                            type="text"
                            name="nama_wali"
                            class="form-control rounded-3"
                            value="{{ old('nama_wali', $siswa->nama_wali) }}"
                        >
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Telepon Wali
                        </label>

                        <input
                            type="text"
                            name="telepon_wali"
                            class="form-control rounded-3"
                            value="{{ old('telepon_wali', $siswa->telepon_wali) }}"
                        >
                    </div>

                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">

                    <a
                        href="{{ url()->previous() }}"
                        class="btn btn-outline-secondary"
                    >
                        <i class="bi bi-arrow-left me-1"></i>
                        Kembali
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary px-4"
                    >
                        <i class="bi bi-save me-1"></i>
                        Simpan Perubahan
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection