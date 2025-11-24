@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>Dashboard Guru</h1>
    <p>Anda login sebagai <strong>Guru</strong>.</p>

    <div class="card mt-4">
        <div class="card-body">
            <h5>Menu untuk Guru</h5>
            <ul>
                <li>Melihat Jadwal Mengajar</li>
                <li>Input Nilai</li>
                <li>Data Kelas & Siswa</li>
                <li>Pengumuman</li>
            </ul>
        </div>
    </div>
</div>
@endsection
