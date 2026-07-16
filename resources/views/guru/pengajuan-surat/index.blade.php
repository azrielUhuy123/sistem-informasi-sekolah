@extends('layouts.app')

@section('title', 'Pengajuan Surat')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2">Manajemen Nilai</h3>
                    <p class="mb-0 opacity-75">Kelola nilai siswa dalam setiap mata pelajaran</p>
                </div>
                <div class="badge bg-white text-primary px-3 py-2 fs-6">
                    <i class="bi bi-shield-check me-2"></i> Sistem Terhubung
                </div>
            </div>
        </div>
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

                <a href="{{ route('guru.pengajuan-surat.create') }}"
           class="btn btn-primary rounded-3">

            <i class="bi bi-plus-circle"></i>
            Buat Pengajuan

        </a>


            </table>

        </div>

    </div>

</div>

@endsection