@extends('layouts.app')

@section('title', 'Data Pendaftar Ditolak')

@section('content')
<div class="container-fluid">
    <x-alert.flash key="success" type="success"/>
    <x-alert.flash key="error" type="danger"/>

    <x-table.card
        title="Daftar Pendaftar Ditolak"
        subtitle="Pendaftar yang saat ini berstatus ditolak."
    >
        <x-slot:badge>
            <span class="badge bg-danger rounded-pill px-2 py-1">
                {{ $data->total() }} <span class="text-badge">Pendaftar</span>
            </span>
        </x-slot:badge>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>No Pendaftaran</th>
                        <th>Nama</th>
                        <th>Jenis Kelamin</th>
                        <th>Tanggal Pendaftaran</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $item)
                        <tr>
                            <td>{{ $item->no_pendaftaran }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->jenis_kelamin === 'L' ? 'Laki-Laki' : 'Perempuan' }}</td>
                            <td>{{ $item->tanggal_pendaftaran }}</td>
                            <td><span class="badge-status nonaktif">Ditolak</span></td>
                            <td>
                                <a href="{{ route('tu.ppdb.pendaftar.show', $item) }}" class="btn btn-primary btn-sm rounded-pill px-3">
                                    Detail <i class="bi bi-chevron-right ms-1"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">Belum ada pendaftar berstatus ditolak.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4 d-flex justify-content-end">
                {{ $data->links() }}
            </div>
        </div>
    </x-table.card>
</div>
@endsection