@extends('layouts.app')

@section('title', 'Data Pendaftar PPDB')

@section('content')

<div class="container-fluid">

    <div class="card">

        <div class="card-header">

            Data Pendaftar PPDB

        </div>

        <div class="card-body">

            <table class="table">

                <thead>

                    <tr>
                        <th>No</th>
                        <th>No Pendaftaran</th>
                        <th>Nama</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>

                </thead>

                <tbody>

                    @foreach($data as $item)

                    <tr>

                        <td>{{ $loop->iteration }}</td>

                        <td>{{ $item->no_pendaftaran }}</td>

                        <td>{{ $item->nama }}</td>

                        <td>

                            <span class="badge bg-secondary">

                                {{ strtoupper($item->status) }}

                            </span>

                        </td>

                        <td>

                            <a
                                href="{{ route('tu.ppdb.pendaftar.show',$item) }}"
                                class="btn btn-sm btn-primary"
                            >
                                Detail
                            </a>

                            @if($item->status == 'pending')

                                <form
                                    action="{{ route('tu.ppdb.pendaftar.terima',$item) }}"
                                    method="POST"
                                    class="d-inline"
                                >
                                    @csrf

                                    <button
                                        class="btn btn-success btn-sm"
                                    >
                                        Terima
                                    </button>

                                </form>

                                <form
                                    action="{{ route('tu.ppdb.pendaftar.tolak',$item) }}"
                                    method="POST"
                                    class="d-inline"
                                >
                                    @csrf

                                    <button
                                        class="btn btn-danger btn-sm"
                                    >
                                        Tolak
                                    </button>

                                </form>

                            @endif

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

            {{ $data->links() }}

        </div>

    </div>

</div>

@endsection