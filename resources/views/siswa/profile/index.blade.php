@extends('layouts.app')

@section('title', 'Profile Saya')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="mb-4">

        <h2 class="fw-bold display-6 mb-1">
            Profile Saya
        </h2>

        <p class="text-secondary">
            Kelola informasi identitas akun siswa.
        </p>

    </div>

    {{-- PROFILE --}}
    <div class="profile-header mb-4">

        <div class="p-5 position-relative">

            <div class="row align-items-center">

                {{-- FOTO --}}
                <div class="col-lg-3 text-center mb-4 mb-lg-0">

                    <img
                        src="https://ui-avatars.com/api/?name={{ urlencode($siswa->nama) }}&background=ffffff&color=2563eb&size=250"
                        class="profile-avatar"
                    >

                    <div class="mt-4">

                        <div class="status-chip">

                            <span class="status-dot"></span>

                            {{ $user->is_active ? 'Akun Aktif' : 'Tidak Aktif' }}

                        </div>

                    </div>

                    <div class="mt-3">

                        <a href="{{ route('siswa.profile.detail') }}"
                            class="btn btn-primary rounded-pill px-4">

                            <i class="bi bi-person-lines-fill me-2"></i>
                            Detail Profil

                        </a>

                    </div>

                </div>

                {{-- IDENTITAS --}}
                <div class="col-lg-4">

                    <h1 class="profile-name">

                        {{ $siswa->nama }}

                    </h1>

                    <div class="row mt-4 g-3">

                        {{-- CARD NIS --}}
                        <div class="col-12">

                            <div class="profile-stat">

                                <i class="bi bi-person-vcard"></i>

                                <div class="flex-grow-1">

                                    <h3 class="nip-text">

                                        {{ $siswa->nis ?? '-' }}

                                    </h3>

                                    <span>NIS</span>

                                </div>

                            </div>

                        </div>

                        {{-- CARD NISN --}}
                        <div class="col-8">

                            <div class="profile-stat">

                                <i class="bi bi-credit-card-2-front-fill"></i>

                                <div>

                                    <h3>

                                        {{ $siswa->nisn ?: '-' }}

                                    </h3>

                                    <span>NISN</span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                {{-- INFORMASI KANAN --}}
                <div class="col-lg-5">

                    <div class="row g-3">

                        {{-- KELAS --}}
                        <div class="col-12">

                            <div class="profile-stat">

                                <i class="bi bi-house-door-fill"></i>

                                <div>

                                    <h3>

                                        {{ $siswa->penempatanAktif?->kelas?->nama_kelas ?? '-' }}

                                    </h3>

                                    <span>Kelas</span>

                                </div>

                            </div>

                        </div>

                        {{-- AGAMA --}}
                        <div class="col-6">

                            <div class="profile-stat">

                                <i class="bi bi-bookmark-heart-fill"></i>

                                <div>

                                    <h3>

                                        {{ $ppdb->agama ?? '-' }}

                                    </h3>

                                    <span>Agama</span>

                                </div>

                            </div>

                        </div>

                        {{-- JENIS KELAMIN --}}
                        <div class="col-6">

                            <div class="profile-stat">

                                <i class="bi bi-gender-ambiguous"></i>

                                <div>

                                    <h3>

                                        {{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}

                                    </h3>

                                    <span>Jenis Kelamin</span>

                                </div>

                            </div>

                        </div>

                        {{-- STATUS DAFTAR ULANG --}}
                        <div class="col-6">

                            <div class="profile-stat">

                                <i class="bi bi-patch-check-fill"></i>

                                <div>

                                    <h3>

                                        {{ ucfirst($ppdb->status_daftar_ulang ?? 'Belum') }}

                                    </h3>

                                    <span>Status Daftar Ulang</span>

                                </div>

                            </div>

                        </div>

                        {{-- TAHUN AJARAN --}}
                        <div class="col-6">

                            <div class="profile-stat">

                                <i class="bi bi-calendar3"></i>

                                <div>

                                    <h3>

                                        {{ current_tahun()?->tahun ?? '-' }}

                                    </h3>

                                    <span>Tahun Ajaran</span>

                                </div>

                            </div>

                        </div>

                        {{-- TEMPAT, TANGGAL LAHIR --}}
                        <div class="col-6">

                            <div class="profile-stat">

                                <i class="bi bi-geo-alt-fill"></i>

                                <div>

                                    <h3>

                                        {{ $ppdb->tempat_lahir ?? '-' }}

                                        @if($ppdb->tanggal_lahir)
                                            <br>
                                            {{ \Carbon\Carbon::parse($ppdb->tanggal_lahir)->translatedFormat('d F Y') }}
                                        @endif

                                    </h3>

                                    <span>Tempat, Tanggal Lahir</span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    </div> {{-- profile-header --}}

    {{-- UBAH PASSWORD --}}
    <div class="card shadow-sm border-0">

        <div class="card-header">

            <h5 class="mb-0 fw-bold">
                Ubah Password
            </h5>

        </div>

        <div class="card-body">

            @include('profile.partials.update-password-form')

        </div>

    </div>

</div>



@push('styles')

<style>

.profile-header{
    background:#fff;
    border-radius:24px;
    overflow:hidden;
    position:relative;
    border:1px solid #e5e7eb;
    box-shadow:0 8px 25px rgba(15,23,42,.06);
}

.profile-avatar{
    width:170px;
    height:170px;
    border-radius:50%;
    border:6px solid rgba(255,255,255,.95);
    box-shadow:
        0 15px 35px rgba(0,0,0,.25),
        0 0 40px rgba(255,255,255,.18);
    transition:.35s;
    position:relative;
    z-index:2;
}

.profile-avatar:hover{
    transform:scale(1.04) rotate(-2deg);
}

.profile-name{
    font-size:clamp(1.6rem,2.8vw,2rem);
    font-weight:700;
    color:#1e293b;
    line-height:1.25;
    margin-bottom:.25rem;

    white-space:normal;
    word-break:break-word;
    overflow-wrap:anywhere;
}

.status-chip{
    display:inline-flex;
    align-items:center;
    gap:.5rem;
    padding:.55rem 1rem;
    border-radius:50px;
    background:#ecfdf5;
    color:#16a34a;
    font-weight:600;
}

.status-dot{
    width:10px;
    height:10px;
    border-radius:50%;
    background:#22c55e;
    box-shadow:0 0 12px #22c55e;
}

.profile-stat{
    background:#fff;
    border:1px solid #e5e7eb;
    border-radius:18px;
    padding:18px 22px;

    display:flex;
    align-items:flex-start;
    gap:16px;

    transition:.25s;
    box-shadow:0 4px 14px rgba(15,23,42,.05);

    height:100%;
}

.profile-stat:hover{
    transform:translateY(-3px);
    border-color:#2563eb;
    box-shadow:0 10px 22px rgba(37,99,235,.12);
}

.profile-stat i{
    color:#2563eb;
    font-size:26px;
    flex-shrink:0;
}

.profile-stat > div{
    flex:1;
    min-width:0;
}

.profile-stat h3{
    color:#1e293b;
    font-size:1rem;
    font-weight:700;
    margin:0;
    line-height:1.45;

    white-space:normal;
    word-break:break-word;
    overflow-wrap:anywhere;
}

.profile-stat span{
    color:#64748b;
    font-size:.82rem;
}

</style>

@endpush

@endsection