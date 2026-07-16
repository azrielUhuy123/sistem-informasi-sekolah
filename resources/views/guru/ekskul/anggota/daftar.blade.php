@extends('layouts.app')

@section('title', 'Daftar Anggota Ekskul')

@section('content')

<div class="container-fluid py-4">

        {{-- HEADER --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2">Manajemen Anggota Ekskul</h3>
                    <p class="mb-0 opacity-75">Kelola anggota ekstrakurikuler</p>
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

    {{-- SEARCH --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="GET">

                <div class="row g-3 align-items-end">

                    <div class="col-md-10">

                        <label class="form-label">

                            Cari Anggota

                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Nama / NIS"
                            value="{{ $search }}"
                        >

                    </div>

                    <div class="col-md-2">

                        <button class="btn btn-dark w-100">

                            Cari

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

    {{-- TABLE --}}
    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table align-middle">

                    <thead class="table-light">

                        <tr>

                            <th width="70">

                                No

                            </th>

                            <th>

                                Nama

                            </th>

                            <th>

                                NIS

                            </th>

                            <th>

                                Tanggal Bergabung

                            </th>

                            <th>

                                Status

                            </th>

                            <th width="120">

                                Aksi

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($anggota as $a)

                            <tr>

                                <td>

                                    {{ $anggota->firstItem() + $loop->index }}

                                </td>

                                <td>

                                    {{ $a->siswa->nama }}

                                </td>

                                <td>

                                    {{ $a->siswa->nis }}

                                </td>

                                <td>

                                    {{ $a->created_at->format('d M Y') }}

                                </td>

                                <td>

                                    <span class="badge bg-success">

                                        Aktif

                                    </span>

                                </td>

                                <td>

                                    <form
                                        action="{{ route('guru.ekskul.daftar-anggota.destroy', $a->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Keluarkan anggota ini?')"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-sm btn-danger">

                                            Keluarkan

                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="text-center text-muted py-4"
                                >

                                    Belum ada anggota ekskul

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- PAGINATION --}}
            <div class="mt-4">

                {{ $anggota->links() }}

            </div>

        </div>

    </div>

</div>

@endsection