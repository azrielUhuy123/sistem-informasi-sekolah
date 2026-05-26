@extends('layouts.app')

@section('title', 'Nomor Surat')

@section('content')

<div class="container-fluid">

    {{-- ALERT --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4"
             role="alert">

            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="fw-bold mb-1">
                Daftar Nomor Surat
            </h4>

            <p class="text-muted mb-0">
                Riwayat nomor surat siswa
            </p>

        </div>

        <form
            action="{{ route('siswa.nomor-surat.generate') }}"
            method="POST">

            @csrf

            <button
                class="btn btn-primary rounded-3">

                <i class="bi bi-plus-circle"></i>
                Tambah

            </button>

        </form>

    </div>

    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body">

            <table class="table align-middle">

                <thead class="table-light">

                    <tr>

                        <th>No</th>
                        <th>Nomor Surat</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Status</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($nomorSurat as $row)

                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>

                            <td>
                                {{ $row->nomor_surat }}
                            </td>

                            <td>
                                {{ $row->created_at->translatedFormat('d F Y') }}
                            </td>

                            <td>

                                <span class="badge bg-success">

                                    Digunakan

                                </span>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="4"
                                class="text-center text-muted py-4">

                                Belum ada nomor surat

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection