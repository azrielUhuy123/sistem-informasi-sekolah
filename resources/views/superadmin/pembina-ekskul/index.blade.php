@extends('layouts.app')

@section('title', 'Pembina Ekskul')

@section('content')

<div class="container-fluid dt-siswa-page">

    <x-table.page-header
        icon="bi-person-video3"
        eyebrow="Data Ekstrakurikuler / Pembina"
        title="Pembina Ekskul"
        description="Kelola data pembina ekstrakurikuler sekolah."
    />

    {{-- ALERT --}}
    <x-alert.flash key="success" type="success" />
    <x-alert.flash key="error" type="danger" />

    {{-- FORM --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="mb-3">

                <h5 class="fw-bold mb-1">
                    Penugasan Pembina Ekskul
                </h5>

                <small class="text-muted">
                    Pilih guru dan ekstrakurikuler yang akan dibina.
                </small>

            </div>

            <form
                action="{{ route('superadmin.pembina-ekskul.store') }}"
                method="POST"
            >

                @csrf

                <div class="row g-4 mb-5">

                    {{-- GURU --}}
                    <div>

                        <label class="form-label">
                            Guru
                        </label>

                        <select
                            name="guru_id"
                            class="form-control"
                            required
                        >
                            <option value="">
                                -- Pilih Guru --
                            </option>

                            @foreach($guru as $g)

                                <option value="{{ $g->id }}">
                                    {{ $g->nama }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- EKSTRAKURIKULER --}}
                    <div>

                        <label class="form-label">
                            Ekstrakurikuler
                        </label>

                        <select
                            name="ekskul_id"
                            class="form-control"
                            required
                        > 
                            <option value="">
                                -- Pilih Ekskul --
                            </option>

                            @foreach($ekskul as $e)

                                <option value="{{ $e->id }}">
                                    {{ $e->nama }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

                <div class="d-flex justify-content-end">

                    <button
                        type="submit"
                        class="btn btn-primary rounded-3"
                    >
                        Simpan
                        <i class="bi bi-save ms-1"></i>
                    </button>

                </div>

            </form>

        </div>

    </div>

    {{-- TABLE --}}
    <x-table.card
        title="Data Pembina Ekstrakurikuler"
        subtitle="Daftar guru yang menjadi pembina ekstrakurikuler"
        :count="$pembina->count()"
    >

        <div class="table-responsive dt-siswa-table-wrap">

            <table class="table table-hover align-middle dt-table">
                <caption class="visually-hidden">Daftar guru pembina ekstrakurikuler</caption>

                <thead>
                    <tr>
                        <x-table.heading width="70px">No</x-table.heading>
                        <x-table.heading>Nama Guru</x-table.heading>
                        <x-table.heading width="150px">Ekstrakurikuler</x-table.heading>
                        <x-table.heading width="100px">Aksi</x-table.heading>
                    </tr>
                </thead>

                <tbody>

                    @forelse($pembina as $p)

                        <tr>

                            {{-- NO --}}
                            <td>
                                <span class="badge bg-light text-dark rounded-circle">
                                    {{ $loop->iteration }}
                                </span>
                            </td>

                            {{-- GURU --}}
                            <td>

                                <div class="d-flex align-items-center">

                                    <div
                                        class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3"
                                        style="width: 48px; height: 48px;"
                                    >
                                        <i class="bi bi-person-fill fs-4"></i>
                                    </div>

                                    <div>

                                        <div class="fw-semibold">
                                            {{ $p->guru->pegawai?->nama ?? '-' }}
                                        </div>

                                        <small class="text-muted">
                                            NIP : {{ $p->guru->pegawai?->nip ?? '-' }}
                                        </small>

                                    </div>

                                </div>

                            </td>

                            {{-- EKSTRAKURIKULER --}}
                            <td>

                                <div class="d-flex align-items-center text-muted">
                                    <i class="bi bi-star-fill text-primary me-2"></i>                              
                                    <span>{{ $p->ekskul->nama ?? '-' }}</span>
                                </div>

                            </td>

                            {{-- AKSI --}}
                            <td>
                                <form
                                    action="{{ route('superadmin.pembina-ekskul.destroy', $p->id) }}"
                                    method="POST"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-danger btn-sm rounded-pill px-3 py-0 btn-confirm"
                                        data-title="Hapus Pembina Ekskul"
                                        data-message="Data pembina ekstrakurikuler akan dihapus dari sistem."
                                        data-button="btn-danger"
                                    >
                                        Hapus <i class="bi bi-trash ms-2"></i>
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <x-table.empty
                            :colspan="4"
                            title="Belum ada guru yang ditugaskan sebagai pembina ekstrakurikuler."
                            description=""
                        />

                    @endforelse

                </tbody>

            </table>

        </div>

    </x-table.card>

</div>

@endsection