@extends('layouts.app')

@section('title', 'Profile Saya')

@section('content')

<div class="container-fluid">
    <div class="mb-4">
        <h2 class="fw-bold">Profile Saya</h2>
        <p class="text-muted">Informasi akun pengguna</p>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 text-center">
                    <img
                        src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}"
                        class="rounded-circle border"
                        width="140"
                        height="140"
                    >
                </div>

                <div class="col-md-9">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Nama</label>
                            <h5>{{ $user->name }}</h5>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Username</label>
                            <h5>{{ $user->username ?? '-' }}</h5>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Role</label>
                            <h5>{{ strtoupper($user->role) }}</h5>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Email</label>
                            <h5>{{ $user->email ?? '-' }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($user->siswa)
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header">Data Siswa</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label>NIS</label>
                        <div>{{ $user->siswa->nis }}</div>
                    </div>

                    <div class="col-md-4">
                        <label>NISN</label>
                        <div>{{ $user->siswa->nisn ?? '-' }}</div>
                    </div>

                    <div class="col-md-4">
                        <label>Tanggal Lahir</label>
                        <div>{{ $user->siswa->tanggal_lahir }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($user->guru)
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header">Data Guru</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label>NIP</label>
                        <div>{{ $user->guru->nip }}</div>
                    </div>

                    <div class="col-md-4">
                        <label>Jenis Kelamin</label>
                        <div>{{ $user->guru->jenis_kelamin }}</div>
                    </div>

                    <div class="col-md-4">
                        <label>Telepon</label>
                        <div>{{ $user->guru->telepon ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="card border-0 shadow-sm mt-4">

        <div class="card-header">
            Informasi Kontak
        </div>

        <div class="card-body">

            <form
                method="POST"
                action="{{ route('profile.update') }}"
            >
                @csrf

                <div class="row g-3">

                    <div class="col-md-6">

                        <label>Email</label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            value="{{ old('email', $user->email) }}"
                        >

                    </div>

                    <div class="col-md-6">

                        <label>No HP</label>

                        <input
                            type="text"
                            name="telepon"
                            class="form-control"
                            value="{{ old('telepon', $user->telepon) }}"
                        >

                    </div>

                    <div class="col-md-12">

                        <label>Alamat</label>

                        <textarea
                            name="alamat"
                            rows="3"
                            class="form-control"
                        >{{ old('alamat', $user->alamat) }}</textarea>

                    </div>

                </div>

                <div class="mt-3">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Simpan Perubahan
                    </button>

                </div>

            </form>

        </div>

    </div>


    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header">Ubah Password</div>
        <div class="card-body">
            @include('profile.partials.update-password-form')
        </div>
    </div>
</div>

@endsection