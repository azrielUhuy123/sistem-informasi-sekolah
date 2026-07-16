@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')

<div class="container-fluid">

    {{-- ================= HEADER ================= --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

        <div class="card-body p-4 p-lg-5 text-white"
            style="background:linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);">

            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center">

                <div>

                    <h2 class="fw-bold mb-2">
                        Data Siswa
                    </h2>

                    <p class="mb-0 opacity-75">
                        Monitoring seluruh data siswa berdasarkan angkatan, rombel dan status akademik.
                    </p>

                </div>

                <div class="mt-3 mt-lg-0">

                    <div class="bg-white rounded-4 px-4 py-3 text-center shadow-sm">

                        <h3 class="fw-bold text-primary mb-0">

                            {{ $siswa->total() }}

                        </h3>

                        <small class="text-muted">

                            Total Siswa

                        </small>

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- ================= FILTER ================= --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-header bg-white border-0 pt-4 px-4">

            <h5 class="fw-bold mb-1">

                <i class="bi bi-funnel-fill text-primary me-2"></i>

                Filter Data

            </h5>

            <small class="text-muted">

                Gunakan filter untuk mencari data siswa dengan lebih cepat.

            </small>

        </div>

        <div class="card-body">

            <form method="GET"
                  action="{{ route('superadmin.siswa.index') }}">

                <div class="row g-3">

                    {{-- ANGKATAN --}}
                    <div class="col-lg-2">

                        <label class="form-label fw-semibold">
                            Angkatan
                        </label>

                        <select
                            name="angkatan"
                            class="form-select rounded-3">

                            <option value="">
                                Semua
                            </option>

                            @foreach($angkatan as $a)

                                <option
                                    value="{{ $a->id }}"
                                    {{ request('angkatan')==$a->id?'selected':'' }}>

                                    {{ $a->tahun }}

                                </option>

                            @endforeach

                        </select>

                    </div>



                    {{-- GRUP --}}
                    <div class="col-lg-2">

                        <label class="form-label fw-semibold">
                            Grup
                        </label>

                        <select
                            name="kelas_grup"
                            class="form-select rounded-3">

                            <option value="">
                                Semua
                            </option>

                            @foreach($kelasGrup as $g)

                                <option
                                    value="{{ $g->id }}"
                                    {{ request('kelas_grup')==$g->id?'selected':'' }}>

                                    Kelas {{ $g->kelasMaster->tingkat }}

                                </option>

                            @endforeach

                        </select>

                    </div>



                    {{-- ROMBEL --}}
                    <div class="col-lg-2">

                        <label class="form-label fw-semibold">
                            Rombel
                        </label>

                        <select
                            name="kelas"
                            class="form-select rounded-3">

                            <option value="">
                                Semua
                            </option>

                            @foreach($kelas as $k)

                                <option
                                    value="{{ $k->id }}"
                                    {{ request('kelas')==$k->id?'selected':'' }}>

                                    {{ $k->nama_kelas }}

                                </option>

                            @endforeach

                        </select>

                    </div>



                    {{-- SEARCH --}}
                    <div class="col-lg-3">

                        <label class="form-label fw-semibold">
                            Nama / NIS
                        </label>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control rounded-3"
                            placeholder="Cari siswa...">

                    </div>



                    {{-- STATUS --}}
                    <div class="col-lg-2">

                        <label class="form-label fw-semibold">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select rounded-3">

                            <option value="">
                                Semua
                            </option>

                            <option value="aktif"
                                {{ request('status')=='aktif'?'selected':'' }}>

                                Aktif

                            </option>

                            <option value="nonaktif"
                                {{ request('status')=='nonaktif'?'selected':'' }}>

                                Nonaktif

                            </option>

                        </select>

                    </div>



                    <div class="col-lg-1 d-grid">

                        <label class="form-label">&nbsp;</label>

                        <button
                            class="btn btn-primary rounded-3">

                            <i class="bi bi-search"></i>

                        </button>

                    </div>



                    <div class="col-lg-2 d-grid">

                        <a
                            href="{{ route('superadmin.siswa.index') }}"
                            class="btn btn-outline-secondary rounded-3">

                            <i class="bi bi-arrow-clockwise me-1"></i>

                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>



    {{-- ================= TABLE ================= --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white border-0 pt-4 px-4">

            <h5 class="fw-bold mb-1">

                <i class="bi bi-mortarboard-fill text-primary me-2"></i>

                Data Seluruh Siswa

            </h5>

            <small class="text-muted">

                Informasi siswa aktif maupun nonaktif.

            </small>

        </div>

        <div class="card-body pt-2">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>

                        <tr>

                            <th>Siswa</th>

                            <th width="130">NIS</th>

                            <th width="130">NISN</th>

                            <th width="120">Rombel</th>

                            <th width="120">Angkatan</th>

                            <th width="120">Status</th>

                            <th width="120" class="text-center">

                                Aksi

                            </th>

                        </tr>

                    </thead>

                <tbody>

                    @forelse($siswa as $row)

                        <tr>

                            {{-- SISWA --}}
                            <td>

                                <div class="d-flex align-items-center">

                                    <div class="avatar-circle me-3">

                                        {{ strtoupper(substr($row->nama,0,1)) }}

                                    </div>

                                    <div>

                                        <div class="fw-semibold">

                                            {{ $row->nama }}

                                        </div>

                                        <small class="text-muted">

                                            {{ $row->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}

                                        </small>

                                    </div>

                                </div>

                            </td>

                            {{-- NIS --}}
                            <td>

                                <span class="fw-semibold">

                                    {{ $row->nis }}

                                </span>

                            </td>

                            {{-- NISN --}}
                            <td>

                                {{ $row->nisn ?? '-' }}

                            </td>

                            {{-- ROMBEL --}}
                            <td>

                                <span class="badge bg-primary-subtle text-primary px-3 py-2">

                                    {{ $row->penempatanAktif?->kelas?->nama_kelas ?? '-' }}

                                </span>

                            </td>

                            {{-- ANGKATAN --}}
                            <td>

                                {{ $row->penempatanAktif?->kelas?->kelasGrup?->angkatan?->tahun ?? '-' }}

                            </td>

                            {{-- STATUS --}}
                            <td>

                                @if($row->trashed())

                                    <span class="badge rounded-pill bg-secondary px-3 py-2">

                                        Nonaktif

                                    </span>

                                @else

                                    <span class="badge rounded-pill bg-success px-3 py-2">

                                        Aktif

                                    </span>

                                @endif

                            </td>

                            {{-- AKSI --}}
                            <td class="text-center">

                                <a href="{{ route('superadmin.siswa.show',$row->id) }}"
                                    class="btn btn-primary btn-sm rounded-pill px-3">

                                    <i class="bi bi-eye-fill me-1"></i>

                                    Detail

                                </a>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="7">

                                <div class="empty-state">

                                    <i class="bi bi-mortarboard display-4 text-secondary"></i>

                                    <h5 class="mt-3">

                                        Belum Ada Data Siswa

                                    </h5>

                                    <p class="text-muted mb-0">

                                        Data siswa belum tersedia.

                                    </p>

                                </div>

                            </td>

                        </tr>

                        @endforelse

                </tbody>

            </table>

            <div class="mt-3">

                {{ $siswa->links() }}

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

    color:white;

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

    font-size:.83rem;

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

    font-weight:500;

}

.empty-state{

    text-align:center;

    padding:60px 20px;

}

.form-control,
.form-select{

    border-radius:12px;

}

.btn{

    border-radius:12px;

}

.card-header{

    background:white;

}

</style>

@endsection