@extends('layouts.app')

@section('title', 'Profile Saya')

@section('content')

<div class="container-fluid py-4">
    {{-- PROFILE --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-4 p-lg-5">
            <div class="row align-items-center">
                {{-- FOTO --}}
                <div class="col-lg-3 text-center mb-4 mb-lg-0">
                    <img
                        src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=f8f9fa&color=2563eb&size=250"
                        alt="Foto {{ $user->name }}"
                        class="rounded-circle border border-2 border-light shadow-sm"
                        style="width: 170px; height: 170px; object-fit: cover;"
                    >

                    <div class="mt-4 d-flex justify-content-center align-items-center gap-2 flex-wrap">
                        <x-table.role-badge :role="$user->role" />
                        {{-- STATUS AKUN --}}                    
                        @if($user->is_active)
                            <span class="badge-status aktif">
                                <i class="bi bi-circle-fill me-1"></i>
                                Aktif
                            </span>
                        @else
                            <span class="badge-status nonaktif">
                                <i class="bi bi-circle-fill me-1"></i>
                                Tidak Aktif
                            </span>
                        @endif                
                    </div>    
 
                    {{-- DETAIL PROFIL --}}
                    <div class="mt-3">
                        <a
                            href="{{ route('guru.profile.detail') }}"
                            class="btn btn-primary rounded-3"
                        >
                            <i class="bi bi-person-lines-fill me-1"></i>
                            Detail Profil
                        </a>
                    </div>
                </div>

                {{-- IDENTITAS --}}
                <div class="col-lg-9">
                    <small class="text-secondary">
                        Nama
                    </small>
                    <h1 class="fw-bold text-primary"
                        style="font-size: 2rem;">
                        {{ $user->name }}
                    </h1>
                    <div class="fw-semibold text-muted">
                        <i class="bi bi-mortarboard-fill text-primary me-2"></i>{{ $status }}
                    </div>

                    {{-- NIP --}}
                    <div class="row mt-2 g-3">                    
                        <div class="col-12">
                            <div class="d-flex align-items-start gap-3 bg-light px-3 py-2 rounded-3">
                                <i class="bi bi-person-vcard text-primary fs-5 mt-1"></i>
                                <div class="flex-grow-1 min-w-0">
                                    <div class="fw-semibold text-dark"
                                         style="font-size: 1.05rem;">
                                        {{ $user->guru->nip }}
                                    </div>
                                    <small class="text-secondary">
                                        NIP
                                    </small>
                                </div>
                            </div>
                        </div>

                        {{-- JABATAN --}}
                        <div class="col-12">
                            <div class="d-flex align-items-start gap-3 bg-light px-3 py-2 rounded-3">
                                <i class="bi bi-briefcase-fill text-primary fs-5 mt-1"></i>
                                <div class="flex-grow-1 min-w-0">
                                    <div class="fw-semibold text-dark">
                                        {{ $user->guru->pegawai->jabatan }}
                                    </div>
                                    <small class="text-secondary">
                                        Jabatan
                                    </small>
                                </div>
                            </div>
                        </div>
                        {{-- TEMPAT, TANGGAL LAHIR --}}
                        <div class="col-12">
                            <div class="d-flex align-items-start gap-3 bg-light px-3 py-2 rounded-3">
                                <i class="bi bi-geo-alt-fill text-primary fs-5 mt-1"></i>
                                <div class="flex-grow-1 min-w-0">
                                    <div class="fw-semibold text-dark">
                                        {{ $user->guru->pegawai->tempat_lahir }},
                                        {{ $user->guru->pegawai->tanggal_lahir
                                            ? \Carbon\Carbon::parse(
                                                $user->guru->pegawai->tanggal_lahir
                                            )->translatedFormat('d M Y')
                                            : '-' }}
                                    </div>
                                    <small class="text-secondary">
                                        Tempat Tanggal Lahir
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">    

                        {{-- AGAMA --}}
                        <div class="col-12 col-sm-4 mb-2">
                            <div class="d-flex align-items-start gap-3 bg-light px-3 py-2 rounded-3">
                                <i class="bi bi-bookmark-heart-fill text-primary fs-5 mt-1"></i>
                                <div class="flex-grow-1 min-w-0">
                                    <div class="fw-semibold text-dark">
                                        {{ $user->guru->pegawai->agama }}
                                    </div>
                                    <small class="text-secondary">
                                        Agama
                                    </small>
                                </div>
                            </div>
                        </div>

                        {{-- JENIS KELAMIN --}}
                        <div class="col-12 col-sm-4 mb-2">
                            <div class="d-flex align-items-start gap-3 bg-light px-3 py-2 rounded-3">
                                <i class="bi bi-gender-ambiguous text-primary fs-5 mt-1"></i>
                                <div class="flex-grow-1 min-w-0">
                                    <div class="fw-semibold text-dark">
                                        {{ $user->guru->pegawai->jenis_kelamin == 'L'
                                            ? 'Laki-laki'
                                            : 'Perempuan' }}
                                    </div>
                                    <small class="text-secondary">
                                        Jenis Kelamin
                                    </small>
                                </div>
                            </div>
                        </div>

                        {{-- STATUS --}}
                        <div class="col-12 col-sm-4">
                            <div class="d-flex align-items-start gap-3 bg-light px-3 py-2 rounded-3">
                                <i class="bi bi-shield-check text-primary fs-5 mt-1"></i>
                                <div class="flex-grow-1 min-w-0">
                                    <div class="fw-semibold text-dark">
                                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </div>
                                    <small class="text-secondary">
                                        Status
                                    </small>
                                </div>
                            </div>
                        </div>            
                    </div>                
                </div>
            </div>
        </div>
    </div>

    {{-- PASSWORD --}}
    <div class="card border-0 shadow-sm rounded-3 mt-4">
        <div class="card-body">
            @include('profile.partials.update-password-form')
        </div>
    </div>
</div>
@endsection