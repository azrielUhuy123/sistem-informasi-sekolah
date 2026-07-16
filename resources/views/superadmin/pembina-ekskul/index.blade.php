@extends('layouts.app')

@section('title', 'Pembina Ekskul')

@section('content')

<div class="container-fluid">

    {{-- ================= HEADER ================= --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

        <div class="card-body p-4 p-lg-5 text-white"
            style="background:linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);">

            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center">

                <div>

                    <h2 class="fw-bold mb-2">

                        Pembina Ekstrakurikuler

                    </h2>

                    <p class="mb-0 opacity-75">

                        Kelola penugasan guru sebagai pembina ekstrakurikuler sekolah.

                    </p>

                </div>

                <div class="mt-3 mt-lg-0">

                    <div class="bg-white rounded-4 px-4 py-3 text-center">

                        <h3 class="fw-bold text-primary mb-0">

                            {{ $pembina->count() }}

                        </h3>

                        <small class="text-muted">

                            Total Pembina

                        </small>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- ================= FORM ================= --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-header bg-white border-0 pt-4 px-4">

            <h5 class="fw-bold mb-1">

                <i class="bi bi-person-plus-fill text-primary me-2"></i>

                Penugasan Pembina Ekskul

            </h5>

            <small class="text-muted">

                Pilih guru dan ekstrakurikuler yang akan dibina.

            </small>

        </div>

        <div class="card-body p-4">

            <form
                action="{{ route('superadmin.pembina-ekskul.store') }}"
                method="POST">

                @csrf

                <div class="row g-4">

                    <div class="col-lg-5">

                        <label class="form-label fw-semibold">

                            Guru

                        </label>

                        <select
                            name="guru_id"
                            class="form-select"
                            required>

                            <option value="">

                                -- Pilih Guru --

                            </option>

                            @foreach($guru as $g)

                                <option value="{{ $g->id }}">

                                    {{ $g->nama }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-lg-5">

                        <label class="form-label fw-semibold">

                            Ekstrakurikuler

                        </label>

                        <select
                            name="ekskul_id"
                            class="form-select"
                            required>

                            <option value="">

                                -- Pilih Ekskul --

                            </option>

                            @foreach($ekskul as $e)

                                <option value="{{ $e->id }}">

                                    {{ $e->nama }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-lg-2 d-grid">

                        <label class="form-label opacity-0">

                            Simpan

                        </label>

                        <button class="btn btn-primary rounded-pill">

                            <i class="bi bi-check-circle-fill me-2"></i>

                            Simpan

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

    {{-- ================= TABLE ================= --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white border-0 pt-4 px-4">

            <div>

                <h5 class="fw-bold mb-1">

                    <i class="bi bi-people-fill text-primary me-2"></i>

                    Data Pembina Ekstrakurikuler

                </h5>

                <small class="text-muted">

                    Daftar guru yang menjadi pembina ekstrakurikuler.

                </small>

            </div>

        </div>

        <div class="card-body pt-2">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>

                        <tr>

                            <th>Guru</th>
                            <th>Ekstrakurikuler</th>
                            <th width="120" class="text-center">Aksi</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($pembina as $p)

                            <tr>

                                <td>

                                    <div class="d-flex align-items-center">

                                        <div class="avatar-circle me-3">

                                            {{ strtoupper(substr($p->guru->pegawai->nama,0,1)) }}

                                        </div>

                                        <div>

                                            <div class="fw-semibold">

                                                {{ $p->guru->pegawai->nama }}

                                            </div>

                                            <small class="text-muted">

                                                {{ $p->guru->pegawai->nip ?? '-' }}

                                            </small>

                                        </div>

                                    </div>

                                </td>

                                <td>

                                    <span class="badge bg-primary-subtle text-primary border px-3 py-2">

                                        <i class="bi bi-trophy-fill me-1"></i>

                                        {{ $p->ekskul->nama }}

                                    </span>

                                </td>

                                <td class="text-center">

                                    <form
                                        action="{{ route('superadmin.pembina-ekskul.destroy',$p->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Hapus pembina ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            class="btn btn-danger rounded-pill btn-sm px-3">

                                            <i class="bi bi-trash3-fill me-1"></i>

                                            Hapus

                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="3">

                                    <div class="empty-state">

                                        <i class="bi bi-person-x display-4 text-secondary"></i>

                                        <h5 class="mt-3">

                                            Belum Ada Pembina Ekskul

                                        </h5>

                                        <p class="text-muted mb-0">

                                            Belum ada guru yang ditugaskan sebagai pembina ekstrakurikuler.

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

.avatar-circle{

    width:52px;
    height:52px;
    border-radius:50%;
    background:linear-gradient(135deg,#2563eb,#0f4c81);
    color:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:700;
    font-size:1.1rem;
    box-shadow:0 6px 18px rgba(37,99,235,.25);

}

.table thead th{

    border:0;
    color:#64748b;
    text-transform:uppercase;
    font-size:.82rem;
    font-weight:700;
    letter-spacing:.5px;
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

.card{

    transition:.25s;

}

.card:hover{

    box-shadow:0 15px 40px rgba(15,76,129,.08)!important;

}

.badge{

    font-weight:600;

}

.empty-state{

    text-align:center;
    padding:60px 20px;

}

.form-select{

    border-radius:12px;
    min-height:48px;
    border:1px solid #dbe4f0;

}

.form-select:focus{

    border-color:#2563eb;
    box-shadow:0 0 0 .2rem rgba(37,99,235,.15);

}

.btn-primary{

    border:none;
    background:linear-gradient(135deg,#2563eb,#0f4c81);

}

.btn-primary:hover{

    background:linear-gradient(135deg,#1d4ed8,#1e40af);

}

.btn-danger{

    transition:.2s;

}

.btn-danger:hover{

    transform:translateY(-2px);

}

</style>

@endsection