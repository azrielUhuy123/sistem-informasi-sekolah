@extends('layouts.app')

@section('title', 'Mata Pelajaran')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Mata Pelajaran</h4>
            <small class="text-muted">Data Mata pelajaran</small>
        </div>
    </div>

    {{-- CARD --}}
    <div class="card">
        <div class="card-body">

            <table class="table align-middle">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Mata Pelajaran</th>
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