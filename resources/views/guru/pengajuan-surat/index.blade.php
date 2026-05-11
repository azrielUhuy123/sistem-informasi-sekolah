@extends('layouts.app')

@section('title', 'Pengajuan Surat')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="fw-bold mb-1">
                Pengajuan Surat
            </h4>

            <p class="text-muted mb-0">
                Kelola pengajuan Surat atau Laporan untuk divalidasi kepala sekolah
            </p>

        </div>

        <a href="{{ route('guru.pengajuan-surat.create') }}"
           class="btn btn-primary rounded-3">

            <i class="bi bi-plus-circle"></i>
            Buat Pengajuan

        </a>

    </div>

    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body">

            <table class="table align-middle">

                <thead class="table-light">

                    <tr>

                        <th>No</th>
                        <th>Nama Dokumen</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Aksi</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($pengajuan as $row)

                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>

                            <td>
                                {{ $row->nama_surat }}
                            </td>

                            <td>
                                {{ $row->kategori_surat }}
                            </td>

                            <td>

                                @if($row->status == 'draft')

                                    <span class="badge bg-secondary">

                                        Draft

                                    </span>

                                @elseif($row->status == 'disetujui')

                                    <span class="badge bg-success">

                                        Valid

                                    </span>

                                @else

                                    <span class="badge bg-danger">

                                        Ditolak

                                    </span>

                                @endif

                            </td>

                            <td>
                                <div class="d-flex gap-2">

                                    {{-- DETAIL --}}
                                    <a href="{{ route('guru.pengajuan-surat.show', $row->id) }}"
                                    class="btn btn-primary btn-sm rounded-3">
                                        Detail
                                    </a>

                                    {{-- EDIT --}}
                                    <a href="{{ route('guru.pengajuan-surat.edit', $row->id) }}"
                                    class="btn btn-warning btn-sm rounded-3">
                                        Edit
                                    </a>

                                    {{-- DELETE --}}
                                    <form action="{{ route('guru.pengajuan-surat.destroy', $row->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus ajuan ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-danger btn-sm rounded-3">

                                            Hapus

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5"
                                class="text-center text-muted py-4">

                                Belum ada pengajuan surat

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection