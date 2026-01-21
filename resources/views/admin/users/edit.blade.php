@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')

<h1 class="mb-4">Edit User</h1>

<div class="card">
    <div class="card-body">

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Nama</label>
                <input type="text"
                       name="name"
                       class="form-control"
                       value="{{ old('name', $user->name) }}"
                       required>
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email"
                       name="email"
                       class="form-control"
                       value="{{ old('email', $user->email) }}"
                       required>
            </div>

            <div class="mb-3">
                <label>Password (opsional)</label>
                <input type="password"
                       name="password"
                       class="form-control"
                       placeholder="Kosongkan jika tidak diubah">
            </div>

            <div class="mb-3">
                <label>Role</label>
                <select name="role" class="form-control" required>
                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="tu" {{ $user->role === 'tu' ? 'selected' : '' }}>Tata Usaha</option>
                    <option value="guru" {{ $user->role === 'guru' ? 'selected' : '' }}>Guru</option>
                    <option value="murid" {{ $user->role === 'murid' ? 'selected' : '' }}>Murid</option>
                </select>
            </div>

            <button class="btn btn-primary">Update</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary ms-2">Kembali</a>

        </form>

    </div>
</div>

@endsection
