@extends('layouts.app')

@section('title', 'Edit Akses Fitur')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <h3 class="fw-bold mb-2">Akses Fitur Pengguna</h3>
            <p class="mb-0 opacity-75">{{ $user->pegawai?->nama ?? $user->name }} - {{ strtoupper($user->role) }}</p>
        </div>
    </div>

    <x-alert.flash key="success" type="success" />

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('superadmin.feature-access.update', $user) }}">
                @csrf
                @method('PUT')

                @foreach($features as $key => $feature)
                    @if(in_array($user->role, $feature['roles'] ?? [], true) || $permissions->has($key))
                        <div class="d-flex align-items-center justify-content-between border-bottom py-3">
                            <div>
                                <div class="fw-semibold">{{ $feature['label'] }}</div>
                                <small class="text-muted">Kunci fitur: {{ $key }}</small>
                            </div>
                            <div class="form-check form-switch">
                                <input
                                    type="checkbox"
                                    class="form-check-input"
                                    name="permissions[{{ $key }}]"
                                    value="1"
                                    @checked($effective[$key])
                                >
                            </div>
                        </div>
                    @endif
                @endforeach

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('superadmin.feature-access.index') }}" class="btn btn-outline-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan Akses</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
