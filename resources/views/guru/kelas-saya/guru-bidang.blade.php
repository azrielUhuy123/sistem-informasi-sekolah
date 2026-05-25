@extends('layouts.app')

@section('title', 'Kelas Diampu')

@section('content')

<div class="container-fluid py-3">

    <div class="mb-4">

        <h4 class="fw-bold mb-1">

            Kelas Diampu

        </h4>

        <small class="text-muted">

            Monitoring kelas dan mata pelajaran yang Anda ampu

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

                                Kelas

                            </th>

                            <th>

                                Mata Pelajaran

                            </th>

                            <th>

                                Total Siswa

                            </th>

                            <th>

                                Progress Nilai

                            </th>

                            <th width="240">

                                Aksi

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($kelasDiampu as $i => $item)

                            <tr>

                                <td>

                                    {{ $i + 1 }}

                                </td>

                                <td>

                                    {{ $item->kelas->nama_kelas }}

                                </td>

                                <td>

                                    {{ $item->mapel->nama_mapel }}

                                </td>

                                <td>

                                    {{ $item->total_siswa }}

                                </td>

                                <td>

                                    <span class="fw-semibold">

                                        {{ $item->nilai_terisi }}

                                    </span>

                                    /

                                    {{ $item->total_siswa }}

                                </td>

                                <td class="d-flex gap-2">

                                    <a
                                        href="{{ route(
                                            'guru.nilai.index',
                                            [
                                                'kelas_id' => $item->kelas_id,
                                                'mapel_id' => $item->mapel_id,
                                            ]
                                        ) }}"
                                        class="btn btn-primary btn-sm"
                                    >

                                        Input Nilai

                                    </a>

                                    <a
                                        href="{{ route(
                                            'guru.nilai.index',
                                            [
                                                'kelas_id' => $item->kelas_id,
                                                'mapel_id' => $item->mapel_id,
                                            ]
                                        ) }}"
                                        class="btn btn-outline-secondary btn-sm"
                                    >

                                        Lihat Siswa

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="text-center text-muted py-4"
                                >

                                    Belum ada kelas diampu

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