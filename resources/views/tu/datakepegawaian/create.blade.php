@extends('layouts.tu')

@section('title', 'Tambah Pegawai')

@section('content')

<h2 class="mb-4">Tambah Data Pegawai</h2>

<form action="{{ route('tu.datakepegawaian.store') }}" method="POST">
    @csrf

    @include('tu.datakepegawaian.form')

    <button class="btn btn-primary mt-3">Simpan</button>
</form>

@endsection
