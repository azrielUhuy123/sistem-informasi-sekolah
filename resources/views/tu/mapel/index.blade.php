@extends('layouts.app')

@section('title', 'Mata Pelajaran')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Manajemen Mata Pelajaran</h4>
            <small class="text-muted">Kelola data mata pelajaran</small>
        </div>

        <a href="{{ route('tu.mapel.create') }}" 
           class="btn btn-dark">
            <i class="bi bi-plus-lg me-1"></i>
            Tambah Mapel
        </a>
    </div>

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

                            <td>{{ $row->kode_mapel ?? '-' }}</td>

                            <td>
                                {{ $row->nama_mapel }}
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