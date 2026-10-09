@extends('layouts.app')

@section('title', 'Perkembangan Siswa')

@section('content')

@php
    $totalSiswa = $penempatan->count();

    $lengkap = $penempatan
        ->where('status_perkembangan', 'Lengkap')
        ->count();

    $belumLengkap = $penempatan
        ->where('status_perkembangan', 'Belum Lengkap')
        ->count();

    $belumAda = $penempatan
        ->where('status_perkembangan', null)
        ->count();
@endphp

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
                        Perkembangan Siswa
                    </h3>

                    <p class="mb-0 opacity-75">
                        Kelola perkembangan karakter, sosial, disiplin, kebersihan,
                        dan catatan umum setiap siswa.
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


    {{-- FILTER --}}
    @if(isset($kelasPengampu) && $kelasPengampu->count())

        <div class="card border-0 shadow-sm rounded-4 mb-4">

            <div class="card-header bg-white border-0 pt-4 px-4">

                <h5 class="fw-bold mb-1">
                    Filter Kelas
                </h5>

                <small class="text-muted">
                    Pilih kelas untuk melihat perkembangan siswa.
                </small>

            </div>

            <div class="card-body">

                <form method="GET">

                    <select
                        name="kelas_id"
                        class="form-control"
                        onchange="this.form.submit()"
                    >

                        @foreach($kelasPengampu as $item)

                            <option
                                value="{{ $item->kelas_id }}"
                                {{ request('kelas_id', $kelas->id) == $item->kelas_id ? 'selected' : '' }}
                            >
                                {{ $item->kelas->nama_kelas_label }}
                            </option>

                        @endforeach

                    </select>

                </form>

            </div>

        </div>

    @endif

    {{-- TABLE --}}
    <x-table.card
        title="Daftar Perkembangan Siswa"
        subtitle="Klik tombol Isi / Edit untuk memperbarui perkembangan siswa."
    >

        <x-slot:badge>

            <span class="badge bg-primary rounded-pill px-2 py-1">

                {{ $totalSiswa }} 

                <span class="text-badge">
                    Siswa
                </span>

            </span>

        </x-slot:badge>

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

                    @forelse($penempatan as $i => $p)

                        <tr>

                            {{-- NO --}}
                            <td>

                                <span class="badge bg-light text-dark rounded-circle">
                                    {{ $i + 1 }}
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
                                            {{ $p->siswa->nama }}
                                        </div>

                                        <small class="text-muted">
                                            NIS : {{ $p->siswa->nis }}
                                        </small>

                                    </div>

                                </div>

                            </td>

                            {{-- STATUS --}}
                            <td>

                                @if($p->status_perkembangan == 'Lengkap')

                                    <span class="badge-status aktif">
                                        <i class="bi bi-check-circle-fill me-2"></i>
                                        Lengkap
                                    </span>

                                @elseif($p->status_perkembangan == 'Belum Lengkap')

                                    <span class="badge-status pending">
                                        <i class="bi bi-pencil-square me-2"></i>
                                        Belum Lengkap
                                    </span>

                                @else

                                    <span class="badge-status draft">
                                        <i class="bi bi-dash-circle-fill me-2"></i>
                                        Belum Ada
                                    </span>

                                @endif

                            </td>

                            {{-- AKSI --}}
                            <td>

                                <button
                                    type="button"
                                    class="btn btn-primary btn-sm rounded-pill px-3 py-0"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modal-{{ $p->siswa->id }}"
                                >                                    
                                    Isi / Edit <i class="bi bi-pencil-square ms-1"></i>

                                </button>

                            </td>

                        </tr>

                        {{-- MODAL --}}
                        <div
                            class="modal fade"
                            id="modal-{{ $p->siswa->id }}"
                            tabindex="-1"
                        >

                            <div class="modal-dialog modal-xl modal-dialog-scrollable">

                                <div class="modal-content border-0 rounded-4">

                                    <form
                                        action="{{ route('guru.perkembangan.store') }}"
                                        method="POST"
                                    >

                                        @csrf

                                        <div class="modal-header bg-light border-0">

                                            <div>

                                                <h5 class="text-label fw-bold">
                                                    {{ $p->siswa->nama }}
                                                </h5>

                                                <small class="text-label opacity-75">
                                                    Form Perkembangan Siswa
                                                </small>

                                            </div>

                                            <button
                                                type="button"
                                                class="btn-close text-label"
                                                data-bs-dismiss="modal"
                                            ></button>

                                        </div>

                                        <div class="modal-body">

                                            <div class="row g-4">

                                                <div class="col-md-6">

                                                    <label class="form-label fw-semibold">
                                                        Karakter
                                                    </label>

                                                    <textarea
                                                        name="perkembangan[{{ $p->siswa->id }}][karakter]"
                                                        class="form-control"
                                                        rows="4"
                                                    >{{ $p->perkembangan->karakter ?? '' }}</textarea>

                                                </div>

                                                <div class="col-md-6">

                                                    <label class="form-label fw-semibold">
                                                        Sosial
                                                    </label>

                                                    <textarea
                                                        name="perkembangan[{{ $p->siswa->id }}][sosial]"
                                                        class="form-control"
                                                        rows="4"
                                                    >{{ $p->perkembangan->sosial ?? '' }}</textarea>

                                                </div>

                                                <div class="col-md-6">

                                                    <label class="form-label fw-semibold">
                                                        Disiplin
                                                    </label>

                                                    <textarea
                                                        name="perkembangan[{{ $p->siswa->id }}][disiplin]"
                                                        class="form-control"
                                                        rows="4"
                                                    >{{ $p->perkembangan->disiplin ?? '' }}</textarea>

                                                </div>

                                                <div class="col-md-6">

                                                    <label class="form-label fw-semibold">
                                                        Kebersihan
                                                    </label>

                                                    <textarea
                                                        name="perkembangan[{{ $p->siswa->id }}][kebersihan]"
                                                        class="form-control"
                                                        rows="4"
                                                    >{{ $p->perkembangan->kebersihan ?? '' }}</textarea>

                                                </div>

                                                <div class="col-12">

                                                    <label class="form-label fw-semibold">
                                                        Catatan Umum
                                                    </label>

                                                    <textarea
                                                        name="perkembangan[{{ $p->siswa->id }}][catatan]"
                                                        class="form-control"
                                                        rows="5"
                                                    >{{ $p->perkembangan->catatan ?? '' }}</textarea>

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
                                        Belum Ada Data Siswa
                                    </h5>

                                    <p class="text-muted mb-0">
                                        Tidak ada data siswa yang dapat ditampilkan.
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