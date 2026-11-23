@extends('layouts.app')

@section('title', 'User Aktif')

@section('content')

<div class="container-fluid dt-siswa-page">

    <x-table.page-header
        icon="bi-people"
        eyebrow="Manajemen Akun / Pengguna"
        title="User Aktif"
        description="Kelola akun pengguna yang telah disetujui, termasuk aktivasi dan pemblokiran akun."
    />

    {{-- ALERT --}}
    <x-alert.flash key="success" type="success"/>
    <x-alert.flash key="error" type="danger"/>

    {{-- TABLE --}}
    <x-table.card
        title="Daftar User Aktif"
        subtitle="Pengguna yang telah memperoleh akses ke sistem"
        :count="$users->total()"
    >

        <x-slot:toolbar>
            <x-table.toolbar table-id="dt-superadmin-users-approved-table" :column-toggle="true">
                <x-slot:columnMenu>
                    @foreach ([
                        'number' => 'No',
                        'user' => 'User',
                        'role' => 'Role',
                        'status' => 'Status',
                        'approved' => 'Tanggal Disetujui',
                        'action' => 'Aksi',
                    ] as $column => $label)
                        <label class="dt-column-menu__option">
                            <input type="checkbox" data-dt-column="{{ $column }}" checked>
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
                </x-slot:columnMenu>
            </x-table.toolbar>
        </x-slot:toolbar>

        <div class="table-responsive dt-siswa-table-wrap">

            <table id="dt-superadmin-users-approved-table" class="table table-hover align-middle mb-0 dt-table">

                <thead>
                    <tr>
                        <x-table.heading class="text-center" width="70px" column="number">No</x-table.heading>
                        <x-table.heading column="user">User</x-table.heading>
                        <x-table.heading width="130px" column="role">Role</x-table.heading>
                        <x-table.heading width="140px" column="status">Status</x-table.heading>
                        <x-table.heading width="190px" column="approved">Tanggal Disetujui</x-table.heading>
                        <x-table.heading width="140px" column="action">Aksi</x-table.heading>
                    </tr>
                </thead>

                <tbody>

                    @forelse($users as $user)
                        <tr>
                            {{-- NO --}}
                            <td class="text-center" data-dt-column="number">
                                <span class="badge bg-light text-dark rounded-circle">
                                    {{ $loop->iteration + ($users->firstItem() ?? 0) - 1 }}
                                </span>
                            </td>
                            {{-- USER --}}
                            <td data-dt-column="user">

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
                            <td data-dt-column="role">
                                <x-table.role-badge :role="$user->role" />
                            </td>

                            {{-- STATUS --}}
                            <td data-dt-column="status">

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
                            <td data-dt-column="approved">

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
                            <td data-dt-column="action">

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

                        <x-table.empty
                            :colspan="6"
                            title="Tidak ada pengguna aktif"
                            description="Tidak terdapat data user yang dapat ditampilkan."
                        />

                    @endforelse

                </tbody>

            </table>

        </div>

        <x-table.pagination :paginator="$users->appends(request()->except('page'))" :show-summary="true" />

    </x-table.card>

</div>

@endsection