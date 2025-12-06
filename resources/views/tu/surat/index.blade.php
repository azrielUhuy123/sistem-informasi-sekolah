@extends('layouts.tu')

@section('title', 'Surat')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Dokumen Surat</h1>
    <a href="{{ route('tu.arsip-dokumen.create') }}" class="btn btn-primary">
        + Tambah Surat
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card mt-3">
    <div class="card-body">

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama Surat</th>
                    <th>Nomor Surat</th>
                    <th>Kategori Surat</th>
                    <th>Tanggal Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($data as $row)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $row->nama_surat }}</td>
                        <td>{{ $row->nomor_surat ?? '-' }}</td>
                        <td>{{ $row->kategori ?? '-' }}</td>
                        <td>{{ $row->tanggal_dibuat ?? '-' }}</td>

                        <td>

                            {{-- PREVIEW PDF.JS --}}
                            @if(pathinfo($row->file_path, PATHINFO_EXTENSION) === 'pdf')
                                <a href="{{ route('tu.arsip-dokumen.viewer', $row->id) }}"
                                   target="_blank"
                                   class="btn btn-secondary btn-sm">
                                    Preview
                                </a>
                            @endif

                            {{-- DOWNLOAD --}}
                            <a href="{{ route('tu.arsip-dokumen.download', $row->id) }}"
                               class="btn btn-success btn-sm">
                                Download
                            </a>

                            {{-- EDIT --}}
                            <a href="{{ route('tu.arsip-dokumen.edit', $row->id) }}"
                               class="btn btn-warning btn-sm">
                                Edit
                            </a>

                            {{-- DELETE --}}
                            <form action="{{ route('tu.arsip-dokumen.destroy', $row->id) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Hapus dokumen ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">
                                    Hapus
                                </button>
                            </form>

                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            Tidak ada surat tersimpan.
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>

    </div>
</div>

@endsection
