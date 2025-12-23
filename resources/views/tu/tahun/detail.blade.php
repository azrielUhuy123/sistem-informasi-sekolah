@extends('layouts.tu')

@section('title', 'Detail Tahun Ajaran')

@section('content')
<h3>Detail Tahun Ajaran</h3>

<ul>
    <li><strong>Tahun:</strong> {{ $tahun->tahun }}</li>
    <li><strong>Status:</strong>
        @if($tahun->aktif)
            Aktif
        @else
            Tidak Aktif
        @endif
    </li>
</ul>

<a href="{{ route('tu.tahun.index') }}" class="btn btn-secondary">Kembali</a>
@endsection
