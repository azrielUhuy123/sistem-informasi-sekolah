@extends('layouts.app')

@section('title', 'Dokumen Sekolah')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                Template Surat Sekolah
            </h3>

            <p class="text-muted mb-0">
                Kelola Template dokumen / surat internal sekolah yang dapat diakses oleh guru dan kepsek
            </p>
        </div>

        <a href="{{ route('tu.dokumen.create', 'sekolah') }}"
           class="btn btn-primary shadow-sm">

            <i class="bi bi-plus-circle me-1"></i>
            Tambah Surat

        </a>

    </div>

    {{-- ALERT SUCCESS --}}
    @if(session('success'))

        <div class="alert alert-success border-0 shadow-sm">

            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}

        </div>

    @endif

    {{-- ALERT ERROR --}}
    @if(session('error'))

        <div class="alert alert-danger border-0 shadow-sm">

            <i class="bi bi-exclamation-circle-fill me-2"></i>
            {{ session('error') }}

        </div>

    @endif

    {{-- CARD --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table align-middle mb-0">

                    <thead class="bg-dark text-white">

                        <tr>

                            <th width="6%" class="ps-4 py-3">
                                No
                            </th>

                            <th class="py-3">
                                Nama Surat
                            </th>

                            <th class="py-3">
                                Kategori
                            </th>

                            <th width="18%" class="text-center py-3">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($dokumen as $row)

                            <tr>

                                {{-- NOMOR --}}
                                <td class="ps-4 fw-semibold text-muted">
                                    {{ $loop->iteration }}
                                </td>

                                {{-- NAMA SURAT --}}
                                
                                <td>

                                    <div class="fw-semibold text-dark">
                                        {{ $row->nama_surat }}
                                    </div>

                                </td>

                                {{-- KATEGORI --}}
                                <td class="align-middle">

                                    <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">

                                        {{ $row->kategori_surat }}

                                    </span>

                                </td>

                                {{-- AKSI --}}
                                <td class="text-center">

                                    <div class="d-flex justify-content-center gap-2">

                                        {{-- DOWNLOAD --}}
                                        <a href="{{ asset('uploads/dokumen/' . $row->file) }}"
                                           download
                                           class="btn btn-sm btn-success shadow-sm"
                                           title="Download">

                                            <i class="bi bi-download"></i>

                                        </a>

                                        {{-- EDIT --}}
                                        <a href="{{ route('tu.dokumen.edit', $row->id) }}"
                                           class="btn btn-sm btn-warning shadow-sm"
                                           title="Edit">

                                            <i class="bi bi-pencil-square"></i>

                                        </a>

                                        {{-- DELETE --}}
                                        <form action="{{ route('tu.dokumen.destroy', $row->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Hapus dokumen ini?')">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-danger shadow-sm"
                                                    title="Hapus">

                                                <i class="bi bi-trash"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="4"
                                    class="text-center py-5">

                                    <div class="d-flex flex-column align-items-center">

                                        <i class="bi bi-folder-x text-muted"
                                           style="font-size: 50px;"></i>

                                        <h6 class="mt-3 text-muted">
                                            Belum ada dokumen
                                        </h6>

                                        <p class="text-muted small mb-0">
                                            Dokumen sekolah yang diupload akan muncul di sini
                                        </p>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection