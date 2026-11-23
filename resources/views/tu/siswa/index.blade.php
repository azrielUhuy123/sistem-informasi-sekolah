@extends('layouts.app')

@section('title', 'Manajemen Data Siswa')

@section('content')
<div class="container-fluid dt-siswa-page">
    <x-table.page-header
        icon="bi-people-fill"
        eyebrow="Direktori Siswa"
        title="Data Siswa"
        description="Kelola data siswa sekolah."
    />

    <x-alert.flash key="success" type="success"/>
    <x-alert.flash key="error" type="danger"/>

    <x-table.card
        title="Daftar Seluruh Siswa"
        subtitle="Seluruh data siswa pada SDN Mekar Jaya"
        :count="$siswa->total()"
        count-label="data"
    >
        <x-slot:toolbar>
            <x-table.toolbar
                table-id="tu-siswa-table"
                :action="route('tu.siswa.index')"
                :searchable="true"
                search-name="search"
                :search-value="request('search')"
                search-label="Cari nama atau NIS siswa"
                search-placeholder="Nama atau NIS"
                filter-name="status"
                :filter-value="request('status')"
                filter-label="Status"
                filter-menu-title="Filter status"
                :filter-options="[
                    ['value' => 'aktif', 'label' => 'Aktif'],
                    ['value' => 'nonaktif', 'label' => 'Nonaktif'],
                ]"
            />
        </x-slot:toolbar>

        <x-table.chips
            :criteria="[
                ['name' => 'search', 'label' => 'Search', 'value' => request('search')],
                [
                    'name' => 'status',
                    'label' => 'Status',
                    'value' => in_array(request('status'), ['aktif', 'nonaktif'], true) ? request('status') : null,
                    'display' => request('status') === 'aktif' ? 'Aktif' : 'Nonaktif',
                ],
            ]"
        />

        <form method="POST" action="{{ route('tu.siswa.bulk') }}" id="bulkForm">
            @csrf
            <div class="siswa-bulk-bar mb-3">
                <div class="siswa-bulk-actions">
                    <select
                        name="action"
                        class="form-control"
                        required
                    >
                        <option value="">Pilih Opsi</option>
                        <option value="nonaktifkan">Nonaktifkan</option>
                        <option value="aktifkan">Aktifkan</option>
                    </select>
                    <button
                        id="bulkButton"
                        type="submit"
                        class="btn btn-primary rounded-3"
                        disabled
                    >
                        Terapkan
                    </button>
                </div>
                <span class="badge bg-primary rounded-pill">
                    <span id="selectedCount">0</span> siswa dipilih
                </span>

            </div>

            <div class="table-responsive dt-siswa-table-wrap">
                <table id="tu-siswa-table" class="table table-hover align-middle dt-table">
                    <thead>
                        <tr>
                            <x-table.heading width="70px" class="text-center">
                                <input
                                    id="selectAll"
                                    class="form-check-input"
                                    type="checkbox">
                            </x-table.heading>
                            <x-table.heading width="70px">No</x-table.heading>
                            <x-table.heading>Nama</x-table.heading>
                            <x-table.heading width="150px">NISN</x-table.heading>
                            <x-table.heading>Rombel</x-table.heading>
                            <x-table.heading width="150px">Angkatan</x-table.heading>
                            <x-table.heading width="150px">Status</x-table.heading>
                            <x-table.heading width="90px">Aksi</x-table.heading>
                        </tr>
                    </thead>

                    <tbody id="tableSiswa">
                        @forelse ($siswa as $row)
                            <tr>
                                <td>
                                    <input type="checkbox" name="ids[]" value="{{ $row->id }}" class="form-check-input row-checkbox">
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark rounded-circle">
                                        {{ $loop->iteration + ($siswa->currentPage()-1) * $siswa->perPage() }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                            <i class="bi bi-person-fill fs-4"></i>
                                        </div>

                                        <div>
                                            <div class="fw-semibold">
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
                                    <div class="fw-bold">
                                        {{ $row->penempatanAktif?->kelas?->nama_kelas_label ?? '-' }}
                                    </div>
                                </td>
                                <td>
                                    {{ $row->penempatanAktif?->kelas?->kelasGrup?->angkatan?->tahun ?? '-' }}
                                </td>
                                <td>
                                    @if($row->trashed())
                                        <span class="badge-status nonaktif">
                                            <i class="bi bi-circle-fill me-2"></i> Nonaktif
                                        </span> 
                                    @else
                                        <span class="badge-status aktif">
                                            <i class="bi bi-circle-fill me-2"></i> Aktif
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <x-table.actions>
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
                                    </x-table.actions>                                                                      
                                </td>
                            </tr>
                        @empty
                            @if (request()->filled('status') || request()->filled('search'))
                                <x-table.empty
                                    :colspan="8"
                                    state="no-results"
                                    title="Tidak ada siswa yang cocok"
                                    description="Ubah status atau kata kunci pencarian, lalu coba lagi."
                                />
                            @else
                                <x-table.empty
                                    :colspan="8"
                                    title="Belum ada data siswa"
                                    description="Data siswa belum tersedia."
                                />
                            @endif
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                <x-table.pagination :paginator="$siswa" :show-summary="true" />
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