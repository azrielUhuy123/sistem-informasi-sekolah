@extends('layouts.app')

@section('title', 'Pengampu Mapel')

@section('content')

<div class="container-fluid">

    {{-- ================= HEADER ================= --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

        <div class="card-body p-4 p-lg-5 text-white"
            style="background:linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);">

            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center">

                <div>

                    <h2 class="fw-bold mb-2">

                        Pengampu Mata Pelajaran

                    </h2>

                    <p class="mb-0 opacity-75">

                        Mengatur guru pengampu mata pelajaran pada setiap kelas di tahun ajaran aktif.

                    </p>

                </div>

                <div class="mt-4 mt-lg-0">

                    <div class="bg-white rounded-4 px-4 py-3 text-center shadow-sm">

                        <h3 class="fw-bold text-primary mb-0">

                            {{ $pengampu->count() }}

                        </h3>

                        <small class="text-muted">

                            Total Penempatan

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

                Penempatan Pengampu

            </h5>

            <small class="text-muted">

                Pilih guru, mata pelajaran, lalu tentukan kelas yang diampu.

            </small>

        </div>

        <div class="card-body pt-3">

            <form
                action="{{ route('superadmin.pengampu-mapel.store') }}"
                method="POST">

                @csrf

                <div class="row">

                    {{-- Guru --}}
                    <div class="col-lg-6 mb-4">

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

                    {{-- Mapel --}}
                    <div class="col-lg-6 mb-4">

                        <label class="form-label fw-semibold">

                            Mata Pelajaran

                        </label>

                        <select
                            name="mata_pelajaran_id"
                            class="form-select"
                            required>

                            <option value="">
                                -- Pilih Mata Pelajaran --
                            </option>

                            @foreach($mapel as $m)

                                <option value="{{ $m->id }}">

                                    {{ $m->nama_mapel }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

                <hr>

                <label class="form-label fw-bold mb-3">

                    Pilih Kelas

                </label>

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <small class="text-muted">
                        Guru dapat ditempatkan pada lebih dari satu kelas.
                    </small>

                    <div>

                        <button
                            type="button"
                            class="btn btn-sm btn-outline-primary me-2"
                            id="checkAll">

                            Pilih Semua

                        </button>

                        <button
                            type="button"
                            class="btn btn-sm btn-outline-secondary"
                            id="uncheckAll">

                            Kosongkan

                        </button>

                    </div>

                </div>

                <div class="row g-3">

                    @foreach($kelas as $k)

                        <div class="col-lg-2 col-md-3 col-sm-4 col-6">

                            <label class="kelas-item">

                                <input
                                    type="checkbox"
                                    class="form-check-input kelas-checkbox"
                                    name="kelas_id[]"
                                    value="{{ $k->id }}">

                                <span class="kelas-badge">

                                    {{ $k->nama_kelas }}

                                </span>

                            </label>

                        </div>

                    @endforeach

                </div>

                <div class="mt-4">

                    <button class="btn btn-primary rounded-pill px-4">

                        <i class="bi bi-check-circle-fill me-2"></i>

                        Simpan Penempatan

                    </button>

                </div>

            </form>

        </div>

    </div>

    {{-- TABLE --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white border-0 pt-4 px-4">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="fw-bold mb-1">
                        <i class="bi bi-table text-primary me-2"></i>
                        Data Pengampu Mata Pelajaran
                    </h5>

                    <small class="text-muted">
                        Daftar guru beserta kelas dan mata pelajaran yang diampu.
                    </small>

                </div>

                <span class="badge bg-primary rounded-pill px-3 py-2">
                    {{ $pengampu->count() }} Data
                </span>

            </div>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>

                        <tr>

                            <th>Guru</th>
                            <th width="120">Kelas</th>
                            <th>Mata Pelajaran</th>
                            <th width="120" class="text-center">Aksi</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($pengampu as $p)

                            <tr>

                                <td>

                                    <div class="d-flex align-items-center">

                                        <div class="avatar-mini me-3">

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

                                    <span class="badge bg-success-subtle text-success px-3 py-2">

                                        {{ $p->kelas->nama_kelas }}

                                    </span>

                                </td>

                                <td>

                                    <span class="badge bg-primary-subtle text-primary border px-3 py-2">

                                        {{ $p->mapel->nama_mapel }}

                                    </span>

                                </td>

                                <td class="text-center">

                                    <form
                                        action="{{ route('superadmin.pengampu-mapel.destroy',$p->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Hapus data ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-danger rounded-pill btn-sm px-3">

                                            <i class="bi bi-trash3-fill me-1"></i>

                                            Hapus

                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="4">

                                    <div class="empty-state">

                                        <i class="bi bi-journal-x display-4 text-secondary"></i>

                                    <h5 class="mt-3">

                                        Belum Ada Pengampu Mapel

                                    </h5>

                                    <p class="text-muted mb-0">

                                        Silakan tambahkan data pengampu mata pelajaran.

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

.avatar-mini{
    width:48px;
    height:48px;
    border-radius:50%;
    background:linear-gradient(135deg,#2563eb,#0f4c81);
    color:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:700;
    font-size:1rem;
    box-shadow:0 8px 20px rgba(37,99,235,.25);
}

.table thead th{
    border:0;
    color:#64748b;
    text-transform:uppercase;
    font-size:.82rem;
    font-weight:700;
    padding:16px;
    letter-spacing:.5px;
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

.empty-state{
    text-align:center;
    padding:60px 20px;
}

.badge{
    font-weight:600;
    border-radius:999px;
}

.form-select,
.form-control{

    border-radius:12px;
    min-height:46px;
    border:1px solid #dbe4f0;

}

.form-select:focus,
.form-control:focus{

    border-color:#2563eb;
    box-shadow:0 0 0 .2rem rgba(37,99,235,.15);

}

.btn{

    border-radius:12px;

}

.class-item{

    transition:.2s;
    cursor:pointer;

}

.class-item:hover{

    border-color:#2563eb!important;
    background:#f8fbff;

}

</style>

<script>

document.getElementById('checkAll').onclick = function(){

    document.querySelectorAll('.kelas-checkbox').forEach(function(item){

        item.checked = true;

    });

};

document.getElementById('uncheckAll').onclick = function(){

    document.querySelectorAll('.kelas-checkbox').forEach(function(item){

        item.checked = false;

    });

};

</script>

@endsection