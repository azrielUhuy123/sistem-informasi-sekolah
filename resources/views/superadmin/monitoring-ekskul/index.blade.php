@extends('layouts.app')

@section('title', 'Monitoring Ekskul')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">

                <div>
                    <h3 class="fw-bold mb-2">
                        Monitoring Ekskul
                    </h3>

                    <p class="mb-0 opacity-75">
                        Pantau seluruh kegiatan ekstrakurikuler, pembina, dan proses monitoring secara terpusat.
                    </p>
                </div>

                <div class="badge bg-white text-primary px-3 py-2 fs-6">
                    <i class="bi bi-shield-check me-2"></i> Sinergi-MJ
                </div>

            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <x-table.card
        title="Daftar Ekstrakurikuler"
        subtitle="Pilih ekstrakurikuler untuk melihat hasil monitoring"
    >

        <x-slot:badge>
            <span class="badge bg-primary rounded-pill px-2 py-1">
                {{ $ekskul->count() }}
                <span class="text-badge">Ekskul</span>
            </span>
        </x-slot:badge>

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead>
                    <tr>
                        <th width="70">No</th>
                        <th>Ekstrakurikuler</th>
                        <th>Pembina</th>
                        <th width="140">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($ekskul as $e)

                        <tr>

                            {{-- NO --}}
                            <td>
                                <span class="badge bg-light text-dark rounded-circle">
                                    {{ $loop->iteration }}
                                </span>
                            </td>

                            {{-- EKSTRAKURIKULER --}}
                            <td>
                                <span class="fw-semibold">
                                    {{ $e->nama }}
                                </span>                                                                        
                            </td>
                            {{-- PEMBINA --}}
                            <td>
                                @if($e->pembina)
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3"
                                            style="width: 48px; height: 48px;"
                                        >
                                            <i class="bi bi-person-fill fs-4"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">
                                                {{ $e->pembina->guru->pegawai?->nama ?? '-' }}
                                            </div>
                                            <small class="text-muted">
                                                Pembina Ekskul
                                            </small>
                                        </div>
                                    </div>
                                @else
                                    <span class="badge-status pending">
                                        Belum Ada Pembina
                                    </span>
                                @endif
                            </td>
                            {{-- AKSI --}}
                            <td>
                                <a
                                    href="{{ route('superadmin.monitoring-ekskul.show', $e->id) }}"
                                    class="btn btn-primary btn-sm rounded-pill px-3 py-0"
                                >
                                    Monitoring
                                    <i class="bi bi-chevron-right ms-1"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <i class="bi bi-inbox display-5 text-secondary"></i>
                                <p class="mt-3 mb-0 text-muted">
                                    Data ekstrakurikuler belum tersedia.
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