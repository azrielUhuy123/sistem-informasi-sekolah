@extends('layouts.app')

@section('title','Detail Pendaftar')

@section('content')

<div class="container-fluid">

    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">

            Detail Pendaftar

        </div>

        @if($pendaftar->status == 'pending')

            <form
                action="{{ route('tu.ppdb.pendaftar.terima',$pendaftar) }}"
                method="POST"
                class="d-inline"
            >
                @csrf
                <a
                    href="{{ route('tu.ppdb.pendaftar.verifikasi',$pendaftar) }}"
                    class="btn btn-warning"
                >
                    Verifikasi Berkas
                </a>

                <button
                    class="btn btn-success"
                >
                    Terima
                </button>

            </form>

            <form
                action="{{ route('tu.ppdb.pendaftar.tolak',$pendaftar) }}"
                method="POST"
                class="d-inline"
            >
                @csrf

                <button
                    class="btn btn-danger"
                >
                    Tolak
                </button>

            </form>

            @endif

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

            <h5>Data Ayah</h5>

                <table class="table">

                <tr>
                    <th>Nama</th>
                    <td>{{ optional($pendaftar->ayah)->nama }}</td>
                </tr>

                <tr>
                    <th>Pekerjaan</th>
                    <td>{{ optional($pendaftar->ayah)->pekerjaan }}</td>
                </tr>

                <tr>
                    <th>Penghasilan</th>
                    <td>{{ optional($pendaftar->ayah)->penghasilan }}</td>
                </tr>

            </table>

            <h5>Data Ibu</h5>

            <table class="table">

                <tr>
                    <th>Nama</th>
                    <td>{{ optional($pendaftar->ibu)->nama }}</td>
                </tr>

                <tr>
                    <th>Pekerjaan</th>
                    <td>{{ optional($pendaftar->ibu)->pekerjaan }}</td>
                </tr>

                <tr>
                    <th>Penghasilan</th>
                    <td>{{ optional($pendaftar->ibu)->penghasilan }}</td>
                </tr>

            </table>

        </div>

    </div>

</div>

@endsection