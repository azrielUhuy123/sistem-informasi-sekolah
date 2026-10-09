@extends('layouts.app')

@section('title', 'Daftar Anggota Ekskul')

@section('content')

@php
    $memberItems = $anggota instanceof \Illuminate\Pagination\LengthAwarePaginator ? $anggota->getCollection() : $anggota;
    $totalAnggota = $anggota instanceof \Illuminate\Pagination\LengthAwarePaginator ? $anggota->total() : $memberItems->count();
    $aktifAnggota = $totalAnggota;
    $anggotaBaru = $memberItems->filter(function ($item) {
        return $item->created_at && $item->created_at->isCurrentMonth();
    })->count();
    $ekskulCount = $memberItems->pluck('ekskul_id')->filter()->unique()->count();
@endphp

<div class="container-fluid">

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div
            class="card-body p-4 p-lg-5 text-white"
            style="background:linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);"
        >
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2">Daftar Anggota Ekstrakurikuler</h3>
                    <p class="mb-0 opacity-75">
                        Kelola seluruh anggota ekstrakurikuler, pantau status keanggotaan siswa, dan lakukan administrasi anggota dalam satu halaman.
                    </p>
                </div>

                <div class="badge bg-white text-primary px-3 py-2 fs-6">
                    <i class="bi bi-shield-check me-2"></i>
                    Sinergi-MJ
                </div>
            </div>
        </div>
    </div>

    <x-alert.flash key="success" type="success" />
    <x-alert.flash key="error" type="danger" />

    <x-table.card
        title="Daftar Anggota"
        subtitle="Kelola seluruh anggota ekstrakurikuler dalam satu tampilan."
    >
        <x-slot:badge>
            <span class="badge bg-primary rounded-pill px-2 py-1">
                {{ $totalAnggota }}
                <span class="text-badge">Anggota</span>
            </span>
        </x-slot:badge>

        <x-slot:actions>
            <input
                type="text"
                id="searchInput"
                name="search"
                class="form-control table-search"
                placeholder="Cari nama atau NIS..."                        
            >
        </x-slot:actions>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th width="70">No</th>
                        <th>Nama</th>
                        <th>Tanggal Bergabung</th>
                        <th>Status</th>
                        <th width="140">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableAnggotaEkskul">
                    @forelse($anggota as $a)
                        @php
                            $siswaData = $a->siswa;
                            $namaSiswa = optional($siswaData)->nama ?? 'Data Siswa Hilang';
                            $nis = optional($siswaData)->nis ?? '-';
                            $inisial = strtoupper(substr(trim($namaSiswa), 0, 1));
                        @endphp
                        <tr>
                            <td>
                                <span class="badge bg-light text-dark rounded-circle">
                                    {{ $anggota->firstItem() + $loop->index }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div
                                        class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3"
                                        style="width:48px;height:48px;"
                                    >
                                        <i class="bi bi-person-fill fs-4"></i>
                                    </div>

                                    <div>
                                        <div class="fw-semibold">{{ $namaSiswa }}</div>
                                        <small class="text-muted">{{ $nis }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-semibold">{{ $a->created_at->translatedFormat('d F Y') }}</span>
                                    <small class="text-muted">{{ $a->created_at->diffForHumans() }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="badge-status aktif">
                                    <i class="bi bi-check-circle-fill me-2"></i>Aktif
                                </span>
                            </td>
                            <td>
                                <form
                                    action="{{ route('guru.ekskul.daftar-anggota.destroy', $a->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Keluarkan anggota ini?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-danger btn-sm rounded-pill px-3 py-0">
                                        Keluarkan<i class="bi bi-person-dash-fill ms-1"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="bi bi-people display-4 text-secondary"></i>
                                    <h5 class="fw-bold mt-3 mb-2">Belum Ada Anggota</h5>
                                    <p class="text-muted mb-0">Belum ada siswa yang menjadi anggota ekstrakurikuler ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3 d-flex justify-content-center">
            {{ $anggota->links() }}
        </div>
    </x-table.card>

</div>
{{-- SEARCH --}}
<script>

    const searchInput =
        document.getElementById('searchInput');

    searchInput.addEventListener('keyup', function () {

        const keyword =
            this.value.toLowerCase();

        const rows =
            document.querySelectorAll(
                '#tableAnggotaEkskul tr'
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