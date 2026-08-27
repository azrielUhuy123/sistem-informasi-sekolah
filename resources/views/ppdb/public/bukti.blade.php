@extends('layouts.public')

@section('title','Bukti Pendaftaran')

@section('content')

<style>
    .card-body {
        padding: 2.5rem;
    }
    .table-responsive {
        overflow-x: auto;
    }
    @media (max-width: 768px) {
        .card-body {
            padding: 2rem 1.5rem;
        }
    }
    @media (max-width: 576px) {
        .card-body {
            padding: 1.25rem 1rem;
        }
        .alert {
            font-size: 0.95rem;
        }
        .table th,
        .table td {
            padding: 0.75rem;
        }
        .btn-lg {
            width: 100%;
        }
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold text-primary">
                            BUKTI PENDAFTARAN PPDB
                        </h2>
                        <h5>
                            SDN Mekar Jaya
                        </h5>
                        <p class="text-muted">
                            Tahun Ajaran {{ date('Y') }}/{{ date('Y') + 1 }}
                        </p>
                    </div>

                    <div class="alert alert-success">
                        <strong>Pendaftaran Berhasil!</strong>
                        Simpan Nomor Pendaftaran berikut
                        untuk mengecek status pendaftaran.
                    </div>

                    <div class="text-center mb-4">
                        <h1 class="fw-bold text-primary">
                            {{ $data->no_pendaftaran }}
                        </h1>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
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
                                    Nama Lengkap
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
                                    Tanggal Pendaftaran
                                </th>
                                <td>
                                    {{ \Carbon\Carbon::parse($data->tanggal_pendaftaran)->format('d F Y') }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Status
                                </th>
                                <td>
                                    <span class="badge bg-warning fs-6">
                                        MENUNGGU VERIFIKASI
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="bg-light rounded p-4 mt-4">
                        <h6 class="fw-bold">
                            Langkah Selanjutnya
                        </h6>
                        <ol class="mb-0">
                            <li>
                                Simpan Nomor Pendaftaran Anda.
                            </li>
                            <li>
                                Informasi perkembangan pendaftaran juga akan dikirim ke email
                                yang Anda daftarkan.
                            </li>
                            <li>
                                Gunakan Nomor Pendaftaran tersebut untuk mengecek status melalui menu
                                <b>Cek Status PPDB</b>.
                            </li>
                            <li>
                                Jika dinyatakan diterima, lakukan daftar ulang.
                            </li>
                        </ol>
                    </div>

                    <div class="text-center mt-4">
                        <button
                            onclick="window.print()"
                            class="btn btn-primary btn-lg"
                        >
                            Cetak Bukti Pendaftaran
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection