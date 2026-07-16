@extends('layouts.app')

@section('title', 'Mata Pelajaran')

@section('content')

<div class="container-fluid">

    {{-- ================= HEADER ================= --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

        <div class="card-body p-4 p-lg-5 text-white"
            style="background:linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);">

            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center">

                <div>

                    <h2 class="fw-bold mb-2">
                        Mata Pelajaran
                    </h2>

                    <p class="mb-0 opacity-75">
                        Daftar seluruh mata pelajaran yang digunakan pada sistem akademik sekolah.
                    </p>

                </div>

                <div class="mt-3 mt-lg-0">

                    <div class="bg-white rounded-4 px-4 py-3 text-center">

                        <h3 class="fw-bold text-primary mb-0">
                            {{ $mata_pelajaran->count() }}
                        </h3>

                        <small class="text-muted">
                            Mata Pelajaran
                        </small>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- ================= TABEL ================= --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white border-0 pt-4 px-4">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="fw-bold mb-1">

                        <i class="bi bi-book-half text-primary me-2"></i>

                        Daftar Mata Pelajaran

                    </h5>

                    <small class="text-muted">
                        Data mata pelajaran yang tersedia.
                    </small>

                </div>

            </div>

        </div>

        <div class="card-body pt-2">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>

                        <tr>

                            <th width="80">No</th>

                            <th width="180">Kode</th>

                            <th>Nama Mata Pelajaran</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($mata_pelajaran as $row)

                            <tr>

                                <td>

                                    <span class="number-badge">

                                        {{ $loop->iteration }}

                                    </span>

                                </td>

                                <td>

                                    @if($row->kode_mapel)

                                        <span class="badge bg-primary-subtle text-primary px-3 py-2">

                                            {{ $row->kode_mapel }}

                                        </span>

                                    @else

                                        <span class="text-muted">

                                            -

                                        </span>

                                    @endif

                                </td>

                                <td>

                                    <div class="fw-semibold text-dark">

                                        {{ $row->nama_mapel }}

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="3">

                                    <div class="empty-state">

                                        <i class="bi bi-journal-x display-4 text-secondary"></i>

                                        <h5 class="mt-3 mb-2">

                                            Belum Ada Mata Pelajaran

                                        </h5>

                                        <p class="text-muted mb-0">

                                            Data mata pelajaran belum tersedia.

                                        </p>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<style>

.table thead th{

    border:0;

    font-weight:700;

    color:#64748b;

    font-size:.85rem;

    letter-spacing:.5px;

    text-transform:uppercase;

    padding:16px;

}

.table tbody td{

    padding:18px 16px;

    vertical-align:middle;

}

.table-hover tbody tr{

    transition:.25s;

}

.table-hover tbody tr:hover{

    background:#f8fbff;

}

.number-badge{

    width:38px;

    height:38px;

    border-radius:50%;

    background:#eaf2ff;

    color:#2563eb;

    display:flex;

    align-items:center;

    justify-content:center;

    font-weight:700;

}

.empty-state{

    padding:60px 20px;

    text-align:center;

}

.card{

    transition:.25s;

}

.card:hover{

    box-shadow:0 15px 40px rgba(15,76,129,.08)!important;

}

</style>

@endsection