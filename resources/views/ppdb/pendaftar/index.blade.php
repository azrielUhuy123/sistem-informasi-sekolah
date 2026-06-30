@extends('layouts.app')

@section('title', 'Data Pendaftar PPDB')

@section('content')

<div class="container-fluid">
{{-- HEADER --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                    <div>
                        <h3 class="fw-bold mb-2">Daftar Pendaftar PPDB</h3>
                        <p class="mb-0 opacity-75">Kelola data pendaftar PPDB</p>
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

    <div class="card border-0 shadow-sm">

        {{-- TOP BAR --}}

        <div
            class="card-header bg-light border-0 p-4"
        >

            <div
                class="d-flex justify-content-between align-items-center flex-wrap gap-3"
            >

                <div>

                    <h5 class="fw-semibold mb-1">
                        Daftar Pendaftar PPDB
                    </h5>

                    <span class="text-muted small">
                        Total:
                        {{ $data->count() }}
                        pendaftar PPDB   
                    </span>

                </div>

                <div style="max-width: 280px; width:100%;">

                    <input
                        type="text"
                        id="searchInput"
                        class="form-control rounded-3"
                        placeholder="Cari pendaftar..."
                    >

                </div>

            </div>

        </div>


        <div class="card-body">

            <table class="table align-middle">

                <thead>

                    <tr>
                        <th>No</th>
                        <th>No Pendaftaran</th>
                        <th>Nama</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>

                </thead>

                <tbody id="tablePPDB">

                    @foreach($data as $item)

                    <tr>

                        <td>{{ $loop->iteration }}</td>

                        <td>{{ $item->no_pendaftaran }}</td>

                        <td>{{ $item->nama }}</td>

                        <td>

                           <span class="badge bg-light text-dark border">

                                {{ strtoupper($item->status) }}

                            </span>

                        </td>

                        <td>

                            <a
                                    href="{{ route('tu.ppdb.pendaftar.show',$item) }}"
                                    class="btn btn-light border rounded-3 btn-sm"
                                > <i class="bi bi-eye"></i>
                            </a>

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

            {{ $data->links() }}

        </div>

    </div>

</div>

@endsection