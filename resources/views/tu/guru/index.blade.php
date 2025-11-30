@extends('layouts.tu')

@section('title', 'Manajemen Data Guru')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Manajemen Data Guru</h1>
    <a href="{{ route('tu.guru.create') }}" class="btn btn-primary">+ Tambah Guru</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card mt-3">
    <div class="card-body">

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>{!! sortLink('id', 'ID') !!}</th>
                    <th>{!! sortLink('nip', 'NIP') !!}</th>
                    <th>Foto</th>
                    <th>{!! sortLink('nama', 'Nama') !!}</th>
                    <th>{!! sortLink('jenis_kelamin', 'Jenis Kelamin') !!}</th>
                    <th>{!! sortLink('mapel_id', 'Mapel') !!}</th>
                    <th>Telepon</th>
                    <th>Export</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach($guru as $g)
                <tr>
                    <td>{{ $g->id }}</td>
                    <td>{{ $g->nip }}</td>

                    <td>
                        @if($g->foto)
                            <img src="{{ asset('foto-guru/'.$g->foto) }}" width="55" height="55"
                                 class="rounded" style="object-fit: cover;">
                        @else
                            -
                        @endif
                    </td>

                    <td>{{ $g->nama }}</td>
                    <td>{{ $g->jenis_kelamin }}</td>
                    <td>{{ $g->mapel->nama_mapel ?? '-' }}</td>
                    <td>{{ $g->telepon ?? '-' }}</td>

                    <!-- TOMBOL EXPORT JADWAL PDF PER GURU -->
                    <td>
                        <a href="{{ route('tu.jadwal.guru.export.pdf', $g->id) }}"
                           class="btn btn-danger btn-sm">
                           Export PDF
                        </a>
                    </td>

                    <td>
                        <a href="{{ route('tu.guru.edit', $g->id) }}" class="btn btn-warning btn-sm">Edit</a>

                        <form action="{{ route('tu.guru.destroy', $g->id) }}" method="POST"
                              class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Hapus guru ini?')">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>

        <div class="mt-3">
            {{ $guru->links() }}
        </div>

    </div>
</div>

@endsection
