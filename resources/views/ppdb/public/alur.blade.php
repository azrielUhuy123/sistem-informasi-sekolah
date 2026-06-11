@extends('layouts.public')

@section('title','Alur PPDB')

@section('content')

<div class="container py-5">


<div class="text-center mb-5">

    <h1 class="fw-bold">
        Alur PPDB SDN Mekar Jaya
    </h1>

    <p class="text-muted">
        Ikuti tahapan berikut sampai resmi menjadi siswa baru.
    </p>

</div>

<div class="row justify-content-center">

    <div class="col-lg-8">

        <div class="card shadow-soft">

            <div class="card-body p-4">

                <div class="d-flex mb-4">

                    <div class="me-4">
                        <span class="badge bg-primary fs-6">1</span>
                    </div>

                    <div>
                        <h5>Isi Formulir Pendaftaran</h5>
                        <p class="text-muted mb-0">
                            Lengkapi seluruh data calon peserta didik.
                        </p>
                    </div>

                </div>

                <hr>

                <div class="d-flex mb-4">

                    <div class="me-4">
                        <span class="badge bg-primary fs-6">2</span>
                    </div>

                    <div>
                        <h5>Upload Berkas</h5>
                        <p class="text-muted mb-0">
                            Unggah foto, akta kelahiran, dan kartu keluarga.
                        </p>
                    </div>

                </div>

                <hr>

                <div class="d-flex mb-4">

                    <div class="me-4">
                        <span class="badge bg-primary fs-6">3</span>
                    </div>

                    <div>
                        <h5>Verifikasi Berkas</h5>
                        <p class="text-muted mb-0">
                            Tim Tata Usaha akan memverifikasi kelengkapan data.
                        </p>
                    </div>

                </div>

                <hr>

                <div class="d-flex mb-4">

                    <div class="me-4">
                        <span class="badge bg-primary fs-6">4</span>
                    </div>

                    <div>
                        <h5>Pengumuman Hasil</h5>
                        <p class="text-muted mb-0">
                            Calon siswa dapat melihat status pendaftaran secara online.
                        </p>
                    </div>

                </div>

                <hr>

                <div class="d-flex mb-4">

                    <div class="me-4">
                        <span class="badge bg-success fs-6">5</span>
                    </div>

                    <div>
                        <h5>Daftar Ulang</h5>
                        <p class="text-muted mb-0">
                            Peserta yang diterima wajib melakukan daftar ulang.
                        </p>
                    </div>

                </div>

                <hr>

                <div class="d-flex">

                    <div class="me-4">
                        <span class="badge bg-success fs-6">✓</span>
                    </div>

                    <div>
                        <h5>Resmi Menjadi Siswa</h5>
                        <p class="text-muted mb-0">
                            Akun siswa dibuat dan siap mengikuti kegiatan belajar.
                        </p>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<div class="text-center mt-5">

    <a
        href="{{ route('ppdb.create') }}"
        class="btn btn-primary btn-lg px-5"
    >
        Mulai Pendaftaran
    </a>

</div>


</div>


@endsection