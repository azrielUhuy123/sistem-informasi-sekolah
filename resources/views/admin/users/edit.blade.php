@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')

<h1 class="mb-4">Edit User</h1>

<div class="card">
    <div class="card-body">

        <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
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
                <label>Role</label>
                <select name="role" class="form-control" required>
                    <option value="admin" {{ $user->role=='admin'?'selected':'' }}>Admin</option>
                    <option value="tu" {{ $user->role=='tu'?'selected':'' }}>Tata Usaha</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Password Baru <small class="text-muted">(opsional)</small></label>
                <input type="password"
                       name="password"
                       class="form-control"
                       placeholder="Kosongkan jika tidak diubah">
            </div>

            <div class="d-flex gap-2">
                <button class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
            </div>

        </form>

    </div>
</div>

@endsection
