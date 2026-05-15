@extends('layouts.app')

@section('title', 'Approval User')

@section('content')

<div class="container-fluid">

    <div class="mb-4">
        <h4 class="fw-bold mb-1">
            Approval User
        </h4>

        <small class="text-muted">
            Daftar user menunggu persetujuan
        </small>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <table class="table align-middle">

                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th width="220">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($users as $user)

                        <tr>

                            <td>
                                {{ $user->name }}
                            </td>

                            <td>
                                {{ $user->email }}
                            </td>

                            <td>
                                <x-status-badge type="warning">
                                    {{ $user->role }}
                                </x-layouts.components.status-badge>
                            </td>

                            <td>

                                <form
                                    action="{{ route('superadmin.users.approve', $user->id) }}"
                                    method="POST"
                                    class="d-inline"
                                >

                                    @csrf
                                    @method('PATCH')

                                    <button class="btn btn-success btn-sm">
                                        Approve
                                    </button>

                                </form>

                                <form
                                    action="{{ route('superadmin.users.reject', $user->id) }}"
                                    method="POST"
                                    class="d-inline"
                                >

                                    @csrf
                                    @method('PATCH')

                                    <button class="btn btn-danger btn-sm">
                                        Reject
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                Tidak ada user pending
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>
    </div>

</div>

@endsection