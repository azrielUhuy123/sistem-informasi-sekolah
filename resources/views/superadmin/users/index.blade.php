@extends('layouts.app')

@section('title', 'Manajemen Admin & TU')

@section('content')

<h3>Manajemen Admin & Tata Usaha</h3>

<table class="table table-bordered mt-3">
    <thead>
        <tr>
            <th>Nama</th>
            <th>Email</th>
            <th>Role</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $u)
        <tr>
            <td>{{ $u->name }}</td>
            <td>{{ $u->email }}</td>
            <td>
                <span class="badge bg-info">{{ $u->role }}</span>
            </td>
            <td>
                <a href="{{ route('superadmin.users.edit', $u) }}"
                   class="btn btn-sm btn-warning">
                    Edit
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $users->links() }}

@endsection
