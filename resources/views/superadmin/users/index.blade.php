@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="container-fluid dt-siswa-page">
    <x-table.page-header
        icon="bi-people"
        eyebrow="Manajemen Akun / Pengguna"
        title="Manajemen Pengguna"
        description="Kelola akun teknis dan Guru."
    />

    <x-table.card
        title="Manajemen Admin dan Guru"
        subtitle="Kelola akun teknis dan Guru"
        :count="$users->total()"
        count-label="user"
    >
        <x-slot:toolbar>
            <x-table.toolbar
                table-id="dt-superadmin-users-table"
                :column-toggle="true"
                :refreshable="true"
                :refresh-url="request()->fullUrl()"
            >
                <x-slot:columnMenu>
                    @foreach ([
                        'name' => 'Nama',
                        'email' => 'Email',
                        'role' => 'Role',
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
            <table id="dt-superadmin-users-table" class="table table-hover align-middle dt-table">
                <colgroup>
                    <col data-dt-column="name">
                    <col data-dt-column="email">
                    <col data-dt-column="role">
                    <col data-dt-column="action">
                </colgroup>
                <thead>
                    <tr>
                        <x-table.heading column="name">Nama</x-table.heading>
                        <x-table.heading column="email">Email</x-table.heading>
                        <x-table.heading column="role">Role</x-table.heading>
                        <x-table.heading column="action">Aksi</x-table.heading>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                        <tr>
                            <td data-dt-column="name">{{ $u->name }}</td>
                            <td data-dt-column="email">{{ $u->email }}</td>
                            <td data-dt-column="role">
                                <span class="badge bg-info">{{ $u->role }}</span>
                            </td>
                            <td data-dt-column="action">
                                <a href="{{ route('superadmin.users.edit', $u) }}" class="btn btn-sm btn-warning">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <x-table.empty
                            :colspan="4"
                            title="Belum ada pengguna"
                            description="Belum ada akun admin atau Guru yang dapat ditampilkan."
                        />
                    @endforelse
                </tbody>
            </table>
        </div>

        <x-table.pagination :paginator="$users->appends(request()->except('page'))" :show-summary="true" />
    </x-table.card>
</div>
@endsection
