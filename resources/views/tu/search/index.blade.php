@extends('layouts.tu')

@section('title', 'Hasil Pencarian')

@section('content')

<h2>Hasil Pencarian: "{{ $q }}"</h2>
<hr>

@if($siswa->isEmpty() && $kelas->isEmpty())
    <p class="text-muted">Tidak ditemukan hasil.</p>
@endif

@if(!$siswa->isEmpty())
    <h4>🧍‍♂️ Siswa</h4>
    <table class="table table-striped table-bordered">
        <tr class="table-dark">
            <th>NIS</th>
            <th>Nama</th>
            <th>Kelas</th>
        </tr>
        @foreach($siswa as $s)
            <tr>
                <td>{{ $s->nis }}</td>
                <td>{{ $s->nama }}</td>
                <td>{{ $s->kelas->nama_kelas ?? '-' }}</td>
            </tr>
        @endforeach
    </table>
@endif


@if(!$kelas->isEmpty())
    <h4 class="mt-4">🏫 Kelas</h4>
    <table class="table table-striped table-bordered">
        <tr class="table-dark">
            <th>Kelas</th>
            <th>Tingkat</th>
            <th>Tahun Ajaran</th>
        </tr>
        @foreach($kelas as $k)
            <tr>
                <td>{{ $k->nama_kelas }}</td>
                <td>{{ $k->tingkat }}</td>
                <td>{{ $k->tahun_ajaran }}</td>
            </tr>
        @endforeach
    </table>
@endif

@endsection
