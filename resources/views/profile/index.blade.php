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
            Kelola informasi identitas akun Anda.
        </p>

    </div>

    {{-- PROFILE --}}
    <div class="profile-header mb-4">

        <div class="p-5 position-relative">

            <div class="row align-items-center">

                {{-- FOTO --}}
               <div class="col-lg-3 text-center mb-4 mb-lg-0">

                    <img
                        src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=ffffff&color=2563eb&size=250"
                        class="profile-avatar"
                    >

                    <div class="mt-4">

                        <div class="status-chip">

                            <span class="status-dot"></span>

                            {{ $user->is_active ? 'Akun Aktif' : 'Tidak Aktif' }}

                        </div>

                    </div>

                </div>

                {{-- IDENTITAS --}}
                <div class="col-lg-4">

                    <h1 class="profile-name">
                        {{ $user->name }}
                    </h1>

                    <div class="row mt-4 g-3">

                        {{-- CARD NIP --}}
                        <div class="col-12">

                            <div class="profile-stat">

                                <i class="bi bi-person-vcard"></i>

                                <div class="flex-grow-1">

                                    <h3 class="nip-text">
                                        {{ $user->guru->nip }}
                                    </h3>

                                    <span>NIP</span>

                                </div>

                            </div>

                        </div>

                        {{-- CARD JABATAN --}}
                        <div class="col-8">

                            <div class="profile-stat">

                                <i class="bi bi-briefcase-fill"></i>

                                <div>

                                    <h3>{{ $user->guru->pegawai->jabatan }}</h3>

                                    <span>Jabatan</span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="col-lg-5">

                    <div class="row g-3">

                        {{-- Tempat Tanggal Lahir --}}
                        <div class="col-12">
                            <div class="profile-stat">

                                <i class="bi bi-geo-alt-fill"></i>

                                <div>
                                    <h3>
                                        {{ $user->guru->pegawai->tempat_lahir }},
                                        {{ \Carbon\Carbon::parse($user->guru->pegawai->tanggal_lahir)->translatedFormat('d M Y') }}
                                    </h3>
                                    <span>Tempat, Tanggal Lahir</span>
                                </div>

                            </div>
                        </div>

                        {{-- Agama --}}
                        <div class="col-6">
                            <div class="profile-stat">

                                <i class="bi bi-bookmark-heart-fill"></i>

                                <div>
                                    <h3>{{ $user->guru->pegawai->agama }}</h3>
                                    <span>Agama</span>
                                </div>

                            </div>
                        </div>

                        {{-- Jenis Kelamin --}}
                        <div class="col-6">
                            <div class="profile-stat">

                                <i class="bi bi-gender-ambiguous"></i>

                                <div>
                                    <h3>
                                        {{ $user->guru->pegawai->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                    </h3>
                                    <span>Jenis Kelamin</span>
                                </div>

                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="col-6">

                            <div class="profile-stat">

                                <i class="bi bi-shield-check"></i>

                                <div>

                                    <h3>

                                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}

                                    </h3>

                                    <span>Status</span>

                                </div>

                            </div>

                        </div>

                        {{-- Tugas --}}
                        <div class="col-6">

                            <div class="profile-stat">

                                <i class="bi bi-mortarboard-fill"></i>

                                <div>

                                    <h3>{{ $status }}</h3>

                                    <span>Tugas</span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- PASSWORD --}}
    <div class="card border-0 shadow-sm mt-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
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
    background: linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);
    border-radius:24px;
    overflow:hidden;
    position:relative;
    box-shadow:0 15px 40px rgba(37,99,235,.18);
}

.profile-header::before{
    content:'';
    position:absolute;
    width:320px;
    height:320px;
    border-radius:50%;
    background:rgba(255,255,255,.06);
    top:-130px;
    right:-100px;
}

.profile-header::after{
    content:'';
    position:absolute;
    width:220px;
    height:220px;
    border-radius:50%;
    background:rgba(255,255,255,.04);
    bottom:-120px;
    left:-70px;
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
    font-size:2rem;
    font-weight:700;
    color:#fff;
    margin-bottom:.25rem;
}

.profile-role{
    color:rgba(255,255,255,.85);
    font-size:1rem;
    margin-bottom:1rem;
}

.status-chip{
    display:inline-flex;
    align-items:center;
    gap:.5rem;
    padding:.55rem 1rem;
    border-radius:50px;
    background:rgba(255,255,255,.18);
    color:#fff;
    backdrop-filter:blur(12px);
    font-weight:600;
}

.status-dot{
    width:10px;
    height:10px;
    border-radius:50%;
    background:#22c55e;
    box-shadow:0 0 12px #22c55e;
}

.profile-info{
    margin-top:-35px;
    position:relative;
    z-index:20;
}

.info-card{
    background:#fff;
    border:none;
    border-radius:20px;
    padding:24px;
    box-shadow:0 10px 30px rgba(15,76,129,.08);
    transition:.3s;
    height:100%;
}

.info-card:hover{
    transform:translateY(-6px);
    box-shadow:0 18px 40px rgba(37,99,235,.15);
}

.info-icon{
    width:58px;
    height:58px;
    border-radius:18px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:22px;
    color:#2563eb;
    background:#eef5ff;
}

.info-label{
    color:#94a3b8;
    font-size:.85rem;
    margin-bottom:6px;
}

.info-value{
    font-size:1.15rem;
    font-weight:700;
    color:#1e293b;
    line-height:1.4;
}

.profile-stat{
    background:rgba(255,255,255,.12);
    border-radius:18px;
    padding:18px 22px;
    display:flex;
    align-items:center;
    gap:16px;
    backdrop-filter:blur(14px);
    border:1px solid rgba(255,255,255,.08);
    transition:.3s;
}

.profile-stat:hover{
    background:rgba(255,255,255,.18);
    transform:translateY(-3px);
}

.profile-stat i{
    font-size:26px;
    color:white;
}

.profile-stat h3{
    color:white;
    font-size:1.15rem;
    margin:0;
    font-weight:700;
}

.profile-stat span{
    color:rgba(255,255,255,.75);
    font-size:.85rem;
}
.profile-stat h3{
    color:white;
    font-size:1.1rem;
    font-weight:700;
    margin:0;
    white-space:nowrap;
}
</style>

@endpush

@endsection