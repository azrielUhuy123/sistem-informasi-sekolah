@extends('layouts.app')

@section('title', 'Jadwal Final Ekskul')

@section('content')

<div class="container-fluid py-4">

    <div class="mb-4">

        <h3 class="fw-bold mb-1">

            Jadwal Final Ekskul

        </h3>

        <p class="text-muted mb-0">

            Jadwal ekskul yang telah dipublish

        </p>

    </div>

    <div class="row">

        @foreach($hari as $h)

            <div class="col-md-4 mb-4">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-header fw-bold">

                        {{ $h->nama }}

                    </div>

                    <div class="card-body">

                        @forelse($grid[$h->id] ?? [] as $j)

                            <div class="border rounded p-3 mb-3">

                                <div class="fw-semibold">

                                    {{ $j->ekskul->nama }}

                                </div>

                                <div class="small text-muted">

                                    Pembina:
                                    {{ $j->guru->nama }}

                                </div>

                                <div class="small">

                                    {{ substr($j->jam_mulai,0,5) }}
                                    -
                                    {{ substr($j->jam_selesai,0,5) }}

                                </div>

                            </div>

                        @empty

                            <div class="text-muted">

                                Tidak ada jadwal

                            </div>

                        @endforelse

                    </div>

                </div>

            </div>

        @endforeach

    </div>

</div>

@endsection