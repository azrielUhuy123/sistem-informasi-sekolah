@extends('layouts.app')

@section('title', 'Catatan Pembinaan')

@section('content')

<div class="container-fluid py-3">

    <div class="mb-4">

        <h3 class="fw-bold mb-1">

            Catatan Pembinaan

        </h3>

        <p class="text-muted mb-0">

            {{ $ekskul->nama }}

        </p>

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

                                Nama Siswa

                            </th>

                            <th width="180">

                                Status

                            </th>

                            <th width="150">

                                Aksi

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($anggota as $item)

                            @php

                                $catatan =
                                    \App\Models\CatatanPembinaanEkskul::where(
                                        'ekskul_id',
                                        $ekskul->id
                                    )
                                    ->where(
                                        'siswa_id',
                                        $item->siswa_id
                                    )
                                    ->where(
                                        'tahun_ajaran_id',
                                        current_tahun_id()
                                    )
                                    ->first();

                                $lengkap =
                                    $catatan
                                    &&
                                    filled($catatan->kehadiran)
                                    &&
                                    filled($catatan->partisipasi)
                                    &&
                                    filled($catatan->perkembangan)
                                    &&
                                    filled($catatan->catatan);

                            @endphp

                            <tr>

                                <td>

                                    {{ $anggota->firstItem() + $loop->index }}

                                </td>

                                <td>

                                    {{ $item->siswa->nama }}

                                </td>

                                <td>

                                    @if(!$catatan)

                                        <span class="badge bg-secondary">

                                            Belum Ada

                                        </span>

                                    @elseif($lengkap)

                                        <span class="badge bg-success">

                                            Lengkap

                                        </span>

                                    @else

                                        <span class="badge bg-warning text-dark">

                                            Belum Lengkap

                                        </span>

                                    @endif

                                </td>

                                <td>

                                    <button
                                        class="btn btn-primary btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modal{{ $item->id }}"
                                    >

                                        Isi / Edit

                                    </button>

                                </td>

                            </tr>

                            {{-- MODAL --}}

                            <div
                                class="modal fade"
                                id="modal{{ $item->id }}"
                                tabindex="-1"
                            >

                                <div class="modal-dialog modal-lg">

                                    <div class="modal-content">

                                        <form
                                            action="{{ route('guru.ekskul.pembinaan.store') }}"
                                            method="POST"
                                        >

                                            @csrf

                                            <input
                                                type="hidden"
                                                name="ekskul_id"
                                                value="{{ $ekskul->id }}"
                                            >

                                            <input
                                                type="hidden"
                                                name="siswa_id"
                                                value="{{ $item->siswa_id }}"
                                            >

                                            <div class="modal-header">

                                                <h5 class="modal-title">

                                                    {{ $item->siswa->nama }}

                                                </h5>

                                                <button
                                                    type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="modal"
                                                ></button>

                                            </div>

                                            <div class="modal-body">

                                                <div class="mb-3">

                                                    <label class="form-label">

                                                        Kehadiran

                                                    </label>

                                                    <textarea
                                                        name="kehadiran"
                                                        rows="4"
                                                        class="form-control"
                                                    >{{ $catatan->kehadiran ?? '' }}</textarea>

                                                </div>

                                                <div class="mb-3">

                                                    <label class="form-label">

                                                        Partisipasi

                                                    </label>

                                                    <textarea
                                                        name="partisipasi"
                                                        rows="4"
                                                        class="form-control"
                                                    >{{ $catatan->partisipasi ?? '' }}</textarea>

                                                </div>

                                                <div class="mb-3">

                                                    <label class="form-label">

                                                        Perkembangan Kemampuan

                                                    </label>

                                                    <textarea
                                                        name="perkembangan"
                                                        rows="4"
                                                        class="form-control"
                                                    >{{ $catatan->perkembangan ?? '' }}</textarea>

                                                </div>

                                                <div class="mb-3">

                                                    <label class="form-label">

                                                        Catatan Pembina

                                                    </label>

                                                    <textarea
                                                        name="catatan"
                                                        rows="5"
                                                        class="form-control"
                                                    >{{ $catatan->catatan ?? '' }}</textarea>

                                                </div>

                                            </div>

                                            <div class="modal-footer">

                                                <button
                                                    type="button"
                                                    class="btn btn-secondary"
                                                    data-bs-dismiss="modal"
                                                >

                                                    Tutup

                                                </button>

                                                <button
                                                    type="submit"
                                                    class="btn btn-success"
                                                >

                                                    Simpan

                                                </button>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        @empty

                            <tr>

                                <td
                                    colspan="4"
                                    class="text-center text-muted py-4"
                                >

                                    Belum ada anggota ekskul

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-3">

                {{ $anggota->links() }}

            </div>

        </div>

    </div>

</div>

@endsection