@extends('layouts.app')

@section('content')

<div class="container py-5">

    <div class="card shadow">

        <div class="card-body">

            <h1>

                PPDB SDN Mekar Jaya

            </h1>

            <p>

                Tahun Ajaran
                {{ optional($setting?->tahunAjaran)->nama }}

            </p>

            <p>

                Kuota:
                {{ $setting?->kuota }}

            </p>

            <a
                href="{{ route('ppdb.create') }}"
                class="btn btn-primary"
            >

                Daftar Sekarang

            </a>

        </div>

    </div>

</div>

@endsection