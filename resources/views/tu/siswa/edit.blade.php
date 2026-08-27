@extends('layouts.app')

@section('title', 'Edit Siswa')

@section('content')

<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="mb-4">
                <h4 class="text-primary fw-bold text-center mb-1">Edit Siswa</h4>
                <p class="text-muted text-center">
                    Perbarui Data Siswa
                </p>
            </div>
            {{-- ALERT --}}            

            <x-alert.flash key="error" type="danger"/>

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
                            class="form-control"
                            value="{{ old('nama', $siswa->nama) }}"                            
                            oninput="this.value = this.value.replace(/\s+/g, ' ').replace(/(^|\s)\S/g, c => c.toUpperCase())"
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
                            class="form-control"
                            value="{{ old('nis', $siswa->nis) }}"
                            inputmode="numeric"                            
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
                            class="form-control"
                            value="{{ old('nisn', $siswa->nisn) }}"
                            inputmode="numeric"                            
                        >
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Jenis Kelamin
                        </label>

                        <select
                            name="jenis_kelamin"
                            class="form-control"
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
                            class="form-control"
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
                            class="form-control"
                        >{{ old('alamat', $siswa->alamat) }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Nama Wali
                        </label>

                        <input
                            type="text"
                            name="nama_wali"
                            class="form-control"
                            value="{{ old('nama_wali', $siswa->nama_wali) }}"
                            oninput="this.value = this.value.replace(/\s+/g, ' ').replace(/(^|\s)\S/g, c => c.toUpperCase())"
                        >
                    </div>

                    <div class="col-md-6 mb-5">
                        <label class="form-label">
                            Telepon Wali
                        </label>

                        <input
                            type="text"
                            name="telepon_wali"
                            class="form-control"
                            value="{{ old('telepon_wali', $siswa->telepon_wali) }}"
                            inputmode="tel"
                            oninput="this.value = this.value.replace(/\D/g, '')"
                        >
                    </div>

                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">

                    <a
                        href="{{ url()->previous() }}"
                        class="btn btn-outline-secondary rounded-3"
                    >
                        <i class="bi bi-chevron-left me-1"></i> Kembali                        
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary rounded-3"
                    >
                        Simpan <i class="bi bi-save ms-1"></i>
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection