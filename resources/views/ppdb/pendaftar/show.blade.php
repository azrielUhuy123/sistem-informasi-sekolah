@extends('layouts.app')

@section('title','Detail Pendaftar')

@section('content')

<div class="container-fluid">

    <div class="card">

        <div class="card-header">

            Detail Pendaftar

        </div>

        <div class="card-body">

            <table class="table">

                <tr>
                    <th>Nama</th>
                    <td>{{ $pendaftar->nama }}</td>
                </tr>

                <tr>
                    <th>NIK</th>
                    <td>{{ $pendaftar->nik }}</td>
                </tr>

                <tr>
                    <th>No KK</th>
                    <td>{{ $pendaftar->nomor_kk }}</td>
                </tr>

                <tr>
                    <th>Status</th>
                    <td>{{ strtoupper($pendaftar->status) }}</td>
                </tr>

            </table>

        </div>

    </div>

</div>

@endsection