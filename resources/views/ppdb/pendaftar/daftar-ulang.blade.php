@extends('layouts.app')

@section('title','Daftar Ulang')

@section('content')

<div class="container-fluid">

    <div class="card">

        <div class="card-header">
            Data Daftar Ulang
        </div>

        <div class="card-body">

            <table class="table">

                <thead>
                    <tr>
                        <th>No Pendaftaran</th>
                        <th>Nama</th>
                        <th>Status Daftar Ulang</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($data as $item)

                    <tr>
                        <td>{{ $item->no_pendaftaran }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->status_daftar_ulang }}</td>
                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection