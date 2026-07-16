@extends('layouts.app')

@section('title', 'Wali Kelas')

@section('content')

<div class="container-fluid">

    {{-- ================= HEADER ================= --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

        <div class="card-body p-4 p-lg-5 text-white"
            style="background:linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);">

            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center">

                <div>

                    <h2 class="fw-bold mb-2">
                        Wali Kelas
                    </h2>

                    <p class="mb-0 opacity-75">
                        Kelola penugasan guru sebagai wali kelas pada setiap rombel aktif.
                    </p>

                </div>

                <div class="mt-3 mt-lg-0 d-flex gap-3">

                    <div class="bg-white rounded-4 px-4 py-3 text-center">

                        <h3 class="fw-bold text-primary mb-0">

                            {{ $waliKelas->count() }}

                        </h3>

                        <small class="text-muted">

                            Total Wali

                        </small>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- ================= TABLE ================= --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white border-0 pt-4 px-4">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="fw-bold mb-1">

                        <i class="bi bi-person-workspace text-primary me-2"></i>

                        Data Wali Kelas

                    </h5>

                    <small class="text-muted">

                        Daftar guru yang ditugaskan sebagai wali kelas.

                    </small>

                </div>

                <a href="{{ route('superadmin.wali-kelas.create') }}"
                    class="btn btn-primary rounded-pill px-4">

                    <i class="bi bi-plus-circle-fill me-2"></i>

                    Assign Wali Kelas

                </a>

            </div>

        </div>

        <div class="card-body pt-2">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>

                        <tr>

                            <th>Guru</th>

                            <th width="140">

                                Kelas

                            </th>

                            <th width="220">

                                Tahun Ajaran

                            </th>

                            <th width="120" class="text-center">

                                Aksi

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($waliKelas as $item)

                            <tr>

                                {{-- GURU --}}
                                <td>

                                    <div class="d-flex align-items-center">

                                        <div class="avatar-circle me-3">

                                            {{ strtoupper(substr($item->guru->pegawai->nama,0,1)) }}

                                        </div>

                                        <div>

                                            <div class="fw-semibold">

                                                {{ $item->guru->pegawai->nama }}

                                            </div>

                                            <small class="text-muted">

                                                {{ $item->guru->pegawai->nip ?? '-' }}

                                            </small>

                                        </div>

                                    </div>

                                </td>

                                {{-- KELAS --}}
                                <td>

                                    <span class="badge bg-success-subtle text-success px-3 py-2">

                                        {{ $item->kelas->nama_kelas }}

                                    </span>

                                </td>

                                {{-- TAHUN AJARAN --}}
                                <td>

                                    @if($item->tahunAjaran)

                                        <span class="badge bg-primary-subtle text-primary border px-3 py-2">

                                            <i class="bi bi-calendar3 me-1"></i>

                                            {{ $item->tahunAjaran->tahun }}

                                            -

                                            {{ ucfirst($item->tahunAjaran->semester) }}

                                        </span>

                                    @else

                                        <span class="text-muted">

                                            -

                                        </span>

                                    @endif

                                </td>

                                {{-- AKSI --}}
                                <td class="text-center">

                                    <form
                                        action="{{ route('superadmin.wali-kelas.destroy',$item->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Hapus wali kelas ini?')">

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

                                <td colspan="4">

                                    <div class="empty-state">

                                        <i class="bi bi-person-x display-4 text-secondary"></i>

                                        <h5 class="mt-3">

                                            Belum Ada Wali Kelas

                                        </h5>

                                        <p class="text-muted mb-0">

                                            Belum ada guru yang ditetapkan sebagai wali kelas.

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