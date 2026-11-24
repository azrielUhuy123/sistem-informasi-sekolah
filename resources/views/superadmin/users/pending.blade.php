@extends('layouts.app')

@section('title', 'Approval User')

@section('content')

<div class="container-fluid dt-siswa-page">

    <x-table.page-header
        icon="bi-person-check"
        eyebrow="Manajemen Akun / Persetujuan"
        title="Approval User"
        description="Tinjau dan proses akun pengguna baru yang menunggu persetujuan untuk dapat mengakses sistem."
    />

    {{-- ALERT --}}
    <x-alert.flash key="success" type="success"/>
    <x-alert.flash key="error" type="danger"/>

    {{-- TABLE --}}
    <x-table.card
        title="Daftar User Menunggu Persetujuan"
        subtitle="Tinjau akun pengguna baru dan putuskan untuk menyetujui atau menolak"
        :count="$users->total()"
    >

        <x-slot:toolbar>
            <x-table.toolbar
                table-id="dt-superadmin-users-pending-table"
                :column-toggle="true"
                :refreshable="true"
                :refresh-url="request()->fullUrl()"
            >
                <x-slot:columnMenu>
                    @foreach ([
                        'number' => 'No',
                        'user' => 'User',
                        'role' => 'Role',
                        'status' => 'Status',
                        'registered' => 'Tanggal Registrasi',
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

            <table id="dt-superadmin-users-pending-table" class="table table-hover align-middle mb-0 dt-table">
                <colgroup>
                    <col data-dt-column="number">
                    <col data-dt-column="user">
                    <col data-dt-column="role">
                    <col data-dt-column="status">
                    <col data-dt-column="registered">
                    <col data-dt-column="action">
                </colgroup>

                <thead>
                    <tr>
                        <x-table.heading class="text-center" width="70px" column="number">No</x-table.heading>
                        <x-table.heading column="user">User</x-table.heading>
                        <x-table.heading width="130px" column="role">Role</x-table.heading>
                        <x-table.heading width="180px" column="status">Status</x-table.heading>
                        <x-table.heading width="190px" column="registered">Tanggal Registrasi</x-table.heading>
                        <x-table.heading width="180px" column="action">Aksi</x-table.heading>
                    </tr>
                </thead>

                <tbody>

                    @forelse($users as $user)

                        <tr>

                            {{-- NO --}}
                            <td class="text-center" data-dt-column="number">
                                <span class="badge bg-light text-dark rounded-circle">
                                    {{ $loop->iteration }}
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

                                <span class="badge-status pending">
                                    <i class="bi bi-hourglass-split me-1"></i>
                                    Pending
                                </span> 

                            </td>

                            {{-- TANGGAL --}}
                            <td data-dt-column="registered">

                                <div class="fw-semibold">                                    
                                    {{ $user->created_at->format('d F Y') }}                                    
                                </div>
                                <small class="text-muted">{{ $user->created_at->format('H:i') }} WIB</small>

                            </td>

                            {{-- AKSI --}}
                            <td data-dt-column="action">

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

                        <x-table.empty
                            :colspan="6"
                            title="Belum ada user menunggu persetujuan"
                            description="Belum terdapat user yang menunggu approval."
                        />

                    @endforelse

                </tbody>

            </table>

        </div>

        <x-table.pagination :paginator="$users->appends(request()->except('page'))" :show-summary="true" />

    </x-table.card>

</div>

@endsection