@extends('layouts.app')

@section('title', 'Ekskul')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2">Manajemen Ekskul</h3>
                    <p class="mb-0 opacity-75">Kelola kegiatan ekstrakurikuler sekolah</p>
                </div>
                <div class="badge bg-white text-primary px-3 py-2 fs-6">
                    <i class="bi bi-shield-check me-2"></i> Sistem Terhubung
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-end align-items-center mb-4">

        <a
            href="{{ route('tu.ekskul.create') }}"
            class="btn btn-dark"
        >

            <i class="bi bi-plus-lg me-1"></i>

            Tambah Ekskul

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

    {{-- TABLE --}}
    <div class="card border-0 shadow-sm">

        <div class="card-body">

                <table
                    class="table align-middle"
                >

                    <thead>

                        <tr>

                            <th>

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

                            <th>

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

                                    <div class="badge bg-primary">

                                        {{ $e->nama }}

                                    </div>

                                    <div class="badge bg-light text-dark border">

                                        {{ Str::limit($e->deskripsi, 50) }}

                                    </div>

                                </td>

                                <td>

                                    {{ $e->guru->pegawai->nama ?? '-' }}

                                </td>

                                <td>
                                    <span class="badge bg-light text-dark border">

                                        {{ $e->hari ?? '-' }}

                                    </span>

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

                                        <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle">
                                            <i class="bi bi-check-circle me-1"></i> Aktif
                                        </span>

                                    @else

                                        <span class="badge bg-danger-subtle text-danger-emphasis border border-danger-subtle">
                                            <i class="bi bi-x-circle me-1"></i> Nonaktif
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    <div class="dropdown">

                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                type="button"
                                                data-bs-toggle="dropdown"
                                                aria-expanded="false">

                                            <i class="bi bi-gear-fill"></i>

                                        </button>

                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">

                                            <li>
                                                <a class="dropdown-item" href="{{ route('tu.ekskul.edit', $e->id) }}">
                                                    <i class="bi bi-pencil-square me-2"></i> Edit
                                                </a>
                                            </li>

                                            <li><hr class="dropdown-divider"></li>

                                            <li>
                                                <form action="{{ route('tu.ekskul.destroy', $e->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Hapus data ini?')">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="bi bi-trash me-2"></i> Hapus
                                                    </button>

                                                </form>
                                            </li>

                                        </ul>

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

@endsection