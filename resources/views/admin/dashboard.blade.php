@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Dashboard Admin</h1>
    <span class="badge bg-danger">
        Login sebagai <b>{{ Auth::user()->role }}</b>
    </span>
</div>

<div class="row g-4">

    <!-- TOTAL USER -->
    <div class="col-md-4">
        <div class="card shadow-sm p-3">
            <h6 class="text-muted mb-1">Total User</h6>
            <h3 class="fw-bold">{{ \App\Models\User::count() }}</h3>
        </div>
    </div>

    <!-- ROLE AKTIF -->
    <div class="col-md-4">
        <div class="card shadow-sm p-3">
            <h6 class="text-muted mb-1">Role Aktif</h6>
            <h3 class="fw-bold">
                {{ \App\Models\User::select('role')->distinct()->count() }}
            </h3>
        </div>
    </div>

    <!-- STATUS SISTEM -->
    <div class="col-md-4">
        <div class="card shadow-sm p-3">
            <h6 class="text-muted mb-1">Status Sistem</h6>
            <h5 class="fw-semibold text-success">Aktif</h5>
        </div>
    </div>

</div>

@endsection
