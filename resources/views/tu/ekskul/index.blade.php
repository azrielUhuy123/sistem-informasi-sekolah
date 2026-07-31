@extends('layouts.app')

@section('title', 'Ekskul')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2">Manajemen Ekskul</h3>
                    <p class="mb-0 opacity-75">Kelola kegiatan ekstrakurikuler sekolah</p>
                </div>
                <div class="badge bg-white text-primary px-3 py-2 fs-6">
                    <i class="bi bi-shield-check me-2"></i> Sistem Terhubung
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-end align-items-center mb-4">

        <a
            href="{{ route('tu.ekskul.create') }}"
            class="btn btn-primary"
        >

            <i class="bi bi-plus-lg me-1"></i>

            Tambah Ekskul

        </a>

    </div>

    {{-- ALERT --}}
    <x-alert.flash key="success" type="success"/>

    <x-alert.flash key="error" type="danger"/>

    {{-- TABLE --}}
    <x-table.card
        title="Daftar Ekstrakurikuler"
        subtitle="Seluruh kegiatan ekstrakurikuler."
    >
        <x-slot:badge>
            <span class="badge bg-primary rounded-pill px-3 py-2">
                {{ $ekskuls->count() }} Ekskul
            </span>
        </x-slot:badge>

        <x-slot:actions>
            <div class="d-flex flex-column align-items-end gap-2">
                <input
                    type="text"
                    id="searchInput"
                    class="form-control rounded-3"
                    placeholder="Cari ekskul..."
                    style="width: 320px;"
                >
            </div>

        </x-slot:actions>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th width="70">No</th>
                        <th>Nama Ekskul</th>
                        <th width="400">Pembina</th>
                        <th width="150">Jadwal</th>
                        <th width="150">Kuota</th>
                        <th width="150">Status</th>
                        <th width="90">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody id="tableEkskul">
                    @forelse($ekskuls as $i => $e)
                        <tr>
                            <td class="text-center">
                                <span class="badge bg-light text-dark rounded-pill px-3 py-2">
                                    {{ $loop->iteration }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="ekskul-icon me-3"><i class="bi bi-trophy-fill"></i></div>

                                    <div>
                                        <div class="fw-bold">
                                            {{ $e->nama }}
                                        </div>

                                        <small class="text-muted">
                                            {{ Str::limit($e->deskripsi, 50) }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold">
                                    {{ $e->guru->pegawai->nama ?? '-' }}
                                </div>
                                <small class="text-muted">Pembina</small>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <div class="fw-semibold">
                                            {{ $e->hari ?? '-' }}
                                        </div>

                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($e->jam_mulai)->format('H:i') }}
                                            -
                                            {{ \Carbon\Carbon::parse($e->jam_selesai)->format('H:i') }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info text-dark fw-semibold rounded-pill px-3 py-2">
                                    {{ $e->kuota }}
                                </span>
                            </td>
                            <td>
                                @if($e->status == 'aktif')
                                    <span class="badge bg-success rounded-pill px-3 py-2">
                                        <i class="bi bi-check-circle me-1"></i> Aktif
                                    </span>
                                @else
                                    <span class="badge bg-danger rounded-pill px-3 py-2">
                                        <i class="bi bi-x-circle me-1"></i> Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-primary"
                                            type="button"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                        <i class="bi bi-gear-fill"></i>
                                        <i class="bi bi-caret-down-fill"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('tu.ekskul.edit', $e->id) }}">
                                                <i class="bi bi-pencil-square me-2"></i> Edit
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('tu.ekskul.destroy', $e->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Hapus data ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="bi bi-trash me-2"></i> Hapus
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="text-center py-5">
                                    <i class="bi bi-trophy display-3 text-secondary"></i>
                                    <h5 class="fw-bold mt-3">Belum Ada Ekskul</h5>
                                    <p class="text-muted mb-0">Saat ini belum ada ekskul yang terdata pada sistem ini.</p>
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
    .ekskul-icon{
        width:50px;height:50px;border-radius:16px;display:flex;align-items:center;justify-content:center;
        background:linear-gradient(135deg,#2563eb,#0f4c81);color:#fff;font-size:1.2rem;flex-shrink:0;
    }

</style>
{{-- SEARCH --}}
<script>

    const searchInput =
        document.getElementById('searchInput');

    searchInput.addEventListener('keyup', function () {

        const keyword =
            this.value.toLowerCase();

        const rows =
            document.querySelectorAll(
                '#tableEkskul tr'
            );

        rows.forEach(row => {

            const text =
                row.innerText.toLowerCase();

            row.style.display =
                text.includes(keyword)
                ? ''
                : 'none';

        });
    });

</script>
@endsection