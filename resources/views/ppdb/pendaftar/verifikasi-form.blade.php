@extends('layouts.app')

@section('title','Verifikasi Berkas')

@section('content')

<div class="container-fluid">

    <div class="card">

        <div class="card-header">

            Verifikasi Berkas

        </div>

        <div class="card-body">

            <h5>{{ $pendaftar->nama }}</h5>

            <hr>

            <div class="mb-3">

                <label>Kartu Keluarga</label>

                @if($pendaftar->kartu_keluarga)

                    <a
                        href="{{ asset('storage/'.$pendaftar->kartu_keluarga) }}"
                        target="_blank"
                        class="btn btn-sm btn-primary"
                    >
                        Lihat File
                    </a>

                @else

                    <span class="badge bg-danger">
                        Belum Upload
                    </span>

                @endif

            </div>

            <div class="mb-3">

                <label>Akta Kelahiran</label>

                @if($pendaftar->akta_lahir)

                    <a
                        href="{{ asset('storage/'.$pendaftar->akta_lahir) }}"
                        target="_blank"
                        class="btn btn-sm btn-primary"
                    >
                        Lihat File
                    </a>

                @endif

            </div>

            <div class="mb-3">

                <label>Pas Foto</label>

                @if($pendaftar->foto)

                    <a
                        href="{{ asset('storage/'.$pendaftar->foto) }}"
                        target="_blank"
                        class="btn btn-sm btn-primary"
                    >
                        Lihat File
                    </a>

                @endif

            </div>

            <form
                method="POST"
                action="{{ route('tu.ppdb.pendaftar.simpan-verifikasi',$pendaftar) }}"
            >

                @csrf

                <div class="mb-3">

                    <label>Catatan Verifikasi</label>

                    <textarea
                        name="catatan_verifikasi"
                        class="form-control"
                        rows="3"
                    ></textarea>

                </div>

                <button
                    class="btn btn-success"
                >

                    Verifikasi Berkas

                </button>

            </form>

        </div>

    </div>

</div>

@endsection