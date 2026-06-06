@extends('layouts.app')

@section('content')

<div class="container py-5">

    <div class="card shadow">

        <div class="card-body text-center">

            <h2>

                Bukti Pendaftaran PPDB

            </h2>

            <hr>

            <h4>

                {{ $data->nama }}

            </h4>

            <p>

                Nomor Pendaftaran

            </p>

            <h3>

                {{ $data->no_pendaftaran }}

            </h3>

            <span class="badge bg-warning">

                {{ strtoupper($data->status) }}

            </span>

            <div class="mt-4">

                <a
                    href="{{ route('ppdb.status') }}"
                    class="btn btn-primary"
                >

                    Cek Status

                </a>

            </div>

        </div>

    </div>

</div>

@endsection