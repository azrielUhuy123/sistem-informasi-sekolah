@extends('layouts.app')

@section('title', 'Ekskul')

@section('content')

<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="mb-4">

        <h3 class="fw-bold mb-1">

            Ekskul Saya

        </h3>

        <p class="text-muted mb-0">

            Kelola kegiatan ekstrakurikuler

        </p>

    </div>

    {{-- LIST EKSKUL --}}
    <div class="row g-4">

        @forelse($ekskuls as $item)

            <div class="col-md-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <h5 class="fw-bold mb-2">

                                    {{ $item->ekskul->nama }}

                                </h5>

                                <p class="text-muted mb-3">

                                    {{ $item->ekskul->deskripsi }}

                                </p>

                            </div>

                            <span class="badge bg-success h-100">

                                Pembina

                            </span>

                        </div>

                        <div class="small text-muted mb-1">

                            Hari:
                            {{ $item->ekskul->hari }}

                        </div>

                        <div class="small text-muted mb-3">

                            Jam:
                            {{ $item->ekskul->jam_mulai }}
                            -
                            {{ $item->ekskul->jam_selesai }}

                        </div>

                        <div class="d-flex gap-2">

                            <a
                                href="#"
                                class="btn btn-dark btn-sm"
                            >

                                Kelola Anggota

                            </a>

                            <a
                                href="#"
                                class="btn btn-outline-dark btn-sm"
                            >

                                Absensi

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        @empty

            <div class="col-12">

                <div class="card border-0 shadow-sm">

                    <div class="card-body text-center py-5">

                        <h6 class="text-muted mb-0">

                            Anda belum menjadi pembina ekskul

                        </h6>

                    </div>

                </div>

            </div>

        @endforelse

    </div>

</div>

@endsection