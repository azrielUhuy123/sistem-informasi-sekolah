@extends('layouts.app')

@section('title', 'Perkembangan Siswa')

@section('content')

@php

    $totalSiswa = $penempatan->count();

    $lengkap = $penempatan->where('status_perkembangan','Lengkap')->count();

    $belumLengkap = $penempatan->where('status_perkembangan','Belum Lengkap')->count();

    $belumAda = $penempatan->where('status_perkembangan',null)->count();

@endphp

<div class="container-fluid py-4">

    {{-- HERO --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

        <div class="card-body p-4 p-lg-5 text-white"
             style="background:linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);">

            <div class="row align-items-center">

                <div class="col-lg-8">

                    <h2 class="fw-bold mb-2">

                        Perkembangan Siswa

                    </h2>

                    <p class="mb-0 opacity-75">

                        Kelola perkembangan karakter, sosial, disiplin, kebersihan, dan catatan umum setiap siswa.

                    </p>

                </div>

                <div class="col-lg-4 mt-4 mt-lg-0">

                    <div class="row g-3">

                        <div class="col-6">

                            <div class="bg-white rounded-4 text-center p-3">

                                <h4 class="fw-bold text-primary mb-1">

                                    {{ $totalSiswa }}

                                </h4>

                                <small class="text-muted">

                                    Siswa

                                </small>

                            </div>

                        </div>

                        <div class="col-6">

                            <div class="bg-white rounded-4 text-center p-3">

                                <h4 class="fw-bold text-success mb-1">

                                    {{ $lengkap }}

                                </h4>

                                <small class="text-muted">

                                    Lengkap

                                </small>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- ALERT --}}
    @if(session('success'))

        <div class="alert alert-success border-0 shadow-sm rounded-4">

            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

        </div>

    @endif

    @if(session('error'))

        <div class="alert alert-danger border-0 shadow-sm rounded-4">

            <i class="bi bi-exclamation-triangle-fill me-2"></i>

            {{ session('error') }}

        </div>

    @endif

    {{-- SUMMARY --}}
    <div class="row g-3 mb-4">

        <div class="col-lg-4">

            <div class="summary-card">

                <div class="summary-icon bg-success-subtle text-success">

                    <i class="bi bi-check-circle-fill"></i>

                </div>

                <h3>{{ $lengkap }}</h3>

                <small class="text-muted">

                    Lengkap

                </small>

            </div>

        </div>

        <div class="col-lg-4">

            <div class="summary-card">

                <div class="summary-icon bg-warning-subtle text-warning">

                    <i class="bi bi-pencil-square"></i>

                </div>

                <h3>{{ $belumLengkap }}</h3>

                <small class="text-muted">

                    Belum Lengkap

                </small>

            </div>

        </div>

        <div class="col-lg-4">

            <div class="summary-card">

                <div class="summary-icon bg-secondary-subtle text-secondary">

                    <i class="bi bi-dash-circle-fill"></i>

                </div>

                <h3>{{ $belumAda }}</h3>

                <small class="text-muted">

                    Belum Ada

                </small>

            </div>

        </div>

    </div>

    {{-- FILTER --}}
    @if(isset($kelasPengampu) && $kelasPengampu->count())

        <div class="card border-0 shadow-sm rounded-4 mb-4">

            <div class="card-header bg-white border-0 py-3">

                <h5 class="fw-bold mb-0">

                    Filter Kelas

                </h5>

            </div>

            <div class="card-body">

                <form method="GET">

                    <select
                        name="kelas_id"
                        class="form-select rounded-3"
                        onchange="this.form.submit()">

                        @foreach($kelasPengampu as $item)

                            <option
                                value="{{ $item->kelas_id }}"
                                {{ request('kelas_id',$kelas->id)==$item->kelas_id ? 'selected' : '' }}>

                                {{ $item->kelas->nama_kelas_label }}

                            </option>

                        @endforeach

                    </select>

                </form>

            </div>

        </div>

    @endif

    {{-- TABLE --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="fw-bold mb-1">

                        Daftar Perkembangan Siswa

                    </h5>

                    <small class="text-muted">

                        Klik tombol <strong>Isi / Edit</strong> untuk memperbarui perkembangan siswa.

                    </small>

                </div>

                <span class="badge bg-primary rounded-pill px-3 py-2">

                    {{ $totalSiswa }} Siswa

                </span>

            </div>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>

                        <tr>

                            <th width="70">No</th>

                            <th>Nama Siswa</th>

                            <th width="200">Status</th>

                            <th width="170" class="text-center">

                                Aksi

                            </th>

                        </tr>

                    </thead>

                    <tbody>

    @forelse($penempatan as $i => $p)

        <tr>

            <td>

                <span class="badge bg-light text-dark rounded-pill px-3 py-2">

                    {{ $i + 1 }}

                </span>

            </td>

            <td>

                <div class="d-flex align-items-center">

                    <div class="student-avatar me-3">

                        {{ strtoupper(substr($p->siswa->nama,0,1)) }}

                    </div>

                    <div>

                        <div class="fw-bold">

                            {{ $p->siswa->nama }}

                        </div>

                        <small class="text-muted">

                            {{ $p->siswa->nis }}

                        </small>

                    </div>

                </div>

            </td>

            <td>

                @if($p->status_perkembangan == 'Lengkap')

                    <span class="badge bg-success rounded-pill px-3 py-2">

                        <i class="bi bi-check-circle-fill me-1"></i>

                        Lengkap

                    </span>

                @elseif($p->status_perkembangan == 'Belum Lengkap')

                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2">

                        <i class="bi bi-pencil-square me-1"></i>

                        Belum Lengkap

                    </span>

                @else

                    <span class="badge bg-secondary rounded-pill px-3 py-2">

                        <i class="bi bi-dash-circle-fill me-1"></i>

                        Belum Ada

                    </span>

                @endif

            </td>

            <td class="text-center">

                <button
                    class="btn btn-primary btn-sm rounded-pill px-3"
                    data-bs-toggle="modal"
                    data-bs-target="#modal-{{ $p->siswa->id }}">

                    <i class="bi bi-pencil-square me-1"></i>

                    Isi / Edit

                </button>

            </td>

        </tr>

        {{-- MODAL --}}
        <div
            class="modal fade"
            id="modal-{{ $p->siswa->id }}"
            tabindex="-1">

            <div class="modal-dialog modal-xl modal-dialog-scrollable">

                <div class="modal-content border-0 rounded-4">

                    <form
                        action="{{ route('guru.perkembangan.store') }}"
                        method="POST">

                        @csrf

                        <div class="modal-header bg-primary text-white border-0">

                            <div>

                                <h5 class="modal-title fw-bold">

                                    {{ $p->siswa->nama }}

                                </h5>

                                <small class="opacity-75">

                                    Form Perkembangan Siswa

                                </small>

                            </div>

                            <button
                                type="button"
                                class="btn-close btn-close-white"
                                data-bs-dismiss="modal">
                            </button>

                        </div>

                        <div class="modal-body">

                            <div class="row g-4">

                                <div class="col-md-6">

                                    <label class="form-label fw-semibold">

                                        Karakter

                                    </label>

                                    <textarea
                                        name="perkembangan[{{ $p->siswa->id }}][karakter]"
                                        class="form-control rounded-3"
                                        rows="4">{{ $p->perkembangan->karakter ?? '' }}</textarea>

                                </div>

                                <div class="col-md-6">

                                    <label class="form-label fw-semibold">

                                        Sosial

                                    </label>

                                    <textarea
                                        name="perkembangan[{{ $p->siswa->id }}][sosial]"
                                        class="form-control rounded-3"
                                        rows="4">{{ $p->perkembangan->sosial ?? '' }}</textarea>

                                </div>

                                <div class="col-md-6">

                                    <label class="form-label fw-semibold">

                                        Disiplin

                                    </label>

                                    <textarea
                                        name="perkembangan[{{ $p->siswa->id }}][disiplin]"
                                        class="form-control rounded-3"
                                        rows="4">{{ $p->perkembangan->disiplin ?? '' }}</textarea>

                                </div>

                                <div class="col-md-6">

                                    <label class="form-label fw-semibold">

                                        Kebersihan

                                    </label>

                                    <textarea
                                        name="perkembangan[{{ $p->siswa->id }}][kebersihan]"
                                        class="form-control rounded-3"
                                        rows="4">{{ $p->perkembangan->kebersihan ?? '' }}</textarea>

                                </div>

                                <div class="col-12">

                                    <label class="form-label fw-semibold">

                                        Catatan Umum

                                    </label>

                                    <textarea
                                        name="perkembangan[{{ $p->siswa->id }}][catatan]"
                                        class="form-control rounded-3"
                                        rows="5">{{ $p->perkembangan->catatan ?? '' }}</textarea>

                                </div>

                            </div>

                        </div>

                        <div class="modal-footer border-0">

                            <button
                                type="button"
                                class="btn btn-light rounded-pill px-4"
                                data-bs-dismiss="modal">

                                Tutup

                            </button>

                            <button
                                class="btn btn-success rounded-pill px-4">

                                <i class="bi bi-check-circle-fill me-2"></i>

                                Simpan Perkembangan

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    @empty

        <tr>

            <td colspan="4">

                <div class="text-center py-5">

                    <i class="bi bi-people display-3 text-secondary"></i>

                    <h5 class="fw-bold mt-3">

                        Belum Ada Data Siswa

                    </h5>

                    <p class="text-muted mb-0">

                        Tidak ada data siswa yang dapat ditampilkan.

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

<style>

.card{

    transition:.25s;

}

.card:hover{

    box-shadow:0 18px 45px rgba(37,99,235,.08)!important;

}

.summary-card{

    background:#fff;

    border-radius:20px;

    padding:24px;

    text-align:center;

    box-shadow:0 .125rem .5rem rgba(0,0,0,.05);

    transition:.25s;

    height:100%;

}

.summary-card:hover{

    transform:translateY(-4px);

}

.summary-icon{

    width:60px;

    height:60px;

    border-radius:18px;

    display:flex;

    align-items:center;

    justify-content:center;

    margin:0 auto 16px;

    font-size:1.35rem;

}

.student-avatar{

    width:46px;

    height:46px;

    border-radius:50%;

    background:linear-gradient(135deg,#2563eb,#0f4c81);

    color:#fff;

    display:flex;

    align-items:center;

    justify-content:center;

    font-weight:700;

    flex-shrink:0;

}

.table thead th{

    background:#f8fafc;

    border:0;

    color:#64748b;

    font-size:.82rem;

    text-transform:uppercase;

    letter-spacing:.5px;

    padding:18px;

}

.table tbody td{

    padding:18px;

    border-color:#eef2f7;

    vertical-align:middle;

}

.table-hover tbody tr:hover{

    background:#f8fbff;

}

.modal-content{

    overflow:hidden;

}

.form-control{

    border-radius:14px;

}

.form-control:focus{

    border-color:#2563eb;

    box-shadow:0 0 0 .2rem rgba(37,99,235,.15);

}

.btn{

    font-weight:600;

    transition:.2s;

}

.btn:hover{

    transform:translateY(-2px);

}

</style>

@endsection