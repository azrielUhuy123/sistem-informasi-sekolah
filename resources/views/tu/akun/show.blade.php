@extends('layouts.app')

@section('title', 'Detail Akun')

@section('content')

<div class="container-fluid py-3">

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

        <div class="card-header bg-white border-0 px-3 py-3">

            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2">

                <div>

                    <h4 class="mb-1 fw-semibold">
                        <i class="bi bi-person-vcard-fill text-primary me-2"></i>
                        Detail Akun
                    </h4>

                    <p class="text-muted mb-0">

                        Informasi lengkap akun pengguna.

                    </p>

                </div>

                <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }} rounded-pill px-3 py-2">

                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}

                </span>

            </div>

        </div>

        <div class="card-body p-4">
            <div class="card-body p-4 mb-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                        <i class="bi bi-person-fill fs-4"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-5">{{ $user->name }}</div>
                        <small class="text-muted">{{ $user->username }}</small>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-uppercase small fw-semibold text-muted mb-2 d-block">Role</label>
                        <div class="fw-semibold">
                            <span class="badge bg-primary rounded-pill px-3 py-2">{{ strtoupper($user->role) }}</span>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="text-uppercase small fw-semibold text-muted mb-2 d-block">Dibuat</label>
                        <div class="fw-semibold">{{ $user->created_at->translatedFormat('d F Y') }}</div>
                        <small class="text-muted">{{ $user->created_at->format('H:i') }} WIB</small>
                    </div>
                </div>
            </div>

            <a href="{{ route('tu.akun.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Kembali</a>

        </div>

    </div>

</div>
@endsection