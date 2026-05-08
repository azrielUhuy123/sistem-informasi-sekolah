@extends('layouts.app')

@section('title', 'Rombel')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">
        Kelas {{ $kelasGrup->tingkat }} – Angkatan {{ $kelasGrup->angkatan->tahun }}
    </h5>

    <div class="d-flex gap-2">
        {{-- BUTTON KEMBALI --}}
        <a href="{{ route('tu.kelas.index') }}"
           class="btn btn-secondary btn-sm">
            ← Kembali
        </a>

        {{-- BUTTON TAMBAH --}}
        <a href="{{ route('tu.kelas.rombel.create', $kelasGrup->id) }}"
           class="btn btn-primary btn-sm">
            + Tambah Rombel
        </a>
    </div>
</div>
@if(session('import_report'))
    <div class="alert alert-info">
        Total diproses: {{ session('import_report.total') }} <br>
        Penempatan baru: {{ session('import_report.created') }} <br>
        Pindah kelas: {{ session('import_report.updated') }}
    </div>
@endif
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Kelas</th>
            <th>Angkatan</th>
            <th>Jumlah Siswa</th>
            <th>Wali Kelas</th>
            <th width="120">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($rombels as $rombel)
            <tr>
                <td>{{ $rombel->nama_kelas }}</td>
                <td>{{ $kelasGrup->angkatan->tahun }}</td>
                <td>{{ $rombel->siswa_count }}</td>
                <td>{{ optional(optional($rombel->waliKelas)->guru)->nama ?? '-' }}</td>
                <td>
                    <a href="{{ route('tu.kelas.rombel.show', $rombel->id) }}"
                    class="btn btn-sm btn-outline-primary">
                        Detail
                    </a>

                    <a href="{{ route('tu.kelas.wali', $rombel->id) }}" 
                    class="btn btn-sm btn-secondary">
                    Set Wali
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center text-muted">
                    Belum ada rombel
                </td>
            </tr>
        @endforelse
    </tbody>
</table>


@endsection
