@extends('layouts.app')

@section('title', 'Daftar Anggota Ekskul')

@section('content')

<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="mb-4">

        <h3 class="fw-bold mb-1">

            Daftar Anggota Ekskul

        </h3>

        <p class="text-muted mb-0">

            {{ $ekskul->nama }}

        </p>

    </div>

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