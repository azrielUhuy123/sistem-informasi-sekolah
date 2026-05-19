@extends('layouts.app')

@section('title', 'Raport Siswa')

@section('content')

<div class="container-fluid py-3">

    {{-- HEADER --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h3 class="fw-bold mb-1">

                        RAPORT SISWA

                    </h3>

                    <small class="text-muted">

                        Tahun Ajaran
                        {{ current_tahun()?->tahun }}

                        -
                        Semester
                        {{ current_tahun()?->semester }}

                    </small>

                </div>

                <button
                    onclick="window.print()"
                    class="btn btn-success"
                >

                    Print Raport

                </button>

            </div>

        </div>

    </div>

    {{-- IDENTITAS --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <h5 class="fw-bold mb-4">

                Identitas Siswa

            </h5>

            <div class="row">

                <div class="col-md-6 mb-3">

                    <strong>Nama</strong>

                    <div>

                        {{ $siswa->nama }}

                    </div>

                </div>

                <div class="col-md-6 mb-3">

                    <strong>NIS</strong>

                    <div>

                        {{ $siswa->nis }}

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- NILAI --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <h5 class="fw-bold mb-4">

                Nilai Akademik

            </h5>

            <div class="table-responsive">

                <table class="table">

                    <thead class="table-light">

                        <tr>

                            <th>Mapel</th>
                            <th>Tugas</th>
                            <th>UTS</th>
                            <th>UAS</th>
                            <th>Nilai Akhir</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($nilai as $n)

                            <tr>

                                <td>

                                    {{ $n->mapel->nama_mapel }}

                                </td>

                                <td>

                                    {{ $n->nilai_tugas }}

                                </td>

                                <td>

                                    {{ $n->nilai_uts }}

                                </td>

                                <td>

                                    {{ $n->nilai_uas }}

                                </td>

                                <td>

                                    <strong>

                                        {{ $n->nilai_akhir }}

                                    </strong>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                    <tfoot>

                        <tr>

                            <th colspan="4">

                                Rata-Rata

                            </th>

                            <th>

                                {{ $rataRata }}

                            </th>

                        </tr>

                    </tfoot>

                </table>

            </div>

        </div>

    </div>

    {{-- ABSENSI --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <h5 class="fw-bold mb-4">

                Rekap Absensi

            </h5>

            <div class="row text-center">

                <div class="col-md-3">

                    <h4 class="text-success">

                        {{ $hadir }}

                    </h4>

                    <small>Hadir</small>

                </div>

                <div class="col-md-3">

                    <h4 class="text-warning">

                        {{ $izin }}

                    </h4>

                    <small>Izin</small>

                </div>

                <div class="col-md-3">

                    <h4 class="text-info">

                        {{ $sakit }}

                    </h4>

                    <small>Sakit</small>

                </div>

                <div class="col-md-3">

                    <h4 class="text-danger">

                        {{ $alpha }}

                    </h4>

                    <small>Alpha</small>

                </div>

            </div>

        </div>

    </div>

    {{-- PERKEMBANGAN --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <h5 class="fw-bold mb-4">

                Perkembangan Siswa

            </h5>

            @if($perkembangan)

                <div class="mb-3">

                    <strong>Karakter</strong>

                    <p class="mb-0">

                        {{ $perkembangan->karakter ?: '-' }}

                    </p>

                </div>

                <div class="mb-3">

                    <strong>Sosial</strong>

                    <p class="mb-0">

                        {{ $perkembangan->sosial ?: '-' }}

                    </p>

                </div>

                <div class="mb-3">

                    <strong>Disiplin</strong>

                    <p class="mb-0">

                        {{ $perkembangan->disiplin ?: '-' }}

                    </p>

                </div>

                <div class="mb-3">

                    <strong>Kebersihan</strong>

                    <p class="mb-0">

                        {{ $perkembangan->kebersihan ?: '-' }}

                    </p>

                </div>

                <div>

                    <strong>Catatan Umum</strong>

                    <p class="mb-0">

                        {{ $perkembangan->catatan ?: '-' }}

                    </p>

                </div>

            @else

                <p class="text-muted mb-0">

                    Belum ada catatan perkembangan

                </p>

            @endif

        </div>

    </div>

</div>

@endsection