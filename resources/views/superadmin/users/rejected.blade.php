@extends('layouts.app')

@section('title', 'User Ditolak')

@section('content')

<div class="container-fluid dt-siswa-page">

    <x-table.page-header
        icon="bi-person-x"
        eyebrow="Manajemen Akun / Pengguna"
        title="User Ditolak"
        description="Daftar akun yang ditolak dan dapat ditinjau kembali oleh administrator."
    />

    {{-- ALERT --}}
    <x-alert.flash key="success" type="success"/>
    <x-alert.flash key="error" type="danger"/>

    {{-- TABLE --}}
    <x-table.card
        title="Daftar User Ditolak"
        subtitle="Seluruh akun yang ditolak selama proses persetujuan pengguna"
        :count="$users->total()"
    >

        <x-slot:toolbar>
            <x-table.toolbar table-id="dt-superadmin-users-rejected-table" :column-toggle="true">
                <x-slot:columnMenu>
                    @foreach ([
                        'number' => 'No',
                        'user' => 'User',
                        'role' => 'Role',
                        'status' => 'Status',
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

            <table id="dt-superadmin-users-rejected-table" class="table table-hover align-middle mb-0 dt-table">

                <thead>
                    <tr>
                        <x-table.heading class="text-center" width="70px" column="number">No</x-table.heading>
                        <x-table.heading column="user">User</x-table.heading>
                        <x-table.heading width="130px" column="role">Role</x-table.heading>
                        <x-table.heading width="140px" column="status">Status</x-table.heading>
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

                                <span class="badge-status nonaktif">
                                    <i class="bi bi-x-circle-fill me-1"></i>
                                    Ditolak
                                </span>

                            </td>

                            {{-- AKSI --}}
                            <td data-dt-column="action">

                                <form
                                    action="{{ route('superadmin.users.approve', $user) }}"
                                    method="POST"
                                >
                                    @csrf
                                    @method('PATCH')

                                    <button
                                        type="submit"
                                        class="btn btn-success btn-sm rounded-pill px-3 py-0 btn-confirm"
                                        data-title="Setujui User"
                                        data-message="User ini akan disetujui dan dapat mengakses sistem."
                                        data-button="btn-success"
                                    >                                        
                                        Setujui
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <x-table.empty
                            :colspan="5"
                            title="Belum ada akun yang ditolak"
                            description="Belum terdapat akun yang berstatus ditolak."
                        />

                    @endforelse

                </tbody>

            </table>

        </div>

        <x-table.pagination :paginator="$users->appends(request()->except('page'))" :show-summary="true" />

    </x-table.card>

</div>

@endsection