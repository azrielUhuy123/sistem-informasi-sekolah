@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Dashboard Kepala Sekolah</h1>
    <ul>
    <li><a href="{{ route('kepsek.monitoring.index') }}">Monitoring Guru</a></li>
    <li><a href="#">Monitoring Siswa (coming soon)</a></li>
    <li><a href="#">Laporan Kegiatan (coming soon)</a></li>
</ul>
    <p>Selamat datang di sistem.</p>
</div>
@endsection
