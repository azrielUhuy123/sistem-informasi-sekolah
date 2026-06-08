@extends('layouts.public')

@section('title','PPDB SDN Mekar Jaya')

@section('content')

<div class="container py-5">

    <div class="card shadow-sm">

        <div class="card-body text-center">

            <h1>

                PPDB SDN Mekar Jaya

            </h1>

            <p>

                Tahun Ajaran

                <strong>

                    {{ $setting?->tahunAjaran?->tahun }}

                </strong>

            </p>

            <hr>

            <div class="row">

                <div class="col-md-4">

                    <h6>Kuota</h6>

                    <h3>

                        {{ $setting?->kuota }}

                    </h3>

                </div>

                <div class="col-md-4">

                    <h6>Tanggal Mulai</h6>

                    <h3>

                        {{ $setting?->tanggal_mulai }}

                    </h3>

                </div>

                <div class="col-md-4">

                    <h6>Tanggal Selesai</h6>

                    <h3>

                        {{ $setting?->tanggal_selesai }}

                    </h3>

                </div>

            </div>

            <hr>

            @if($setting?->status == 'dibuka')

                <a
                    href="{{ route('ppdb.create') }}"
                    class="btn btn-primary btn-lg"
                >

                    Daftar Sekarang

                </a>

            @else

                <button
                    class="btn btn-secondary btn-lg"
                    disabled
                >

                    PPDB Ditutup

                </button>

            @endif

        </div>

        <div class="mt-4">

            <a
                href="{{ route('ppdb.alur') }}"
                class="btn btn-outline-secondary"
            >

                Lihat Alur PPDB

            </a>

        </div>

    </div>

</div>

@endsection