@extends('layouts.public')

@section('title','Bukti Pendaftaran')

@section('content')

<div class="container py-5">

    <div class="card shadow">

        <div class="card-body">

            <div class="text-center mb-4">

                <h2>
                    Bukti Pendaftaran PPDB
                </h2>

                <h5>
                    SDN Mekar Jaya
                </h5>

            </div>

            <table class="table">

                <tr>
                    <th width="250">
                        Nomor Pendaftaran
                    </th>

                    <td>
                        {{ $data->no_pendaftaran }}
                    </td>
                </tr>

                <tr>
                    <th>
                        Nama
                    </th>

                    <td>
                        {{ $data->nama }}
                    </td>
                </tr>

                <tr>
                    <th>
                        NIK
                    </th>

                    <td>
                        {{ $data->nik }}
                    </td>
                </tr>

                <tr>
                    <th>
                        Status
                    </th>

                    <td>

                        <span class="badge bg-warning">

                            {{ strtoupper($data->status) }}

                        </span>

                    </td>
                </tr>

            </table>

            <div class="alert alert-info">

                Simpan nomor pendaftaran ini untuk
                mengecek status PPDB.

            </div>

            <button
                onclick="window.print()"
                class="btn btn-primary"
            >

                Cetak Bukti

            </button>

        </div>

    </div>

</div>

@endsection