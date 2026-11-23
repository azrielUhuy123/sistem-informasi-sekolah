@extends('layouts.app')

@section('title', 'Akses Fitur Pengguna')

@section('content')
<div class="container-fluid dt-siswa-page">
    <x-table.page-header
        icon="bi-shield-lock"
        eyebrow="Manajemen Akun / Akses Fitur"
        title="Akses Fitur Pengguna"
        description="Atur hak akses fitur per pengguna dalam menu Penugasan."
    />

    <x-alert.flash key="success" type="success" />

    <x-table.card
        title="Daftar Pengguna"
        subtitle="Atur hak akses fitur untuk setiap pengguna"
        :count="$users->total()"
        count-label="pengguna"
    >
        <x-slot:toolbar>
            <x-table.toolbar table-id="dt-superadmin-feature-access-table" :column-toggle="true">
                <x-slot:columnMenu>
                    @foreach ([
                        'user' => 'Nama pengguna',
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
            <table id="dt-superadmin-feature-access-table" class="table table-hover align-middle dt-table">
                <thead>
                    <tr>
                        <x-table.heading column="user">Nama</x-table.heading>
                        <x-table.heading column="role">Role</x-table.heading>
                        <x-table.heading column="status">Status</x-table.heading>
                        <x-table.heading width="150px" column="action">Aksi</x-table.heading>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td data-dt-column="user">
                                <div class="fw-semibold">{{ $user->pegawai?->nama ?? $user->name }}</div>
                                <small class="text-muted">{{ $user->username ?? $user->email }}</small>
                            </td>
                            <td data-dt-column="role"><span class="badge bg-primary-subtle text-primary">{{ strtoupper($user->role) }}</span></td>
                            <td data-dt-column="status">
                                @if($user->isApproved())
                                    <span class="badge bg-success">Disetujui</span>
                                @else
                                    <span class="badge bg-secondary">{{ $user->approval_status }}</span>
                                @endif
                            </td>
                            <td data-dt-column="action">
                                <a href="{{ route('superadmin.feature-access.edit', $user) }}" class="btn btn-sm btn-primary rounded-pill">
                                    Atur Akses
                                </a>
                            </td>
                        </tr>
                    @empty
                        <x-table.empty
                            :colspan="4"
                            title="Belum ada pengguna"
                            description="Belum ada pengguna yang dapat diatur akses fiturnya."
                        />
                    @endforelse
                </tbody>
            </table>
        </div>

        <x-table.pagination :paginator="$users" :show-summary="true" />
    </x-table.card>
</div>
@endsection
