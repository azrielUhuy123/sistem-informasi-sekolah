@extends('layouts.app')

@section('title', 'Ekskul Saya')

@section('content')

<div class="container-fluid py-4">

    <div class="mb-4">

        <h3 class="fw-bold mb-1">

            Ekskul Saya

        </h3>

        <p class="text-muted mb-0">

            Daftar ekstrakurikuler yang sedang saya ikuti

        </p>

    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light">

                        <tr>

                            <th width="60">

                                No

                            </th>

                            <th>

                                Nama Ekskul

                            </th>

                            <th>

                                Pembina

                            </th>

                            <th>

                                Status

                            </th>

                            <th width="180">

                                Aksi

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($ekskul as $item)

                            <tr>

                                <td>

                                    {{ $loop->iteration }}

                                </td>

                                <td>

                                    <div class="fw-semibold">

                                        {{ $item->ekskul->nama }}

                                    </div>

                                </td>

                                <td>

                                    {{ $item->ekskul->pembina->guru->nama ?? '-' }}

                                </td>

                                <td>

                                    @if($item->status == 'aktif')

                                        <span class="badge bg-success">

                                            Aktif

                                        </span>

                                    @else

                                        <span class="badge bg-secondary">

                                            Tidak Aktif

                                        </span>

                                    @endif

                                </td>

                                <td>

                                    <div class="d-flex gap-2">

                                        <a
                                            href="{{ route('siswa.ekskul.show', $item->ekskul_id) }}"
                                            class="btn btn-info btn-sm"
                                        >

                                            Detail

                                        </a>

                                        <a
                                            href="{{ route('siswa.ekskul.absensi', $item->ekskul_id) }}"
                                            class="btn btn-primary btn-sm"
                                        >

                                            Absensi

                                        </a>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="5"
                                    class="text-center text-muted py-4"
                                >

                                    Anda belum terdaftar pada ekskul manapun

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