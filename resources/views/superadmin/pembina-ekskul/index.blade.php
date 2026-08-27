@extends('layouts.app')

@section('title', 'Pembina Ekskul')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2">Pembina Ekskul</h3>
                    <p class="mb-0 opacity-75">
                        Kelola data pembina ekskul sekolah.
                    </p>
                </div>

                <div class="badge bg-white text-primary px-3 py-2 fs-6">
                    <i class="bi bi-shield-check me-2"></i> Sinergi-MJ
                </div>
            </div>
        </div>
    </div>

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
    >

        <x-slot:badge>
            <span class="badge bg-primary rounded-pill px-2 py-1">
                {{ $pembina->count() }}
                <span class="text-badge">Pembina</span>
            </span>
        </x-slot:badge>

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead>
                    <tr>
                        <th width="70">No</th>
                        <th>Nama Guru</th>
                        <th width="150">Ekstrakurikuler</th>                        
                        <th width="100">Aksi</th>
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

                                <span class="text-muted">                                
                                    {{ $p->ekskul->nama ?? '-' }}
                                </span>

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

                        <tr>

                            <td colspan="4" class="text-center py-5">

                                <i class="bi bi-person-x display-5 text-secondary"></i>

                                <p class="mt-3 mb-0 text-muted">
                                    Belum ada guru yang ditugaskan sebagai pembina ekstrakurikuler.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </x-table.card>

</div>

@endsection