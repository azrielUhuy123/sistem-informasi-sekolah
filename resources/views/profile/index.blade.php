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

                    <div class="mt-3">
                        <a href="{{ route('guru.profile.detail') }}" class="btn btn-primary rounded-pill px-4">
                            <i class="bi bi-person-lines-fill me-2"></i>
                            Detail Profil
                        </a>
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

                            <i class="bi bi-mortarboard-fill"></i>

                                <div>

                                    <h3>{{ $status }}</h3>

                                    <span>Tugas</span>

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
    border:6px solid rgba(255, 255, 255, 0.95);
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
    font-size:clamp(1.6rem, 2.8vw, 2rem);
    font-weight:700;
    color:#1e293b;
    line-height:1.25;
    margin-bottom:.25rem;

    white-space:normal;
    word-break:break-word;
    overflow-wrap:anywhere;
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
    background:#ecfdf5;
    color:#16a34a;
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
    background:#fff;
    border:1px solid #e5e7eb;
    border-radius:18px;
    padding:18px 22px;

    display:flex;
    align-items:flex-start; 

    gap:16px;

    transition:.25s;
    box-shadow:0 4px 14px rgba(15,23,42,.05);
}

.profile-stat:hover{
    transform:translateY(-3px);
    border-color:#2563eb;
    box-shadow:0 10px 22px rgba(37,99,235,.12);
}

.profile-stat i{
    color:#2563eb;
    font-size:26px;
}

.profile-stat h3{
    color:#1e293b;
    font-size:1.05rem;
    font-weight:700;
    margin:0;
}

.profile-stat span{
    color:#64748b;
    font-size:.82rem;
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

.profile-stat > div{
    flex:1;
    min-width:0;
}


</style>

@endpush

@endsection