@extends('layouts.tu')

@section('title', 'Manajemen Kelas')

@section('content')

<div class="card shadow-sm">
    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0">Grup Kelas</h5>

            <a href="{{ route('tu.kelas.grup.create') }}"
               class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle"></i> Tambah Grup
            </a>
        </div>

        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Tingkat</th>
                    <th>Tahun Ajaran</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($grupKelas as $grup)
                    <tr>
                        <td>Kelas {{ $grup->kelasMaster->tingkat }}</td>
<td>{{ $grup->tahunAjaran->tahun }}</td>

                        <td class="text-center">
                            <a href="{{ route('tu.kelas.rombel.index', [
                                'kelas_master' => $grup->kelas_master_id,
                                'tahun_ajaran' => $grup->tahun_ajaran_id
                            ]) }}"
                            class="btn btn-sm btn-primary">
                                Masuk
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted">
                            Belum ada data kelas
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</div>

@endsection
