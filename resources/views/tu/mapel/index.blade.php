@extends('layouts.app')

@section('title', 'Mata Pelajaran')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2">Data Mata Pelajaran</h3>
                    <p class="mb-0 opacity-75">Kelola data mata pelajaran sekolah</p>
                </div>
                <div class="badge bg-white text-primary px-3 py-2 fs-6">
                    <i class="bi bi-shield-check me-2"></i> Sistem Terhubung
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end align-items-center mb-4">

        <a href="{{ route('tu.mapel.create') }}" 
           class="btn btn-dark">
            <i class="bi bi-plus-lg me-1"></i>
            Tambah Mapel
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
    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <table class="table align-middle">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Mata Pelajaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($mata_pelajaran as $row)

                        <tr>

                            <td>{{ $loop->iteration }}</td>

                            <td>
                                <span class="badge bg-primary">
                                    {{ $row->kode_mapel ?? '-' }}
                                </span>
                            </td>

                            <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $row->nama_mapel }}
                                    </span>     
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
                                            <a class="dropdown-item" href="{{ route('tu.mapel.edit', $row->id) }}">
                                                <i class="bi bi-pencil-square me-2"></i> Edit
                                            </a>
                                        </li>

                                        <li><hr class="dropdown-divider"></li>

                                        <li>
                                            <form action="{{ route('tu.mapel.destroy', $row->id) }}"
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
                            <td colspan="3" class="text-center text-muted">
                                Tidak ada data mata pelajaran
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>
    </div>

</div>

@endsection