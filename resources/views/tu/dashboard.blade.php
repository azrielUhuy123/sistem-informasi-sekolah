@extends('layouts.tu')

@section('title', 'Dashboard TU')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Dashboard Tata Usaha</h1>
    <span class="badge bg-primary">Anda login sebagai <b>{{ Auth::user()->role }}</b></span>
</div>

<div class="card p-4">
    <h4>Menu TU</h4>
    <ul>
        <li><a href="{{ route('tu.kelas.grup.index') }}">Manajemen Kelas</a></li>
        <li><a href="{{ route('tu.siswa.index') }}">Manajemen Data Siswa</a></li>
        <li><a href="#">Manajemen Data Guru</a></li>
        <li><a href="#">Manajemen Jadwal</a></li>
    </ul>
</div>

@endsection
