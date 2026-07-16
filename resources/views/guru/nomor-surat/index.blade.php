@extends('layouts.app')

@section('title', 'Nomor Surat')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2">Manajemen Nomor Surat</h3>
                    <p class="mb-0 opacity-75">Kelola nomor surat resmi sekolah</p>
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

        {{-- BUTTON GENERATE NOMOR --}}
        <form
            action="{{ route('guru.nomor-surat.generate') }}"
            method="POST"
        >

            @csrf

            <button
                type="submit"
                class="btn btn-primary rounded-3"
            >

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

                            <td colspan="5"
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