@extends('layouts.tu')

@section('title', 'Rombel')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">
        Kelas {{ $kelasGrup->tingkat }} – Angkatan {{ $kelasGrup->angkatan->tahun }}
    </h5>

    <a href="{{ route('tu.kelas.rombel.create', $kelasGrup->id) }}"
       class="btn btn-primary btn-sm">
        + Tambah Rombel
    </a>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Kelas</th>
            <th>Angkatan</th>
            <th>Jumlah Siswa</th>
            <th width="120">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($rombels as $rombel)
            <tr>
                <td>{{ $rombel->nama_kelas }}</td>
                <td>{{ $kelasGrup->angkatan->tahun }}</td>
                <td>{{ $rombel->siswa_count }}</td>
                <td>-</td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center text-muted">
                    Belum ada rombel
                </td>
            </tr>
        @endforelse
    </tbody>
</table>


@endsection
