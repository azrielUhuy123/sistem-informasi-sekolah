@extends('layouts.app')

@section('title', 'Detail Siswa')

@section('content')

    <div class="container-fluid">

        <div class="card shadow-sm border-0 overflow-hidden">
            <div class="card-body p-4 p-lg-5">

                <div class="row g-4 align-items-start">

                    <div class="col-lg-4">
                        <div class="bg-primary bg-opacity-10 rounded-4 p-4 border h-100">
                            <div class="d-flex flex-column align-items-center text-center gap-3">                                
                                <div>
                                    <h4 class="mb-1 fw-bold">{{ $user->name }}</h4>
                                    <p class="text-muted mb-2">{{ strtoupper($user->role) }}</p>

                                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                                        <span class="badge bg-primary-subtle text-primary-emphasis border border-primary-subtle">
                                            <i class="bi bi-person-badge me-1"></i> {{ $user->username }}
                                        </span>   
                                        <span class="badge-status {{ $user->is_active ? 'aktif' : 'nonaktif' }}">
                                            <i class="bi bi-circle-fill me-2"></i> {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>                                     
                                    </div>
                                </div>                                
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="card border-0 h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="fw-bold mb-0">Detail Akun</h5>                                    
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="small text-muted">Nama Pengguna Sistem</div>
                                        <div class="fw-semibold">
                                            {{ $user->username }}                                            
                                        </div>
                                    </div>  
                                    <div class="col-md-6">
                                        <div class="small text-muted">Role Sistem</div>                                        
                                        <x-table.role-badge :role="$user->role" /> 
                                    </div>  
                                    <div class="col-md-6">
                                        <div class="small text-muted">Status Akun</div>
                                        <div class="fw-semibold">
                                            <span class="badge-status {{ $user->is_active ? 'aktif' : 'nonaktif' }}">
                                                <i class="bi bi-circle-fill me-2"></i> {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </span>                                         
                                        </div>
                                    </div> 
                                    <div class="col-md-6">                                                                                
                                        <div class="small text-muted">Dibuat</div>
                                        <div class="fw-semibold">
                                            {{ $user->created_at->translatedFormat('d F Y') }}
                                            <small class="text-muted">{{ $user->created_at->format('H:i') }} WIB</small>
                                        </div>
                                    </div>  
                                                                    
                                </div>
                            </div>
                        </div>
                    </div>

                </div>                
 
            </div>

        </div>
        <div class="sticky-bottom bg-white border border-light-subtle py-3 mt-4">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('tu.akun.index') }}" class="btn btn-outline-secondary rounded-3 ms-4">
                    <i class="bi bi-chevron-left me-1"></i> Kembali
                </a>
            </div>
        </div>

    </div>
@endsection
