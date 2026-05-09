@extends('layouts.app')

@section('title', 'Dokumen Siswa')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="mb-1">Dokumen Siswa</h1>

            <p class="text-muted mb-0">
                Daftar dokumen untuk siswa
            </p>
        </div>

        <a href="{{ route('tu.dokumen.create', 'siswa') }}"
           class="btn btn-primary">

            <i class="fas fa-plus me-1"></i>
            Tambah Dokumen

        </a>

    </div>

    {{-- ALERT SUCCESS --}}
    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif

    {{-- ALERT ERROR --}}
    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif

    {{-- TABLE --}}
    <div class="card shadow-sm border-0">

        <div class="card-body">

            <table class="table table-bordered table-striped align-middle">

                <thead class="table-dark">

                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Surat</th>
                        <th>Kategori Surat</th>
                        <th>File</th>
                        <th width="20%">Aksi</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($dokumen as $row)

                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>

                            <td>
                                {{ $row->nama_surat }}
                            </td>

                            <td>
                                {{ $row->kategori_surat }}
                            </td>

                            <td>

                                @if(pathinfo($row->file, PATHINFO_EXTENSION) === 'pdf')

                                    <a href="{{ asset('uploads/dokumen/' . $row->file) }}"
                                       target="_blank"
                                       class="btn btn-secondary btn-sm">

                                        Preview

                                    </a>

                                @endif

                            </td>

                            <td>

                                {{-- DOWNLOAD --}}
                                <a href="{{ asset('uploads/dokumen/' . $row->file) }}"
                                   download
                                   class="btn btn-success btn-sm">

                                    Download

                                </a>

                                {{-- DELETE --}}
                                <form action="{{ route('tu.dokumen.destroy', $row->id) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Hapus dokumen ini?')">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-danger btn-sm">

                                        Hapus

                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5"
                                class="text-center text-muted">

                                Tidak ada dokumen siswa.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection