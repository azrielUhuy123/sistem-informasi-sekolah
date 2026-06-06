@extends('layouts.app')

@section('title','Data Diterima')

@section('content')

<div class="container-fluid">

    <div class="card shadow-sm">

        <div class="card-header">

            <h5 class="mb-0">
                Data Pendaftar Diterima
            </h5>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered">

                    <thead>

                        <tr>

                            <th>No</th>
                            <th>No Pendaftaran</th>
                            <th>Nama</th>
                            <th>Tanggal Daftar</th>
                            <th>NIS</th>
                            <th>Aksi</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($data as $item)

                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>

                            <td>
                                {{ $item->no_pendaftaran }}
                            </td>

                            <td>
                                {{ $item->nama }}
                            </td>

                            <td>
                                {{ $item->tanggal_pendaftaran }}
                            </td>

                            <td>
                                {{ $item->siswa?->nis }}
                            </td>

                            <td>

                                <a
                                    href="{{ route('tu.ppdb.pendaftar.show',$item) }}"
                                    class="btn btn-sm btn-primary"
                                >
                                    Detail
                                </a>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="6" class="text-center">

                                Tidak ada data

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{ $data->links() }}

        </div>

    </div>

</div>

@endsection