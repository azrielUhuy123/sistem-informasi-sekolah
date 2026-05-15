@extends('layouts.app')

@section('title', 'Kelas Saya')

@section('content')

<div class="container-fluid py-3">

    <div class="mb-4">

        <h4 class="fw-bold mb-1">

            Kelas {{ $kelas->nama_kelas }}

        </h4>

        <small class="text-muted">

            Wali Kelas:
            {{ $waliKelas->guru->nama }}

        </small>

    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table align-middle">

                    <thead class="table-light">

                        <tr>

                            <th width="80">
                                No
                            </th>

                            <th>
                                NIS
                            </th>

                            <th>
                                NISN
                            </th>

                            <th>
                                Nama Siswa
                            </th>
                            <th>
                                Tanggal Lahir
                            <th>
                                Jenis Kelamin
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($penempatan as $i => $p)

                            <tr>

                                <td>

                                    {{ $i + 1 }}

                                </td>

                                <td>

                                    {{ $p->siswa->nis }}

                                </td>

                                <td>

                                    {{ $p->siswa->nisn }}

                                </td>

                                <td>

                                    {{ $p->siswa->nama }}

                                </td>

                                <td>

                                    {{ $p->siswa->tanggal_lahir }}

                                </td>

                                <td>

                                    {{ $p->siswa->jenis_kelamin }}

                                </td>
                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="4"
                                    class="text-center text-muted py-4"
                                >

                                    Belum ada siswa

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection