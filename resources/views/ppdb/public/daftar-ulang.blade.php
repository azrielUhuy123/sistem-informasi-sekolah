@extends('layouts.ppdb-form')

@section('title','Daftar Ulang PPDB')

@section('content')

<style>
    .card.shadow {
        border: none;
        border-radius: 24px;
        overflow: hidden;
    }
    .card-header {
        border-bottom: none;            
    }        
    .card-body {
        padding: 1.5rem 1.25rem;
    }
    
    .tittle-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px;
    }
    
    .tittle-logo {
        width: 60px;            
    }

    .tittle-school {
        color: #0881c7;
        font-size: 14px;
        line-height: 1.2;
    }

    .tittle-school span {
        font-size: 26px;
        font-weight: 600;
        margin-bottom: 6px;
    }       

    @media (max-width: 768px) {                                
        .tittle-logo {
            margin-bottom: 18px;
        }
        .tittle-school {
            font-size: 13px;
        }
        .tittle-school h2 {
            font-size: 24px;
        }
            
    }

    @media (max-width: 575.98px) {
        .card-body {
            padding: 1.25rem 0.75rem;
        }
    }
</style>

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-7">

            <div class="card shadow">
                <div class="card-header">                
                    <div class="tittle-header">
                        <img src="{{ asset('assets/images/logo-kolaan.png') }}" class="tittle-logo">
                        <div class="tittle-school">
                            <span>Daftar Ulang</span><br>
                            Sekolah Dasar Negeri<br>Mekar Jaya
                        </div>
                    </div>
                </div>   
                <div class="card-body">
                    <div class="text-center mb-4">
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

                        Masukkan nomor pendaftaran dan tanggal lahir anak sesuai data pendaftaran PPDB.

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
                                value="{{ old('no_pendaftaran') }}"
                                required
                            >

                        </div>

                        <div class="mb-4">

                            <label for="tanggal_lahir" class="form-label">
                                Tanggal Lahir Anak
                            </label>

                            <input
                                type="date"
                                id="tanggal_lahir"
                                name="tanggal_lahir"
                                class="form-control form-control-lg"
                                value="{{ old('tanggal_lahir') }}"
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

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="{{ route('ppdb.informasi') }}" class="btn btn-outline-secondary">Kembali ke Halaman Utama</a>  
                    </div>

                </div>
                

            </div>

        </div>

    </div>

</div>

@endsection