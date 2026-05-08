@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')

<div class="container-fluid">

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <h4 class="mb-2">
                Dashboard Guru
            </h4>

            <p class="text-muted mb-0">
                Selamat datang {{ auth()->user()->name }}
            </p>

        </div>
    </div>

</div>

@endsection