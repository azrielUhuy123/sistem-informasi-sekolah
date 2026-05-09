@extends('layouts.app')

@section('title', 'Validasi Surat')

@section('content')

<div class="container-fluid">

    <div class="mb-4">

        <h4 class="fw-bold mb-1">
            Validasi Pengajuan Surat
        </h4>

        <p class="text-muted mb-0">
            Validasi surat keluar dari TU / Guru
        </p>

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

                                @if($row->status == 'pending')

                                    <span class="badge bg-warning text-dark">

                                        Pending

                                    </span>

                                @elseif($row->status == 'disetujui')

                                    <span class="badge bg-success">

                                        Disetujui

                                    </span>

                                @else

                                    <span class="badge bg-danger">

                                        Ditolak

                                    </span>

                                @endif

                            </td>

                            <td>

                                <a href="{{ route('superadmin.pengajuan-surat.show', $row->id) }}"
                                   class="btn btn-primary btn-sm rounded-3">

                                    Detail

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5"
                                class="text-center text-muted py-4">

                                Belum ada pengajuan

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection