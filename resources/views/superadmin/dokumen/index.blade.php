@extends('layouts.app')

@section('title', 'Dokumen Surat')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="mb-4">

        <h3 class="fw-bold mb-1">
            Template Dokumen 
        </h3>

        <p class="text-muted mb-0">
            Download template dokumen resmi sekolah yang dapat digunakan untuk keperluan administrasi sekolah
        </p>

    </div>

    {{-- CARD --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-0">

            {{-- FILTER --}}
            <div class="d-flex justify-content-end p-3 border-bottom">

                <form method="GET"
                      action="{{ route('superadmin.dokumen.surat') }}">

                    <select name="kategori"
                            class="form-select"
                            onchange="this.form.submit()">

                        <option value="">
                            Pilih Kategori
                        </option>

                        @foreach($kategori as $item)

                            <option value="{{ $item->kategori_surat }}"
                                {{ request('kategori') == $item->kategori_surat ? 'selected' : '' }}>

                                {{ $item->kategori_surat }}

                            </option>

                        @endforeach

                    </select>

                </form>

            </div>

            {{-- TABLE --}}
            <div class="table-responsive">

                <table class="table align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="ps-4">
                                Nama Dokumen
                            </th>

                            <th width="15%" class="text-center">
                                Unduh
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($dokumen as $row)

                            <tr>

                                <td class="ps-4">

                                    <div class="fw-semibold">
                                        {{ $row->nama_surat }}
                                    </div>

                                </td>

                                <td class="text-center">

                                    <a href="{{ asset('uploads/dokumen/' . $row->file) }}"
                                       download
                                       class="btn btn-success rounded-3">

                                        <i class="bi bi-cloud-arrow-down-fill"></i>

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="2"
                                    class="text-center py-5 text-muted">

                                    Belum ada dokumen tersedia

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