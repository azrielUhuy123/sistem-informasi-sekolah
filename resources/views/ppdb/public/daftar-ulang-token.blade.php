@extends('layouts.ppdb-form')

@section('title','Konfirmasi Daftar Ulang')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow">
                <div class="card-body p-4">
                    <h1 class="h4">Konfirmasi Daftar Ulang</h1>
                    <p>Nomor pendaftaran: <strong>{{ $pendaftar->no_pendaftaran }}</strong></p>
                    <p>Tautan ini berlaku satu kali dan selama 30 menit.</p>
                    <form method="POST" action="{{ route('ppdb.daftar-ulang.token.simpan', ['token' => $token]) }}">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-lg w-100">Selesaikan Daftar Ulang</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
