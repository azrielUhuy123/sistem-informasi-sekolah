@extends('layouts.app')

@section('title', 'Manajemen Data Guru')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold mb-0">Manajemen Data Guru</h4>
            <small class="text-muted">Kelola data guru</small>
        </div>

        <a href="{{ route('tu.guru.create') }}" class="btn btn-primary btn-sm">
            + Tambah
        </a>
    </div>

    {{-- TABEL --}}
    <div class="card">
        <div class="card-body">

            <table class="table table-hover table-sm align-middle">

                <thead class="table-light">
                    <tr>
                        <th width="40">
                            <input type="checkbox" id="selectAll">
                        </th>
                        <th width="150">No</th>
                        <th>Nama Guru</th>
                        <th width="300" class="text-start">NIP</th>
                        <th width="250" class="text-start">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($guru as $row)

                        <tr>
                            <td>
                                <input type="checkbox" class="row-checkbox">
                            </td>

                            <td>{{ $loop->iteration }}</td>

                            <td>{{ $row->nama }}</td>

                            <td>{{ $row->nip }}</td>

                            {{-- AKSI --}}
                            <td>

                                <a href="{{ route('tu.guru.show', $row->id) }}"
                                   class="btn btn-sm btn-info">
                                    Detail
                                </a>

                                <a href="{{ route('tu.guru.edit', $row->id) }}"
                                   class="btn btn-sm btn-warning">
                                    Edit
                                </a>

                                <form action="{{ route('tu.guru.destroy', $row->id) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Yakin hapus data?')">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-danger">
                                        Hapus
                                    </button>
                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                Tidak ada data guru
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

            {{-- PAGINATION --}}
            <div class="mt-3">
                {{ $guru->links() }}
            </div>

        </div>
    </div>

</div>

@endsection