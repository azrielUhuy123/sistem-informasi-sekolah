@extends('layouts.app')

@section('title', 'Absensi Ekskul')

@section('content')

<div class="container-fluid py-4">

    <div class="mb-4">

        <h3 class="fw-bold mb-1">

            Absensi Ekskul

        </h3>

        <p class="text-muted mb-0">

            {{ $ekskul->nama }}

        </p>

    </div>

    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body text-center">

                    <div class="text-muted">

                        Hadir

                    </div>

                    <h3 class="fw-bold text-success">

                        {{ $hadir }}

                    </h3>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body text-center">

                    <div class="text-muted">

                        Izin

                    </div>

                    <h3 class="fw-bold text-primary">

                        {{ $izin }}

                    </h3>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body text-center">

                    <div class="text-muted">

                        Sakit

                    </div>

                    <h3 class="fw-bold text-warning">

                        {{ $sakit }}

                    </h3>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body text-center">

                    <div class="text-muted">

                        Alpa

                    </div>

                    <h3 class="fw-bold text-danger">

                        {{ $alpa }}

                    </h3>

                </div>

            </div>

        </div>

    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>

                        <tr>

                            <th>No</th>

                            <th>Tanggal</th>

                            <th>Status</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($absensi as $item)

                            <tr>

                                <td>

                                    {{ $absensi->firstItem() + $loop->index }}

                                </td>

                                <td>

                                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}

                                </td>

                                <td>

                                    @if($item->status == 'hadir')

                                        <span class="badge bg-success">

                                            Hadir

                                        </span>

                                    @elseif($item->status == 'izin')

                                        <span class="badge bg-primary">

                                            Izin

                                        </span>

                                    @elseif($item->status == 'sakit')

                                        <span class="badge bg-warning text-dark">

                                            Sakit

                                        </span>

                                    @else

                                        <span class="badge bg-danger">

                                            Alpa

                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="3"
                                    class="text-center text-muted py-4"
                                >

                                    Belum ada data absensi

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-3">

                {{ $absensi->links() }}

            </div>

        </div>

    </div>

    <div class="mt-4">

        <a
            href="{{ route('siswa.ekskul.index') }}"
            class="btn btn-light"
        >

            Kembali

        </a>

    </div>

</div>

@endsection