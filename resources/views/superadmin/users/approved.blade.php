@extends('layouts.app')

@section('title', 'User Aktif')

@section('content')
<div class="container-fluid">

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">User Aktif</h5>
        </div>

        <div class="card-body">

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Tanggal Approve</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($users as $user)
                        <tr>

                            <td>{{ $user->name }}</td>

                            <td>{{ $user->username }}</td>

                            <td>
                                {{ ucfirst($user->role) }}
                            </td>

                            <td>
                                @if($user->is_active)
                                    <span class="badge bg-success">
                                        Aktif
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        Diblokir
                                    </span>
                                @endif
                            </td>

                            <td>
                                {{ $user->approved_at?->format('d/m/Y H:i') ?? '-' }}
                            </td>

                            <td>

                                @if($user->is_active)

                                    <form
                                        action="{{ route('superadmin.users.block', $user) }}"
                                        method="POST"
                                        onsubmit="return confirm('Blokir user ini?')"
                                    >
                                        @csrf

                                        <button
                                            type="submit"
                                            class="btn btn-danger btn-sm"
                                        >
                                            Blokir
                                        </button>
                                    </form>

                                @else

                                    <form
                                        action="{{ route('superadmin.users.unblock', $user) }}"
                                        method="POST"
                                        onsubmit="return confirm('Aktifkan kembali user ini?')"
                                    >
                                        @csrf

                                        <button
                                            type="submit"
                                            class="btn btn-success btn-sm"
                                        >
                                            Aktifkan
                                        </button>
                                    </form>

                                @endif

                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                Tidak ada data
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $users->links() }}
            </div>

        </div>
    </div>

</div>
@endsection