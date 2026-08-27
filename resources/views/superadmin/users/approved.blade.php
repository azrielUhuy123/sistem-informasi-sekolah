@extends('layouts.app')

@section('title', 'User Aktif')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">

                <div>
                    <h3 class="fw-bold mb-2">
                        User Aktif
                    </h3>

                    <p class="mb-0 opacity-75">
                        Kelola seluruh akun pengguna yang telah disetujui,
                        lakukan aktivasi maupun pemblokiran akun sesuai kebutuhan
                        untuk menjaga keamanan sistem.
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
        title="Daftar User Aktif"
        subtitle="Pengguna yang telah memperoleh akses ke sistem"
    >

        <x-slot:badge>
            <span class="badge bg-primary rounded-pill px-2 py-1">
                {{ $users->total() }}
                <span class="text-badge">User</span>
            </span>
        </x-slot:badge>

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead>
                    <tr>
                        <th width="70">No</th>
                        <th>User</th>
                        <th width="130">Role</th>
                        <th width="140">Status</th>
                        <th width="190">Tanggal Disetujui</th>
                        <th width="140">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($users as $user)
                        <tr>
                            {{-- NO --}}
                            <td>
                                <span class="badge bg-light text-dark rounded-circle">
                                    {{ $loop->iteration + ($users->firstItem() ?? 0) - 1 }}
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

                                @if($user->is_active)

                                    <span class="badge-status aktif">
                                        <i class="bi bi-circle-fill me-2"></i>
                                        Aktif
                                    </span>

                                @else

                                    <span class="badge-status nonaktif">
                                        <i class="bi bi-lock-fill me-1"></i>
                                        Diblokir
                                    </span>

                                @endif

                            </td>

                            {{-- TANGGAL APPROVE --}}
                            <td>

                                <div class="fw-semibold">
                                    {{ $user->approved_at?->format('d F Y') ?? '-' }}
                                </div>

                                @if($user->approved_at)

                                    <small class="text-muted">
                                        {{ $user->approved_at->format('H:i') }} WIB
                                    </small>

                                @endif

                            </td>

                            {{-- AKSI --}}
                            <td>

                                @if($user->is_active)

                                    {{-- BLOKIR --}}
                                    <form
                                        action="{{ route('superadmin.users.block', $user) }}"
                                        method="POST"
                                    >
                                        @csrf

                                        <button
                                            type="submit"
                                            class="btn btn-danger btn-sm rounded-pill px-3 py-0 btn-confirm"
                                            data-title="Blokir User"
                                            data-message="User ini akan diblokir dan tidak dapat mengakses sistem."
                                            data-button="btn-danger"
                                        >                                            
                                            Blokir
                                        </button>

                                    </form>

                                @else

                                    {{-- AKTIFKAN --}}
                                    <form
                                        action="{{ route('superadmin.users.unblock', $user) }}"
                                        method="POST"
                                    >
                                        @csrf

                                        <button
                                            type="submit"
                                            class="btn btn-success btn-sm rounded-pill px-3 py-0 btn-confirm"
                                            data-title="Aktifkan User"
                                            data-message="User ini akan diaktifkan kembali dan dapat mengakses sistem."
                                            data-button="btn-success"
                                        >                                            
                                            Aktifkan
                                        </button>

                                    </form>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="text-center py-5">

                                <i class="bi bi-people display-5 text-secondary"></i>

                                <p class="mt-3 mb-0 text-muted">
                                    Tidak terdapat data user yang dapat ditampilkan.
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