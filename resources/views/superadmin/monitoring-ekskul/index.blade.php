@extends('layouts.app')

@section('title', 'Monitoring Ekskul')

@section('content')

<div class="container-fluid py-3">

    {{-- ================= HERO ================= --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

        <div
            class="card-body p-4 p-lg-5 text-white"
            style="background:linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);"
        >

            <div class="d-flex justify-content-between align-items-center flex-wrap">

                <div>

                    <h2 class="fw-bold mb-2">

                        Monitoring Ekskul

                    </h2>

                    <p class="mb-0 opacity-75">

                        Pantau seluruh kegiatan ekstrakurikuler, pembina, dan proses monitoring secara terpusat.

                    </p>

                </div>

                <div class="mt-4 mt-lg-0">

                    <div class="bg-white rounded-4 px-4 py-3 text-center">

                        <h2 class="fw-bold text-primary mb-0">

                            {{ $ekskul->count() }}

                        </h2>

                        <small class="text-muted">

                            Total Ekskul

                        </small>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- ================= TABLE CARD ================= --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex justify-content-between align-items-center flex-wrap">

                <div>

                    <h5 class="fw-bold mb-1">

                        Daftar Ekstrakurikuler

                    </h5>

                    <small class="text-muted">

                        Pilih ekstrakurikuler untuk melihat hasil monitoring.

                    </small>

                </div>

            </div>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>

                        <tr>

                            <th width="80" class="text-center">

                                No

                            </th>

                            <th>

                                Ekstrakurikuler

                            </th>

                            <th>

                                Pembina

                            </th>

                            <th width="170" class="text-center">

                                Aksi

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($ekskul as $e)

                            <tr>

                                <td class="text-center">

                                    <span class="badge bg-light text-dark rounded-pill px-3 py-2">

                                        {{ $loop->iteration }}

                                    </span>

                                </td>

                                <td>

                                    <div class="d-flex align-items-center">

                                        <div class="icon-circle me-3">

                                            <i class="bi bi-trophy-fill"></i>

                                        </div>

                                        <div>

                                            <div class="fw-bold">

                                                {{ $e->nama }}

                                            </div>

                                            <small class="text-muted">

                                                Ekstrakurikuler Sekolah

                                            </small>

                                        </div>

                                    </div>

                                </td>

                                <td>

                                    @if($e->pembina)

                                        <div class="d-flex align-items-center">

                                            <div class="avatar-circle me-3">

                                                <i class="bi bi-person-fill"></i>

                                            </div>

                                            <div>

                                                <div class="fw-semibold">

                                                    {{ $e->pembina->guru->pegawai->nama }}

                                                </div>

                                                <small class="text-muted">

                                                    Pembina Ekskul

                                                </small>

                                            </div>

                                        </div>

                                    @else

                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-2">

                                            Belum Ada Pembina

                                        </span>

                                    @endif

                                </td>

                                <td class="text-center">

                                    <a
                                        href="{{ route('superadmin.monitoring-ekskul.show',$e->id) }}"
                                        class="btn btn-primary rounded-pill px-4"
                                    >

                                        <i class="bi bi-bar-chart-line-fill me-2"></i>

                                        Monitoring

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="4">

                                    <div class="text-center py-5">

                                        <i class="bi bi-inbox display-4 text-secondary"></i>

                                        <h5 class="mt-3">

                                            Belum Ada Data

                                        </h5>

                                        <p class="text-muted mb-0">

                                            Data ekstrakurikuler belum tersedia.

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

.card{

    transition:.25s;

}

.card:hover{

    box-shadow:0 16px 40px rgba(37,99,235,.08)!important;

}

.table thead th{

    background:#f8fafc;

    color:#64748b;

    text-transform:uppercase;

    letter-spacing:.5px;

    font-size:.82rem;

    font-weight:700;

    border-bottom:0;

    padding:18px;

}

.table tbody td{

    padding:18px;

    vertical-align:middle;

    border-color:#eef2f7;

}

.table-hover tbody tr{

    transition:.2s;

}

.table-hover tbody tr:hover{

    background:#f8fbff;

}

.icon-circle{

    width:52px;

    height:52px;

    border-radius:16px;

    background:linear-gradient(135deg,#2563eb,#0f4c81);

    display:flex;

    align-items:center;

    justify-content:center;

    color:#fff;

    font-size:1.2rem;

}

.avatar-circle{

    width:46px;

    height:46px;

    border-radius:50%;

    background:#eff6ff;

    color:#2563eb;

    display:flex;

    align-items:center;

    justify-content:center;

    font-size:1.05rem;

}

.btn{

    transition:.2s;

}

.btn:hover{

    transform:translateY(-2px);

}

.btn-primary{

    background:linear-gradient(135deg,#2563eb,#0f4c81);

    border:none;

}

.badge{

    font-weight:600;

    letter-spacing:.3px;

}

@media(max-width:768px){

    .table{

        font-size:.92rem;

    }

}

</style>

@endsection