@extends('layouts.app')

@section('title', 'Dokumen Surat')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2">Manajemen Dokumen</h3>
                    <p class="mb-0 opacity-75">Kelola dokumen resmi sekolah</p>
                </div>
                <div class="badge bg-white text-primary px-3 py-2 fs-6">
                    <i class="bi bi-shield-check me-2"></i> Sistem Terhubung
                </div>
            </div>
        </div>
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

            {{-- TOP BAR --}}
            <div class="d-flex justify-content-end p-3 border-bottom">
                <form method="GET"
                    action="{{ route('guru.dokumen.surat') }}">

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

                                {{-- NAMA FILE --}}
                                <td class="ps-4">

                                    <div class="fw-semibold">
                                        {{ $row->nama_surat }}
                                    </div>

                                </td>

                                {{-- DOWNLOAD --}}
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