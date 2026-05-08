@extends('layouts.app')

@section('title', 'Dashboard Kepala Sekolah')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Dashboard Kepala Sekolah</h1>
    <span class="badge bg-primary">
        Login sebagai <b>{{ Auth::user()->role }}</b>
    </span>
</div>

<div class="row g-4">

    <div class="col-md-4">
        <div class="card shadow-sm p-3">
            <h6 class="text-muted mb-1">Total User</h6>
            <h3 class="fw-bold">{{ $totalUser }}</h3>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm p-3">
            <h6 class="text-muted mb-1">User Pending</h6>
            <h3 class="fw-bold text-warning">{{ $pendingUser }}</h3>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm p-3">
            <h6 class="text-muted mb-1">User Aktif</h6>
            <h3 class="fw-bold text-success">{{ $activeUser }}</h3>
        </div>
    </div>

</div>

@endsection
