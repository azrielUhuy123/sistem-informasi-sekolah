@extends('layouts.app')

@section('title', 'Detail Siswa')

@section('content')

<div class="container-fluid py-3">

    {{-- HEADER --}}
    <div class="mb-4">

        <h3 class="fw-bold mb-1">

            {{ $siswa->nama }}

        </h3>

        <small class="text-muted">

            NIS {{ $siswa->nis }}

        </small>

    </div>

    {{-- ABSENSI --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <h5 class="fw-bold mb-4">

                Rekap Absensi

            </h5>

            <div class="row text-center">

                <div class="col-md-3">

                    <h3 class="text-success">

                        {{ $hadir }}

                    </h3>

                    <small>Hadir</small>

                </div>

                <div class="col-md-3">

                    <h3 class="text-warning">

                        {{ $izin }}

                    </h3>

                    <small>Izin</small>

                </div>

                <div class="col-md-3">

                    <h3 class="text-info">

                        {{ $sakit }}

                    </h3>

                    <small>Sakit</small>

                </div>

                <div class="col-md-3">

                    <h3 class="text-danger">

                        {{ $alpha }}

                    </h3>

                    <small>Alpha</small>

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

                        @forelse($nilai as $n)

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

                        @empty

                            <tr>

                                <td colspan="5" class="text-center">

                                    Belum ada nilai

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    {{-- PERKEMBANGAN --}}
    <div class="card border-0 shadow-sm">

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

                    Belum ada perkembangan siswa

                </p>

            @endif

        </div>

    </div>

</div>

@endsection