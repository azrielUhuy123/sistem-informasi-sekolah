@extends('layouts.public')

@section('title','Daftar Ulang PPDB')

@section('content')

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-7">

            <div class="card shadow-lg border-0">

                <div class="card-body p-5">

                    <div class="text-center mb-4">

                        <h2 class="fw-bold text-primary">

                            Daftar Ulang PPDB

                        </h2>

                        <p class="text-muted">

                            Khusus calon siswa yang telah
                            dinyatakan diterima.

                        </p>

                    </div>

                    @if(session('success'))

                        <div class="alert alert-success">

                            {{ session('success') }}

                        </div>

                    @endif

                    @if(session('error'))

                        <div class="alert alert-danger">

                            {{ session('error') }}

                        </div>

                    @endif

                    <div class="alert alert-info">

                        Masukkan nomor pendaftaran yang
                        diperoleh saat mendaftar PPDB.

                    </div>

                    <form
                        method="POST"
                        action="{{ route('ppdb.daftar-ulang.simpan') }}"
                    >

                        @csrf

                        <div class="mb-4">

                            <label class="form-label">

                                Nomor Pendaftaran

                            </label>

                            <input
                                type="text"
                                name="no_pendaftaran"
                                class="form-control form-control-lg"
                                placeholder="PPDB-2026-00001"
                                required
                            >

                        </div>

                        <button
                            type="submit"
                            class="btn btn-primary btn-lg w-100"
                        >

                            Konfirmasi Daftar Ulang

                        </button>

                    </form>

                    <hr class="my-4">

                    <h6 class="fw-bold">

                        Syarat Daftar Ulang

                    </h6>

                    <ul class="mb-0">

                        <li>
                            Telah dinyatakan diterima.
                        </li>

                        <li>
                            Membawa dokumen asli saat diminta sekolah.
                        </li>

                        <li>
                            Melakukan daftar ulang sesuai jadwal.
                        </li>

                    </ul>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection