@extends('layouts.app')

@section('title','Dashboard PPDB')

@section('content')

<div class="container-fluid">

    <div class="row g-3">

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6>Total Pendaftar</h6>
                    <h2>{{ $total }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6>Pending</h6>
                    <h2>{{ $pending }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6>Diterima</h6>
                    <h2>{{ $diterima }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6>Ditolak</h6>
                    <h2>{{ $ditolak }}</h2>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection