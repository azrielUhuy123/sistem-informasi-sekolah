@extends('layouts.app')

@section('title', 'Preview Naik Kelas')

@section('content')
<div class="container-fluid">

    <h4 class="mb-3">Preview Naik Kelas</h4>

    <p class="text-muted">
        Dari <b>{{ $tahunAktif->tahun }}</b> →
        <b>{{ $tahunBerikutnya->tahun }}</b>
    </p>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Siswa</th>
                        <th>Kelas Sekarang</th>
                        <th>Kelas Tujuan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($preview as $row)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $row['siswa']->nama }}</td>
                            <td>{{ $row['kelas_asal'] }}</td>
                            <td>
                                @if ($row['kelas_tujuan'] === 'LULUS')
                                    <span class="badge bg-dark">LULUS</span>
                                @else
                                    <span class="badge bg-primary">
                                        {{ $row['kelas_tujuan'] }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $row['status'] === 'Naik' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $row['status'] }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3 d-flex gap-2">
        <a href="{{ route('tu.penempatan-siswa.index') }}" class="btn btn-secondary">
            Kembali
        </a>

        <form action="{{ route('tu.penempatan-siswa.naik-kelas') }}" method="POST"
              onsubmit="return confirm('Yakin menjalankan proses naik kelas?')">
            @csrf
            <button class="btn btn-warning">
                <i class="bi bi-arrow-up-circle"></i> Proses Naik Kelas
            </button>
        </form>
    </div>

</div>
@endsection
