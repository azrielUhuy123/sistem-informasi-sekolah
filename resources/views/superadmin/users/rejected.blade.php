@extends('layouts.app')

@section('title', 'User Ditolak')

@section('content')
<div class="container-fluid">

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">User Ditolak</h5>
        </div>

        <div class="card-body">

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($users as $user)
                        <tr>

                            <td>{{ $user->name }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ ucfirst($user->role) }}</td>

                            <td>
                                <span class="badge bg-danger">
                                    Ditolak
                                </span>
                            </td>

                            <td>

                                <form
                                    action="{{ route('superadmin.users.approve', $user) }}"
                                    method="POST"
                                    onsubmit="return confirm('Setujui user ini?')"
                                >
                                    @csrf

                                    <button
                                        type="submit"
                                        class="btn btn-success btn-sm"
                                    >
                                        Setujui
                                    </button>

                                </form>

                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">
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