@extends('layouts.app')

@section('title', 'Detail Siswa')

@section('content')

@php

    $totalNilai = $nilai->count();

    $rataNilai = $totalNilai
        ? round($nilai->avg('nilai_akhir'),1)
        : 0;

@endphp

<div class="container-fluid py-4">

    {{-- HERO --}}
    <div class="card shadow-sm border-0 overflow-hidden mb-3">
            <div class="card-body p-4 p-lg-5">

                <div class="row g-4 align-items-start">

                    <div class="col-lg-4">
                        <div class="bg-primary bg-opacity-10 rounded-4 p-4 border h-100">
                            <div class="d-flex flex-column align-items-center text-center gap-3">
                                <img
                                    src="{{ $siswa->foto
                                        ? asset('storage/foto_siswa/'.$siswa->foto)
                                        : 'https://ui-avatars.com/api/?name='.urlencode($siswa->nama).'&background=E9ECEF&color=495057&size=120' }}"
                                    class="rounded-circle"
                                    width="110"
                                    height="110"
                                    style="object-fit:cover;"
                                >

                                <div>
                                    <h4 class="mb-1 fw-bold">{{ $siswa->nama }}</h4>
                                    <p class="text-muted mb-2">Siswa</p>

                                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                                        <span class="badge bg-primary-subtle text-primary-emphasis border border-primary-subtle">
                                            <i class="bi bi-person-badge me-1"></i> NIS {{ $siswa->nis }}
                                        </span>
                                        <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle">
                                            <i class="bi bi-credit-card-2-front me-1"></i> NISN {{ $siswa->nisn ?? '-' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="w-100">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <div class="border rounded-3 p-2 bg-white">
                                                <div class="small text-muted">Status Siswa</div>
                                                <div class="fw-semibold">
                                                    @if($siswa->penempatanAktif)
                                                        <span class="badge-status aktif">
                                                        <i class="bi bi-circle-fill me-2"></i>
                                                        Aktif
                                                    @else
                                                        <span class="badge-status nonaktif">
                                                        <i class="bi bi-circle-fill me-2"></i>
                                                        Tidak Aktif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="border rounded-3 p-2 bg-white">
                                                <div class="small text-muted">Rombel</div>
                                                <div class="fw-semibold">
                                                    <span class="badge bg-purple rounded-3">{{ $siswa->penempatanAktif?->kelas?->nama_kelas_label ?? '-' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="card border-0 h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="fw-bold mb-0">Profil Siswa</h5>
                                    <x-table.role-badge :role="$siswa->user?->role" /> 
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="small text-muted mb-1">Tempat, Tanggal Lahir</div>
                                        <div class="fw-semibold">
                                            {{ $siswa->ppdb?->tempat_lahir ?? '-' }},
                                            {{ $siswa->ppdb?->tanggal_lahir
                                            ? \Carbon\Carbon::parse($siswa->ppdb?->tanggal_lahir)->format('d-m-Y')
                                            : '-' }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="small text-muted mb-1">Agama</div>
                                        <div class="fw-semibold">{{ $siswa->ppdb?->agama }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="small text-muted mb-1">Alamat</div>
                                        <div class="fw-semibold">{{ $siswa->alamat }}</div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="small text-muted mb-1">Jenis Kelamin</div>
                                        <div class="fw-semibold">
                                            @if($siswa->jenis_kelamin == 'L')
                                                <span class="badge bg-indigo rounded-pill px-2 py-1">
                                                    <i class="bi bi-gender-male me-1"></i>
                                                    Laki-laki
                                                </span>
                                            @else
                                                <span class="badge bg-rose rounded-pill px-2 py-1">
                                                    <i class="bi bi-gender-female me-1"></i>
                                                    Perempuan
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
    </div>

    {{-- ABSENSI --}}
    <div class="row g-2 justify-content-center mb-4">
        <div class="col-lg-3 col-3">
            <div class="card border-0 rounded-3 shadow-sm text-center bg-status aktif p-3">
                <h3>{{ $hadir }}</h3>
                <small class="text-muted">
                    Hadir
                </small>
            </div>
        </div>
        <div class="col-lg-3 col-3">
            <div class="card border-0 rounded-3 shadow-sm text-center bg-status pending text-label p-3">
                <h3>{{ $izin }}</h3>
                <small class="text-muted">
                    Izin
                </small>
            </div>
        </div>
        <div class="col-lg-3 col-3">
            <div class="card border-0 rounded-3 shadow-sm text-center bg-status verifikasi p-3">
                <h3>{{ $sakit }}</h3>
                <small class="text-muted">
                    Sakit
                </small>
            </div>
        </div>
        <div class="col-lg-3 col-3">
            <div class="card border-0 rounded-3 shadow-sm text-center bg-status nonaktif p-3">
                <h3>{{ $alpha }}</h3>
                <small class="text-muted">
                    Alpha
                </small>
            </div>
        </div>       
    </div>

    {{-- TABLE --}}
    <x-table.card
        title="Nilai Akademik"
        subtitle="Rekap seluruh nilai mata pelajaran siswa."
    >

        <x-slot:badge>
            <span class="badge bg-primary rounded-pill px-2 py-1">
                {{ $totalNilai }}
                <span class="text-badge">Mata Pelajaran</span>
            </span>
        </x-slot:badge>    

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th width="70">
                            No
                        </th>
                        <th>
                            Mata Pelajaran
                        </th>
                        <th width="150">
                            Tugas
                        </th>
                        <th width="150">
                            UTS
                        </th>
                        <th width="150">
                            UAS
                        </th>
                        <th width="90">
                            Nilai Akhir
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nilai as $n)
                        <tr>                           
                            <td>
                                <span class="badge bg-light text-dark rounded-circle">
                                    {{ $loop->iteration }}
                                </span>
                            </td>                        
                            <td>
                                <div class="d-flex align-items-center text-muted">
                                    <i class="bi bi-book-half text-primary me-2"></i>
                                    <span class="fw-semibold">
                                        {{ $n->mapel->nama_mapel }}
                                    </span>
                                </div>                                
                            </td>                            
                            <td>
                                <span>
                                    {{ $n->nilai_tugas }}
                                </span>
                            </td>                                
                            <td>
                                <span>
                                    {{ $n->nilai_uts }}
                                </span>
                            </td>                                
                            <td>
                                <span>
                                    {{ $n->nilai_uas }}
                                </span>
                            </td>                                
                            <td>
                                <span class="fw-bold">
                                    {{ $n->nilai_akhir }}
                                </span> 
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="text-center py-5">
                                    <i class="bi bi-journal-x display-3 text-secondary"></i>
                                    <h5 class="fw-bold mt-3">
                                        Belum Ada Nilai
                                    </h5>
                                    <p class="text-muted mb-0">
                                        Nilai akademik siswa belum tersedia.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>            
        </div>
    </x-table.card>
    {{-- PERKEMBANGAN --}}
    <div class="card border-0 shadow-sm rounded-4 mt-4">
        <div class="card-header bg-white border-0 py-4">
            <h5 class="fw-bold mb-1">
                Perkembangan Siswa
            </h5>
            <small class="text-muted">
                Catatan perkembangan karakter dan perilaku siswa.
            </small>
        </div>
        <div class="card-body">
            @if($perkembangan)
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="form-label">
                                <i class="bi bi-stars me-2"></i>
                                Karakter
                            </div>
                            <p class="mb-0 bg-light p-3 rounded-3">
                                {{ $perkembangan->karakter ?: '-' }}
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="form-label">
                                <i class="bi bi-people-fill me-2"></i>
                                Sosial
                            </div>
                            <p class="mb-0 p-3 rounded-3 bg-light">
                                {{ $perkembangan->sosial ?: '-' }}
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="form-label">
                                <i class="bi bi-shield-check me-2"></i>
                                Disiplin
                            </div>
                            <p class="mb-0 p-3 rounded-3 bg-light">
                                {{ $perkembangan->disiplin ?: '-' }}
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="form-label">
                                <i class="bi bi-droplet-half me-2"></i>
                                Kebersihan
                            </div>
                            <p class="mb-0 p-3 rounded-3 bg-light">
                                {{ $perkembangan->kebersihan ?: '-' }}
                            </p>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="info-card">
                            <div class="form-label">
                                <i class="bi bi-chat-left-text-fill me-2"></i>
                                Catatan Umum
                            </div>
                            <p class="mb-0 p-3 rounded-3 bg-light">
                                {{ $perkembangan->catatan ?: '-' }}
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-person-lines-fill display-3 text-secondary"></i>
                    <h5 class="fw-bold mt-3">
                        Belum Ada Data Perkembangan
                    </h5>
                    <p class="text-muted mb-0">
                        Guru belum memberikan catatan perkembangan siswa.
                    </p>
                </div>
            @endif
        </div>
    </div>
    <div class="sticky-bottom bg-white border border-light-subtle py-3 mt-4">
        <div class="d-flex justify-content-between align-items-center">
            <a href="javascript:history.back()" class="btn btn-outline-secondary rounded-3 ms-4">
                <i class="bi bi-chevron-left me-1"></i> Kembali
            </a>
        </div>
    </div>
</div>

@endsection