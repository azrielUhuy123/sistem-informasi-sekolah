@extends('layouts.app')

@section('title', 'Pengajuan Jadwal')

@section('content')

<div class="d-flex justify-content-between mb-3">

    <h4>Pengajuan Jadwal</h4>

    <a href="{{ route('guru.pengajuan-jadwal.create') }}"
       class="btn btn-primary">

        Tambah Pengajuan

    </a>

</div>

<div class="card shadow-sm border-0">

    <div class="card-body">

        <table class="table">

            <thead>
                <tr>
                    <th>Kelas</th>
                    <th>Mapel</th>
                    <th>Hari</th>
                    <th>Jam</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

                @forelse($items as $item)

                    <tr>

                        <td>{{ $item->kelas->nama_kelas }}</td>

                        <td>{{ $item->mapel->nama_mapel }}</td>

                        <td>{{ $item->hari->nama }}</td>

                        <td>{{ $item->jam->nama }}</td>

                        <td>
                            <span class="badge bg-secondary">
                                {{ $item->status }}
                            </span>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="5" class="text-center">
                            Belum ada pengajuan
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection