@extends('layouts.admin')

@section('title', 'Tambah User')

@section('content')

<h1 class="mb-4">Tambah User</h1>

<div class="card">
    <div class="card-body">

        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Nama</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Role</label>
                <input type="hidden" name="role" value="admin">
                <p class="form-control-plaintext mb-0">Admin sistem (akun teknis)</p>
            </div>

            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary ms-2">Kembali</a>

        </form>

    </div>
</div>

@endsection
