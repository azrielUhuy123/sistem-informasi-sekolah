@extends('layouts.app')

@section('title','Verifikasi Berkas')

@section('content')

<div class="container-fluid">

    <div class="card">

        <div class="card-header">

            Verifikasi Berkas PPDB

        </div>

        <div class="card-body">

            <h5>

                {{ $pendaftar->nama }}

            </h5>

            <hr>

            <div class="mb-3">

                <label class="form-label">

                    Kartu Keluarga

                </label>

                <br>

                <a
                    href="{{ asset('storage/'.$pendaftar->kartu_keluarga) }}"
                    target="_blank"
                    class="btn btn-outline-primary"
                >

                    Lihat KK

                </a>

            </div>

            <div class="mb-3">

                <label class="form-label">

                    Akta Kelahiran

                </label>

                <br>

                <a
                    href="{{ asset('storage/'.$pendaftar->akta_lahir) }}"
                    target="_blank"
                    class="btn btn-outline-primary"
                >

                    Lihat Akta

                </a>

            </div>

            <div class="mb-3">

                <label class="form-label">

                    Pas Foto

                </label>

                <br>

                <a
                    href="{{ asset('storage/'.$pendaftar->foto) }}"
                    target="_blank"
                    class="btn btn-outline-primary"
                >

                    Lihat Foto

                </a>

            </div>

            <form
                action="{{ route('tu.ppdb.pendaftar.verifikasi.store',$pendaftar) }}"
                method="POST"
            >

                @csrf

                <div class="mb-3">

                    <label class="form-label">

                        Catatan Verifikasi

                    </label>

                    <textarea
                        name="catatan_verifikasi"
                        class="form-control"
                        rows="4"
                    ></textarea>

                </div>

                <button
                    class="btn btn-success"
                >

                    Tandai Sudah Diverifikasi

                </button>

            </form>

        </div>

    </div>

</div>

@endsection