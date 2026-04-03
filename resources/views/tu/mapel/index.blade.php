@extends('layouts.tu')

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
           class="btn btn-primary btn-sm shadow-sm">
            + Tambah Mapel
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
                        <th width="100" class="test-start">Kode</th>
                        <th width="200" class="test-start">Nama Mata Pelajaran</th>
                        <th width="200" class="test-start">KKM</th>
                        <th width="200" class="test-start">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($mata_pelajaran as $row)

                        <tr>
                            <td>
                                <input type="checkbox">
                            </td>

                            <td>{{ $loop->iteration }}</td>

                            <td>{{ $row->kode_mapel ?? '-' }}</td>

                            <td>{{ $row->nama_mapel }}</td>

                            <td>{{ $row->kkm ?? '-' }}</td>

                            {{-- AKSI --}}
                            <td>

                                <a href="{{ route('tu.mapel.edit', $row->id) }}"
                                   class="btn btn-sm btn-warning">
                                    Edit
                                </a>

                                <form action="{{ route('tu.mapel.destroy', $row->id) }}" 
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
                            <td colspan="6" class="text-center text-muted">
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