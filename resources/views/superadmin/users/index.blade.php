@extends('layouts.app')

@section('title', 'Manajemen Admin & TU')

@section('content')
<div class="container-fluid dt-siswa-page">
    <x-table.page-header
        icon="bi-people"
        eyebrow="Manajemen Akun / Pengguna"
        title="Manajemen Admin & TU"
        description="Kelola akun administrator dan tata usaha."
    />

    <x-table.card
        title="Manajemen Admin & Tata Usaha"
        subtitle="Kelola akun administrator dan tata usaha"
        :count="$users->total()"
        count-label="user"
    >
        <x-slot:toolbar>
            <x-table.toolbar table-id="dt-superadmin-users-table" :column-toggle="true">
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
                            description="Belum ada akun admin atau tata usaha yang dapat ditampilkan."
                        />
                    @endforelse
                </tbody>
            </table>
        </div>

        <x-table.pagination :paginator="$users->appends(request()->except('page'))" :show-summary="true" />
    </x-table.card>
</div>
@endsection
