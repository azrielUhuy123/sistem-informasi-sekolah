@extends('layouts.app')

@section('content')

<div class="container py-5">

    <div class="card shadow">

        <div class="card-body">

            <h2>

                Cek Status PPDB

            </h2>

            <form
                method="POST"
                action="{{ route('ppdb.status.cek') }}"
            >

                @csrf

                <div class="mb-3">

                    <label>

                        Nomor Pendaftaran / NIK

                    </label>

                    <input
                        type="text"
                        name="keyword"
                        class="form-control"
                        placeholder="Masukkan Nomor Pendaftaran atau NIK"
                        required
                    >

                </div>

                <button
                    type="submit"
                    class="btn btn-primary"
                >

                    Cek Status

                </button>

            </form>

            @isset($data)

                <hr>

                <h5>

                    Hasil Pencarian

                </h5>

                <table class="table">

                    <tr>
                        <th>Nama</th>
                        <td>{{ $data->nama }}</td>
                    </tr>

                    <tr>
                        <th>No Pendaftaran</th>
                        <td>{{ $data->no_pendaftaran }}</td>
                    </tr>

                    <tr>
                        <th>Status</th>
                        <td>

                            @if($data->status == 'pending')

                                <span class="badge bg-warning">

                                    Pending

                                </span>

                            @elseif($data->status == 'diterima')

                                <span class="badge bg-success">

                                    Diterima

                                </span>

                            @elseif($data->status == 'ditolak')

                                <span class="badge bg-danger">

                                    Ditolak

                                </span>

                            @endif

                        </td>
                    </tr>

                </table>

            @endisset

        </div>

    </div>

</div>

@endsection