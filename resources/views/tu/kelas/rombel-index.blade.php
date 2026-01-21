@extends('layouts.tu')

@section('title', 'Daftar Rombel')

@section('content')

<div class="card shadow-sm">
    <div class="card-header fw-bold">
        Daftar Rombel
    </div>
    
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
            <a href="{{ route('tu.kelas.rombel.create', ['kelas_master' => request()->route('kelas_master'),
                'tahun_ajaran' => request()->route('tahun_ajaran')]) }}" 
                class="btn btn-sm btn-primary mb-3">+ Tambah Rombel</a>
                <tr>
                    <th>Nama Kelas</th>
                    <th>Tingkat</th>
                    <th>Tahun Ajaran</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kelas as $k)
                    <tr>
                        <td>{{ $k->nama_kelas }}</td>
                        <td>Kelas {{ $k->kelasMaster->tingkat }}</td>
                        <td>{{ $k->tahunAjaran->nama }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
