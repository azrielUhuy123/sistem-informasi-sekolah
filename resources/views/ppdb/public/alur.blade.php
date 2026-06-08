@extends('layouts.public')

@section('title','Alur PPDB')

@section('content')

<div class="container py-5">

    <div class="text-center mb-5">

        <h1>

            Alur Pendaftaran PPDB

        </h1>

        <p class="text-muted">

            Ikuti tahapan berikut untuk melakukan pendaftaran.

        </p>

    </div>

    <div class="row g-4">

        <div class="col-md-2">

            <div class="card h-100 shadow-sm">

                <div class="card-body text-center">

                    <h1>1</h1>

                    <h6>

                        Isi Formulir

                    </h6>

                </div>

            </div>

        </div>

        <div class="col-md-2">

            <div class="card h-100 shadow-sm">

                <div class="card-body text-center">

                    <h1>2</h1>

                    <h6>

                        Upload Berkas

                    </h6>

                </div>

            </div>

        </div>

        <div class="col-md-2">

            <div class="card h-100 shadow-sm">

                <div class="card-body text-center">

                    <h1>3</h1>

                    <h6>

                        Verifikasi TU

                    </h6>

                </div>

            </div>

        </div>

        <div class="col-md-2">

            <div class="card h-100 shadow-sm">

                <div class="card-body text-center">

                    <h1>4</h1>

                    <h6>

                        Pengumuman

                    </h6>

                </div>

            </div>

        </div>

        <div class="col-md-2">

            <div class="card h-100 shadow-sm">

                <div class="card-body text-center">

                    <h1>5</h1>

                    <h6>

                        Daftar Ulang

                    </h6>

                </div>

            </div>

        </div>

    </div>

    <div class="text-center mt-5">

        <a
            href="{{ route('ppdb.create') }}"
            class="btn btn-primary btn-lg"
        >

            Mulai Pendaftaran

        </a>

    </div>

</div>

@endsection