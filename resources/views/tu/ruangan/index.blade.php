@extends('layouts.tu')

@section('title', 'Manajemen Ruangan')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Manajemen Ruangan</h4>
            <small class="text-muted">Kelola data ruangan</small>
        </div>

        <a href="{{ route('tu.ruangan.create') }}" 
           class="btn btn-primary btn-sm shadow-sm">
            + Tambah Ruangan
        </a>
    </div>

    {{-- CARD --}}
    <div class="card">
        <div class="card-body">

            <table class="table table-hover align-middle">

                <thead class="table-light">
                    <tr>
                        <th width="40">
                            <input type="checkbox">
                        </th>
                        <th width="60">No</th>
                        <th width="150" class="text-start">Kode</th>
                        <th width="200" class="text-start">Nama Ruangan</th>
                        <th width="150" class="text-start">Kapasitas</th>
                        <th width="150" class="text-start">Jenis</th>
                        <th width="150" class="text-start">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($ruangan as $row)

                        <tr>
                            <td>
                                <input type="checkbox">
                            </td>

                            <td>{{ $loop->iteration }}</td>

                            <td>{{ $row->kode_ruangan ?? '-' }}</td>

                            <td>{{ $row->nama_ruangan }}</td>

                            <td>{{ $row->kapasitas ?? '-' }}</td>

                            <td>
                                <span class="badge bg-secondary">
                                    {{ $row->jenis_ruangan ?? '-' }}
                                </span>
                            </td>

                            {{-- AKSI --}}
                            <td>

                                <a href="{{ route('tu.ruangan.edit', $row->id) }}"
                                   class="btn btn-sm btn-warning">
                                    Edit
                                </a>

                                <form action="{{ route('tu.ruangan.destroy', $row->id) }}" 
                                      method="POST" 
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-danger"
                                            onclick="return confirm('Yakin hapus?')">
                                        Hapus
                                    </button>
                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                Tidak ada data ruangan
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

            {{-- PAGINATION --}}
            <div class="mt-3">
                {{ $ruangan->links() }}
            </div>

        </div>
    </div>

</div>

@endsection