@extends('layouts.app')

@section('title', 'Tautan Reset Password')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="h4">Tautan Reset Password</h1>
                <p>
                    Berikan tautan ini hanya kepada pemilik akun melalui kanal yang aman.
                    Tautan hanya dapat digunakan sekali dan berlaku selama
                    {{ config('auth.passwords.users.expire') }} menit.
                </p>
                <p class="mb-1">Akun: {{ $user->username }}</p>
                <a href="{{ $resetUrl }}" rel="noreferrer">{{ $resetUrl }}</a>
            </div>
        </div>
    </div>
@endsection
