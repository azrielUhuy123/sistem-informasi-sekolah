@extends('layouts.tu')

@section('title', 'Edit Pegawai')

@section('content')

<h2 class="mb-4">Edit Data Pegawai</h2>

<form action="{{ route('tu.datakepegawaian.update', $pegawai->id) }}" method="POST">
    @csrf
    @method('PUT')

    @include('tu.datakepegawaian.form', ['pegawai' => $pegawai])

    <button class="btn btn-success mt-3">Update</button>
</form>

@endsection
