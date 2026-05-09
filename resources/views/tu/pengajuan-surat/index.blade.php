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
                Kelola pengajuan surat keluar sekolah
            </p>

        </div>

        <a href="{{ route('tu.pengajuan-surat.create') }}"
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
                        <th>Nama Surat</th>
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

                                <span class="badge bg-warning text-dark">

                                    {{ ucfirst($row->status) }}

                                </span>

                            </td>

                            <td>

                                <a href="{{ route('tu.pengajuan-surat.show', $row->id) }}"
                                    class="btn btn-sm btn-primary rounded-3">

                                        Detail

                                    </a>

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