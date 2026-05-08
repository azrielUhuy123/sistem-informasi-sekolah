@extends('layouts.app')

@section('title', 'Edit User')

@section('content')

<h3>Edit User</h3>

<form method="POST" action="{{ route('superadmin.users.update', $user) }}">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Nama</label>
        <input class="form-control" name="name" value="{{ $user->name }}">
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input class="form-control" name="email" value="{{ $user->email }}">
    </div>

    <div class="mb-3">
        <label>Role</label>
        <select class="form-select" name="role">
            <option value="admin" {{ $user->role=='admin'?'selected':'' }}>Admin</option>
            <option value="tu" {{ $user->role=='tu'?'selected':'' }}>Tata Usaha</option>
        </select>
    </div>

    <button class="btn btn-primary">Simpan</button>
    <a href="{{ route('superadmin.users.index') }}" class="btn btn-secondary">Kembali</a>
</form>

@endsection
