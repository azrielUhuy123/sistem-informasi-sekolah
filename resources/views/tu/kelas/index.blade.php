@extends('layouts.app')

@section('title', 'Manajemen Kelas')

@section('content')

<div class="card shadow-sm">
    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0">Grup Kelas</h5>
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

                <form action="{{ route('tu.siswa.import') }}" method="POST" enctype="multipart/form-data" class="d-flex gap-2">
                    @csrf
                    <input type="file" name="file" class="form-control form-control-sm" required>
                    <button class="btn btn-primary btn-sm">
                        Import Excel
                    </button>
                </form>

            <a href="{{ route('tu.kelas.grup.create') }}"
               class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle"></i> Tambah Grup
            </a>
        </div>

        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width: 20%">Tingkat</th>
                    <th>Angkatan</th>
                    <th style="width: 20%">Jumlah Kelas</th>
                    <th style="width: 15%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($grupKelas as $grup)
                    <tr>
                        <td>Kelas {{ $grup->kelasMaster->tingkat }}</td>

                        <td>
                            {{ $grup->angkatan->tahun }}
                            <span class="text-muted">
                                ({{ $grup->angkatan->keterangan }})
                            </span>
                        </td>

                        <td>
                            <span class="badge bg-secondary">
                                {{ $grup->jumlah_kelas }} Kelas
                            </span>
                        </td>

                        <td class="text-center">
                            <a href="{{ route('tu.kelas.rombel.index', $grup->id) }}"
                               class="btn btn-sm btn-outline-primary">
                                Masuk
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            Belum ada data kelas
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</div>

@endsection
