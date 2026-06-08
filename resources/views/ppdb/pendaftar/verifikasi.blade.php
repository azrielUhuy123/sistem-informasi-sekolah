@extends('layouts.app')

@section('title','Verifikasi Berkas')

@section('content')

<div class="container-fluid">

    <div class="card">

        <div class="card-header">

            Verifikasi Berkas

        </div>

        <div class="card-body">

            <table class="table table-bordered">

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

                        <td>{{ strtoupper($item->status) }}</td>

                        <td>

                            <a
                                href="{{ route('tu.ppdb.pendaftar.form-verifikasi',$item) }}"
                                class="btn btn-primary btn-sm"
                            >
                                Verifikasi
                            </a>

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