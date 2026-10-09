@extends('layouts.app')

@section('title', 'Catatan Pembinaan')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

        <div
            class="card-body p-4 p-lg-5 text-white"
            style="background:linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);"
        >

            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">

                <div>

                    <h3 class="fw-bold mb-2">
                        Manajemen Catatan Pembinaan
                    </h3>

                    <p class="mb-0 opacity-75">
                        Kelola catatan pembinaan ekstrakurikuler
                    </p>

                </div>

                <div class="badge bg-white text-primary px-3 py-2 fs-6">
                    <i class="bi bi-shield-check me-2"></i>
                    Sinergi-MJ
                </div>

            </div>

        </div>

    </div>

    {{-- ALERT --}}
    <x-alert.flash key="success" type="success"/>
    <x-alert.flash key="error" type="danger"/>

    {{-- TABLE --}}
    <x-table.card
        title="Daftar Anggota"
        subtitle="Klik tombol Isi / Edit untuk memperbarui pengembangan anggota."
    >        

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead>

                    <tr>

                        <th width="70">
                            No
                        </th>

                        <th>
                            Nama Siswa
                        </th>

                        <th width="200">
                            Status
                        </th>

                        <th width="170">
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

                            @php
                                $siswa = $item->siswa;
                                $namaSiswa = optional($siswa)->nama ?? 'Data Siswa Hilang';
                            @endphp

                        <tr>

                            {{-- NO --}}
                            <td>

                                <span class="badge bg-light text-dark rounded-circle">
                                    {{ $anggota->firstItem() + $loop->index }}
                                </span>

                            </td>

                            {{-- SISWA --}}
                            <td>

                                <div class="d-flex align-items-center">

                                    <div
                                        class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3"
                                        style="width:48px;height:48px;"
                                    >

                                        <i class="bi bi-person-fill fs-4"></i>

                                    </div>

                                    <div>

                                        <div class="fw-semibold">
                                            {{ $namaSiswa }}
                                        </div>

                                    </div>

                                </div>

                            </td>

                            {{-- STATUS --}}
                            <td>

                                @if(!$catatan)
                                    <span class="badge-status draft">
                                        <i class="bi bi-dash-circle-fill me-2"></i>
                                        Belum Ada
                                    </span>                                
                                @elseif($lengkap)
                                    <span class="badge-status aktif">
                                        <i class="bi bi-check-circle-fill me-2"></i>
                                        Lengkap
                                    </span>
                                @else                                
                                    <span class="badge-status pending">
                                        <i class="bi bi-pencil-square me-2"></i>
                                        Belum Lengkap
                                    </span>                                
                                @endif
                            </td>

                            {{-- AKSI --}}
                            <td>

                                <button
                                    type="button"
                                    class="btn btn-primary btn-sm rounded-pill px-3 py-0"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modal{{ $item->id }}"
                                >                                    
                                    Isi / Edit <i class="bi bi-pencil-square ms-1"></i>

                                </button>

                            </td>

                        </tr>

                        {{-- MODAL --}}
                        <div
                            class="modal fade"
                            id="modal{{ $item->id }}"
                            tabindex="-1"
                        >

                            <div class="modal-dialog modal-xl modal-dialog-scrollable">

                                <div class="modal-content border-0 rounded-4">

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
                                        <div class="modal-header bg-light border-0">  
                                            <h5 class="text-label fw-bold">
                                                {{ optional($item->siswa)->nama ?? 'Data Siswa Hilang' }}
                                            </h5>                       
                                                                    
                                            <button
                                                type="button"
                                                class="btn-close text-label"
                                                data-bs-dismiss="modal"
                                            ></button>

                                        </div>

                                        <div class="modal-body">

                                            <div class="row g-4">

                                                <div class="col-md-6">

                                                    <label class="form-label">
                                                        Kehadiran
                                                    </label>

                                                    <textarea
                                                        name="kehadiran"
                                                        rows="4"
                                                        class="form-control"
                                                    >{{ $catatan->kehadiran ?? '' }}</textarea>

                                                </div>

                                                <div class="col-md-6">

                                                    <label class="form-label">
                                                        Partisipasi
                                                    </label>

                                                    <textarea
                                                        name="partisipasi"
                                                        rows="4"
                                                        class="form-control"
                                                    >{{ $catatan->partisipasi ?? '' }}</textarea>

                                                </div>

                                                <div class="col-md-6">

                                                    <label class="form-label">
                                                        Perkembangan Kemampuan
                                                    </label>

                                                    <textarea
                                                        name="perkembangan"
                                                        rows="4"
                                                        class="form-control"
                                                    >{{ $catatan->perkembangan ?? '' }}</textarea>

                                                </div>

                                                <div class="col-md-6">

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

                                        </div>

                                        <div class="modal-footer border-0">

                                            <button
                                                type="button"
                                                class="btn btn-light rounded-3"
                                                data-bs-dismiss="modal"
                                            >
                                                Tutup
                                            </button>

                                            <button
                                                type="submit"
                                                class="btn btn-success rounded-3"
                                            >                                                
                                                Simpan <i class="bi bi-save ms-1"></i>

                                            </button>                                        

                                        </div>

                                    </form>

                                </div>

                            </div>

                        </div>

                    @empty

                        <tr>

                            <td colspan="4" class="text-center py-5">

                                <div class="d-flex flex-column align-items-center">

                                    <i class="bi bi-people display-4 text-secondary"></i>

                                    <h5 class="fw-bold mt-3">
                                        Belum ada anggota ekskul
                                    </h5>

                                    <p class="text-muted mb-0">
                                        Tidak ada data anggota yang dapat ditampilkan.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </x-table.card>

</div>

<style>

.modal-content {
    overflow: hidden;
}

</style>

@endsection