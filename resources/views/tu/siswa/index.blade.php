@extends('layouts.app')

@section('title', 'Manajemen Data Siswa')

@section('content')
<div class="container-fluid">
    {{-- HEADER --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2">Data Siswa</h3>
                    <p class="mb-0 opacity-75">Kelola data siswa sekolah</p>
                </div>
                <div class="badge bg-white text-primary px-3 py-2 fs-6">
                    <i class="bi bi-shield-check me-2"></i> Sistem Terhubung
                </div>
            </div>
        </div>
    </div>

    <x-alert.flash key="success" type="success"/>
    <x-alert.flash key="error" type="danger"/>

    <x-table.card
        title="Daftar Seluruh Siswa"
        subtitle="Seluruh data siswa pada SDN Mekar Jaya">
        <x-slot:badge>
            <span class="badge bg-primary rounded-pill px-3 py-2">
                {{ $siswa->total() }} siswa
            </span>
        </x-slot:badge>

        <x-slot:actions>
            <div class="d-flex flex-column align-items-end gap-2">
                {{-- FILTER --}}
                <form
                    action="{{ route('tu.siswa.index') }}"
                    method="GET"
                    class="d-flex gap-2"
                >
                    <select
                        name="status"
                        class="form-select rounded-3"
                        style="width:170px"
                    >
                        <option value="">Semua Status</option>
                        <option
                            value="aktif"
                            @selected(request('status')=='aktif')
                        >
                            Aktif
                        </option>
                        <option
                            value="nonaktif"
                            @selected(request('status')=='nonaktif')
                        >
                            Nonaktif
                        </option>
                    </select>
                    {{-- SEARCH --}}
                    <input
                        type="text"
                        id="search"
                        name="search"
                        class="form-control rounded-3"
                        placeholder="Cari Nama / NIS..."
                        value="{{ request('search') }}"
                        style="width:300px;"
                    >
                    <button
                        type="submit"
                        class="btn btn-primary rounded-3"
                    >
                        Filter
                    </button>
                    @if(request()->filled('status') || request()->filled('search'))
                        <a
                            href="{{ route('tu.siswa.index') }}"
                            class="btn btn-outline-secondary rounded-3"
                        >
                            Reset
                        </a>
                    @endif
                </form>
                
            </div>
        </x-slot:actions>

        <form method="POST" action="{{ route('tu.siswa.bulk') }}" id="bulkForm">
            @csrf

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex gap-2">
                    <select name="action" class="form-select rounded-3 w-auto" required>
                        <option value="">Pilih Opsi</option>
                        <option value="nonaktifkan">Nonaktifkan</option>
                        <option value="aktifkan">Aktifkan</option>
                    </select>

                    <button id="bulkButton" type="submit" class="btn btn-primary rounded-3" disabled>
                        Terapkan
                    </button>
                </div>

                <span class="badge bg-primary rounded-pill mb-0">
                    <span id="selectedCount">0</span> siswa dipilih
                </span>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="70">
                                <input
                                    id="selectAll"
                                    class="form-check-input"
                                    type="checkbox">
                            </th>
                            <th width="70">No</th>
                            <th>Nama</th>
                            <th width="150">NISN</th>
                            <th width="150">Rombel</th>
                            <th width="150">Angkatan</th>
                            <th width="150">Status</th>
                            <th width="90">Aksi</th>
                        </tr>
                    </thead>

                    <tbody id="tableSiswa">
                        @forelse ($siswa as $row)
                            <tr>
                                <td>
                                    <input type="checkbox" name="ids[]" value="{{ $row->id }}" class="form-check-input row-checkbox">
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark rounded-pill px-3 py-2">
                                        {{ $loop->iteration + ($siswa->currentPage()-1) * $siswa->perPage() }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                            <i class="bi bi-person-fill fs-4"></i>
                                        </div>

                                        <div>
                                            <div class="fw-bold text-lowercase">
                                                {{ $row->nama }}
                                            </div>

                                            <small class="text-muted">
                                                NIS : {{ $row->nis }}
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $row->nisn ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-info text-dark fw-semibold rounded-pill px-3 py-2">
                                        {{ $row->penempatanAktif?->kelas?->nama_kelas_label ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    {{ $row->penempatanAktif?->kelas?->kelasGrup?->angkatan?->tahun ?? '-' }}
                                </td>
                                <td>
                                    @if($row->trashed())
                                        <span class="badge bg-secondary rounded-pill px-3 py-2">
                                            <i class="bi bi-x-circle me-1"></i> Nonaktif
                                        </span>
                                    @else
                                        <span class="badge bg-success rounded-pill px-3 py-2">
                                            <i class="bi bi-check-circle me-1"></i> Aktif
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
                                                <a class="dropdown-item" href="{{ route('tu.siswa.show', $row->id) }}">
                                                    <i class="bi bi-eye me-2"></i> Detail
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('tu.siswa.edit', $row->id) }}">
                                                    <i class="bi bi-pencil-square me-2"></i> Edit
                                                </a>
                                            </li>
                                            @if($row->penempatanAktif)
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('tu.penempatan-siswa.edit', $row->penempatanAktif->id) }}">
                                                        <i class="bi bi-door-open me-2"></i> Pindah Kelas
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    <div class="mb-3">
                                        <i class="bi bi-inbox fs-1"></i>
                                    </div>
                                    <div class="fw-semibold">Tidak ada data siswa</div>
                                    <div class="small">Coba ubah filter atau hapus kata kunci pencarian.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $siswa->links() }}
            </div>
        </form>
    </x-table.card>
</div>
@endsection

@push('scripts')
<script>
    const selectAll = document.getElementById('selectAll');
    const boxes = document.querySelectorAll('.row-checkbox');
    const counter = document.getElementById('selectedCount');
    const bulkForm = document.getElementById('bulkForm');
    const bulkButton = document.getElementById('bulkButton');

    function updateCounter() {

        const checked =
            document.querySelectorAll('.row-checkbox:checked').length;

        counter.innerText = checked;

        if (bulkButton) {
            bulkButton.disabled = checked === 0;
        }

    }

    // Select All
    if (selectAll) {
        selectAll.addEventListener('change', function () {

            boxes.forEach(box => {
                box.checked = this.checked;
            });

            updateCounter();

        });
    }

    // Checkbox per baris
    boxes.forEach(box => {
        box.addEventListener('change', updateCounter);
    });

    // Validasi Bulk Action
    if (bulkForm) {
        bulkForm.addEventListener('submit', function (e) {

            if (document.querySelectorAll('.row-checkbox:checked').length === 0) {

                e.preventDefault();
                alert('Pilih minimal satu siswa.');

            }

        });
    }

    // Sinkronkan counter saat halaman dibuka
    updateCounter();
</script>

@endpush    