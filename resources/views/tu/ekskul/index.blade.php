@extends('layouts.app')

@section('title', 'Ekskul')

@section('content')

<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold mb-1">

                Manajemen Ekskul

            </h3>

            <p class="text-muted mb-0">

                Kelola kegiatan ekstrakurikuler sekolah

            </p>

        </div>

        <a
            href="{{ route('tu.ekskul.create') }}"
            class="btn btn-dark"
        >

            <i class="bi bi-plus-lg me-1"></i>

            Tambah Ekskul

        </a>

    </div>

    {{-- TABLE --}}
    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table
                    class="table align-middle"
                >

                    <thead class="table-light">

                        <tr>

                            <th width="70">

                                No

                            </th>

                            <th>

                                Nama Ekskul

                            </th>

                            <th>

                                Pembina

                            </th>

                            <th>

                                Hari

                            </th>

                            <th>

                                Jam

                            </th>

                            <th>

                                Kuota

                            </th>

                            <th>

                                Status

                            </th>

                            <th width="180">

                                Aksi

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($ekskuls as $i => $e)

                            <tr>

                                <td>

                                    {{ $i + 1 }}

                                </td>

                                <td>

                                    <div class="fw-semibold">

                                        {{ $e->nama }}

                                    </div>

                                    <small class="text-muted">

                                        {{ Str::limit($e->deskripsi, 50) }}

                                    </small>

                                </td>

                                <td>

                                    {{ $e->guru->nama ?? '-' }}

                                </td>

                                <td>

                                    {{ $e->hari ?? '-' }}

                                </td>

                                <td>

                                    {{ $e->jam_mulai }}
                                    -
                                    {{ $e->jam_selesai }}

                                </td>

                                <td>

                                    {{ $e->kuota }}

                                </td>

                                <td>

                                    @if($e->status == 'aktif')

                                        <span class="badge bg-success">

                                            Aktif

                                        </span>

                                    @else

                                        <span class="badge bg-secondary">

                                            Nonaktif

                                        </span>

                                    @endif

                                </td>

                                <td>

                                    <div class="d-flex gap-2">

                                        {{-- EDIT --}}
                                        <a
                                            href="{{ route('tu.ekskul.edit', $e->id) }}"
                                            class="btn btn-sm btn-warning"
                                        >

                                            Edit

                                        </a>

                                        {{-- DELETE --}}
                                        <form
                                            action="{{ route('tu.ekskul.destroy', $e->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Hapus ekskul ini?')"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                class="btn btn-sm btn-danger"
                                            >

                                                Hapus

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="8"
                                    class="text-center py-5 text-muted"
                                >

                                    Belum ada data ekskul

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