@extends('layouts.tu')

@section('title', 'Data Kepegawaian')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Data Kepegawaian</h1>
    <a href="{{ route('tu.datakepegawaian.create') }}" class="btn btn-primary">
        + Tambah Pegawai
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-body p-0">

        <table class="table align-middle mb-0">

            <thead class="border-bottom">
                <tr class="text-muted small">
                    <th width="50">No</th>
                    <th>Nama Pegawai</th>
                    <th>NIP</th>
                    <th>NUPTK</th>
                    <th>Jabatan</th>
                    <th width="200" class="text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($data as $row)
                    <tr class="border-bottom">

                        <td>{{ $loop->iteration }}</td>

                        <td class="fw-semibold">
                            {{ $row->nama }}
                        </td>

                        <td>{{ $row->nip ?? '-' }}</td>

                        <td>{{ $row->nuptk ?? '-' }}</td>

                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ $row->jabatan ?? '-' }}
                            </span>
                        </td>

                        <td class="text-center">

                            <a href="{{ route('tu.datakepegawaian.show', $row->id) }}"
                               class="btn btn-sm btn-info text-white px-2">
                                Detail
                            </a>

                            <a href="{{ route('tu.datakepegawaian.edit', $row->id) }}"
                               class="btn btn-sm btn-warning px-2">
                                Edit
                            </a>

                            <form action="{{ route('tu.datakepegawaian.destroy', $row->id) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Hapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger px-2">
                                    Hapus
                                </button>
                            </form>

                        </td>

                    </tr>

                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            Belum ada data pegawai
                        </td>
                    </tr>
                @endforelse

            </tbody>

        </table>

    </div>
</div>

@endsection
