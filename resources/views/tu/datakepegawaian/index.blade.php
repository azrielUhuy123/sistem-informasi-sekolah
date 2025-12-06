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

<div class="card">
    <div class="card-body">

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>NIP</th>
                    <th>Jabatan</th>
                    <th>Status Kerja</th>
                    <th width="260px">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach($data as $row)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $row->nama }}</td>
                        <td>{{ $row->nip }}</td>
                        <td>{{ $row->jabatan }}</td>
                        <td>{{ $row->status_kerja }}</td>

                        <td>

                            {{-- BUTTON DETAIL --}}
                            <a href="{{ route('tu.datakepegawaian.show', $row->id) }}"
                               class="btn btn-info btn-sm text-white">
                                <i class="bi bi-eye"></i> Detail
                            </a>

                            {{-- EDIT --}}
                            <a href="{{ route('tu.datakepegawaian.edit', $row->id) }}"
                               class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>

                            {{-- DELETE --}}
                            <form action="{{ route('tu.datakepegawaian.destroy', $row->id) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Hapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        </td>

                    </tr>
                @endforeach
            </tbody>

        </table>

    </div>
</div>

@endsection
