@extends('layouts.app')

@section('title','Detail Siswa')

@section('content')

<div class="container-fluid">

    {{-- ================= HEADER ================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">

                Detail Siswa

            </h2>

            <p class="text-muted mb-0">

                Informasi lengkap siswa.

            </p>

        </div>

        <a href="{{ route('superadmin.siswa.index') }}"
           class="btn btn-outline-secondary rounded-pill px-4">

            <i class="bi bi-arrow-left me-2"></i>

            Kembali

        </a>

    </div>

    {{-- ================= HERO ================= --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

        <div class="card-body p-5">

            <div class="row align-items-center">

                {{-- FOTO --}}
                <div class="col-lg-4 text-center">

                    @if($siswa->foto)

                        <img
                            src="{{ asset('foto-siswa/'.$siswa->foto) }}"
                            class="profile-avatar">

                    @else

                        <div class="avatar-circle mx-auto">

                            {{ strtoupper(substr($siswa->nama,0,2)) }}

                        </div>

                    @endif

                    <div class="mt-4">

                        @if($siswa->deleted_at)

                            <span class="badge rounded-pill bg-secondary px-4 py-2">

                                <i class="bi bi-person-x-fill me-1"></i>

                                Nonaktif

                            </span>

                        @else

                            <span class="badge rounded-pill bg-success px-4 py-2">

                                <i class="bi bi-check-circle-fill me-1"></i>

                                Aktif

                            </span>

                        @endif

                    </div>

                </div>

                {{-- IDENTITAS --}}
                <div class="col-lg-8">

                    <h2 class="fw-bold mb-1">

                        {{ $siswa->nama }}

                    </h2>

                    <p class="text-muted mb-4">

                        Siswa dengan NIS {{ $siswa->nis }} dan NISN {{ $siswa->nisn ?? '-' }}.

                    </p>

                    <div class="row g-3">

                        <div class="col-md-6">

                            <div class="info-box">

                                <small>NIS</small>

                                <h5>

                                    {{ $siswa->nis }}

                                </h5>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="info-box">

                                <small>NISN</small>

                                <h5>

                                    {{ $siswa->nisn ?? '-' }}

                                </h5>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="info-box">

                                <small>Jenis Kelamin</small>

                                <h5>

                                    {{ $siswa->jenis_kelamin == 'L'
                                        ? 'Laki-laki'
                                        : 'Perempuan' }}

                                </h5>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="info-box">

                                <small>Agama</small>

                                <h5>

                                    {{ $siswa->ppdb?->agama ?? '-' }}

                                </h5>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="info-box">

                                <small>Tempat, Tanggal Lahir</small>

                                <h5>

                                    {{ $siswa->ppdb?->tempat_lahir ?? '-' }},
                                    {{ $siswa->ppdb?->tanggal_lahir
                                        ? \Carbon\Carbon::parse($siswa->ppdb->tanggal_lahir)->translatedFormat('d F Y')
                                        : '-' }}

                                </h5>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="info-box">

                                <small>Kelas / Rombel</small>

                                <h5>

                                    {{ $siswa->penempatanAktif?->kelas?->nama_kelas_label ?? '-' }}

                                </h5>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- ================= DETAIL ================= --}}
<div class="row">

    {{-- BIODATA --}}
    <div class="col-lg-6 mb-4">

        <div class="card border-0 shadow-sm rounded-4 h-100">

            <div class="card-header bg-white border-0 pt-4 px-4">

                <h5 class="fw-bold mb-1">

                    <i class="bi bi-person-vcard-fill text-primary me-2"></i>

                    Biodata Siswa

                </h5>

                <small class="text-muted">

                    Informasi identitas siswa.

                </small>

            </div>

            <div class="card-body pt-2">

                <table class="table table-borderless align-middle mb-0">

                    <tr>

                        <th width="180">

                            Nama Lengkap

                        </th>

                        <td>

                            {{ $siswa->nama }}

                        </td>

                    </tr>

                    <tr>

                        <th>

                            NIS

                        </th>

                        <td>

                            {{ $siswa->nis }}

                        </td>

                    </tr>

                    <tr>

                        <th>

                            NISN

                        </th>

                        <td>

                            {{ $siswa->nisn ?? '-' }}

                        </td>

                    </tr>

                    <tr>

                        <th>

                            Jenis Kelamin

                        </th>

                        <td>

                            {{ $siswa->jenis_kelamin == 'L'
                                ? 'Laki-laki'
                                : 'Perempuan' }}

                        </td>

                    </tr>

                    <tr>

                        <th>

                            Agama

                        </th>

                        <td>

                            {{ $siswa->ppdb?->agama ?? '-' }}

                        </td>

                    </tr>

                    <tr>

                        <th>

                            Tempat, Tanggal Lahir

                        </th>

                        <td>

                            {{ $siswa->ppdb?->tempat_lahir ?? '-' }},
                            {{ $siswa->ppdb?->tanggal_lahir
                                ? \Carbon\Carbon::parse($siswa->ppdb->tanggal_lahir)->translatedFormat('d F Y')
                                : '-' }}

                        </td>

                    </tr>

                </table>

            </div>

        </div>

    </div>

    {{-- AKADEMIK --}}
    <div class="col-lg-6 mb-4">

        <div class="card border-0 shadow-sm rounded-4 h-100">

            <div class="card-header bg-white border-0 pt-4 px-4">

                <h5 class="fw-bold mb-1">

                    <i class="bi bi-mortarboard-fill text-success me-2"></i>

                    Informasi Akademik

                </h5>

                <small class="text-muted">

                    Status akademik siswa.

                </small>

            </div>

            <div class="card-body pt-2">

                <table class="table table-borderless align-middle mb-0">

                    <tr>

                        <th width="180">

                            Kelas / Rombel

                        </th>

                        <td>

                            <span class="badge bg-primary px-3 py-2">

                                {{ $siswa->penempatanAktif?->kelas?->nama_kelas_label ?? '-' }}

                            </span>

                        </td>

                    </tr>

                    <tr>

                        <th>

                            Angkatan

                        </th>

                        <td>

                            {{ $siswa->penempatanAktif?->kelas?->kelasGrup?->angkatan?->tahun ?? '-' }}

                        </td>

                    </tr>

                    <tr>

                        <th>

                            Tahun Ajaran

                        </th>

                        <td>

                            {{ current_tahun()?->tahun ?? '-' }}

                        </td>

                    </tr>

                    <tr>

                        <th>

                            Status

                        </th>

                        <td>

                            @if($siswa->deleted_at)

                                <span class="badge bg-secondary px-3 py-2">

                                    Nonaktif

                                </span>

                            @else

                                <span class="badge bg-success px-3 py-2">

                                    Aktif

                                </span>

                            @endif

                        </td>

                    </tr>

                    <tr>

                        <th>

                            Nama Wali

                        </th>

                        <td>

                            {{ $siswa->nama_wali ?? '-' }}

                        </td>

                    </tr>

                    <tr>

                        <th>

                            Telepon Wali

                        </th>

                        <td>

                            {{ $siswa->telepon_wali ?? '-' }}

                        </td>

                    </tr>

                </table>

            </div>

        </div>

    </div>

</div>

{{-- ================= ALAMAT ================= --}}
<div class="card border-0 shadow-sm rounded-4 mb-4">

    <div class="card-header bg-white border-0 pt-4 px-4">

        <h5 class="fw-bold mb-1">

            <i class="bi bi-geo-alt-fill text-danger me-2"></i>

            Alamat Siswa

        </h5>

        <small class="text-muted">

            Informasi domisili siswa.

        </small>

    </div>

    <div class="card-body pt-2">

        <table class="table table-borderless align-middle mb-0">

            <tr>

                <th width="180">

                    Alamat

                </th>

                <td>

                    {{ $siswa->alamat ?? '-' }}

                </td>

            </tr>

        </table>

    </div>

</div>

<style>

.profile-avatar{

    width:120px;
    height:120px;

    border-radius:50%;

    object-fit:cover;

    border:5px solid white;

    box-shadow:0 15px 35px rgba(0,0,0,.15);

}

.avatar-circle{

    width:120px;
    height:120px;

    border-radius:50%;

    background:linear-gradient(135deg,#2563eb,#0f4c81);

    color:#fff;

    display:flex;
    align-items:center;
    justify-content:center;

    font-size:42px;
    font-weight:700;

    box-shadow:0 15px 35px rgba(37,99,235,.25);

}

.info-box{

    background:#f8fafc;

    border:1px solid #e2e8f0;

    border-radius:16px;

    padding:18px;

    transition:.25s;

    height:100%;

}

.info-box:hover{

    border-color:#2563eb;

    transform:translateY(-2px);

    box-shadow:0 10px 24px rgba(37,99,235,.08);

}

.info-box small{

    color:#64748b;

    display:block;

    margin-bottom:6px;

}

.info-box h5{

    margin:0;

    font-weight:700;

    color:#1e293b;

}

.card{

    transition:.25s;

}

.card:hover{

    box-shadow:0 18px 40px rgba(15,76,129,.08)!important;

}

.card-header{

    background:#fff;

}

.table th{

    color:#64748b;

    font-weight:600;

    width:180px;

}

.table td{

    color:#0f172a;

    font-weight:500;

}

.badge{

    font-weight:500;

    border-radius:999px;

}

</style>

@endsection