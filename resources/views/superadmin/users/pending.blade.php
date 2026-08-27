@extends('layouts.app')

@section('title', 'Approval User')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">

                <div>
                    <h3 class="fw-bold mb-2">
                        Approval User
                    </h3>

                    <p class="mb-0 opacity-75">
                        Tinjau dan proses akun pengguna baru yang menunggu persetujuan untuk dapat mengakses sistem.
                    </p>
                </div>

                <div class="badge bg-white text-primary px-3 py-2 fs-6">
                    <i class="bi bi-shield-check me-2"></i> Sinergi-MJ
                </div>

            </div>
        </div>
    </div>

    {{-- ALERT --}}
    <x-alert.flash key="success" type="success"/>
    <x-alert.flash key="error" type="danger"/>

    {{-- TABLE --}}
    <x-table.card
        title="Daftar User Menunggu Persetujuan"
        subtitle="Tinjau akun pengguna baru dan putuskan untuk menyetujui atau menolak"
    >

        <x-slot:badge>
            <span class="badge bg-warning text-dark rounded-pill px-2 py-1">
                {{ $users->total() }}
                <span class="text-badge">Pending</span>
            </span>
        </x-slot:badge>

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead>
                    <tr>
                        <th width="70">No</th>
                        <th>User</th>
                        <th width="130">Role</th>
                        <th width="180">Status</th>
                        <th width="190">Tanggal Registrasi</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($users as $user)

                        <tr>

                            {{-- NO --}}
                            <td>
                                <span class="badge bg-light text-dark rounded-circle">
                                    {{ $loop->iteration }}
                                </span>
                            </td>

                            {{-- USER --}}
                            <td>

                                <div class="d-flex align-items-center">

                                    <div
                                        class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3"
                                        style="width: 48px; height: 48px;"
                                    >
                                        <i class="bi bi-person-fill fs-4"></i>
                                    </div>

                                    <div>

                                        <div class="fw-semibold">
                                            {{ $user->name }}
                                        </div>

                                        <small class="text-muted">
                                            {{ $user->username }}
                                        </small>

                                    </div>

                                </div>

                            </td>

                            {{-- ROLE --}}
                            <td>
                                <x-table.role-badge :role="$user->role" />
                            </td>

                            {{-- STATUS --}}
                            <td>

                                <span class="badge-status pending">
                                    <i class="bi bi-hourglass-split me-1"></i>
                                    Pending
                                </span> 

                            </td>

                            {{-- TANGGAL --}}
                            <td>

                                <div class="fw-semibold">                                    
                                    {{ $user->created_at->format('d F Y') }}                                    
                                </div>
                                <small class="text-muted">{{ $user->created_at->format('H:i') }} WIB</small>

                            </td>

                            {{-- AKSI --}}
                            <td>

                                <div class="d-flex gap-2">

                                    {{-- APPROVE --}}
                                    <form
                                        action="{{ route('superadmin.users.approve', $user) }}"
                                        method="POST"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="btn btn-success btn-sm rounded-pill px-3 py-0 btn-confirm"
                                            data-title="Approve User"
                                            data-message="User ini akan disetujui dan dapat mengakses sistem."
                                            data-button="btn-success"
                                        >                                            
                                            Setujui
                                        </button>

                                    </form>

                                    {{-- TOLAK --}}
                                    <form
                                        action="{{ route('superadmin.users.reject', $user) }}"
                                        method="POST"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="btn btn-danger btn-sm rounded-pill px-3 py-0 btn-confirm"
                                            data-title="Tolak User"
                                            data-message="User ini akan ditolak dan tidak dapat mengakses sistem."
                                            data-button="btn-danger"
                                        >                                           
                                            Tolak
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="text-center py-5">

                                <i class="bi bi-inbox display-5 text-secondary"></i>

                                <p class="mt-3 mb-0 text-muted">
                                    Belum terdapat user yang menunggu approval.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- PAGINATION --}}
        @if($users->hasPages())

            <div class="mt-4">
                {{ $users->links() }}
            </div>

        @endif

    </x-table.card>

</div>

@endsection