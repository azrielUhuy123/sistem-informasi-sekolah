@extends('layouts.app')

@section('title', 'Tahun Ajaran')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2">Manajemen Tahun Ajaran</h3>
                    <p class="mb-0 opacity-75">Kelola siklus akademik sistem</p>
                </div>
                <div class="badge bg-white text-primary px-3 py-2 fs-6">
                    <i class="bi bi-shield-check me-2"></i> Sistem Terhubung
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-end align-items-center mb-4">

        <a href="{{ route('tu.tahun.create') }}" 
           class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>
            Tambah Tahun Ajaran
        </a>
    </div>

    {{-- ALERT SUCCESS --}}
    @if(session('success'))

        <div class="alert alert-success border-0 shadow-sm">

            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}

        </div>

    @endif

    {{-- ALERT ERROR --}}
    @if(session('error'))

        <div class="alert alert-danger border-0 shadow-sm">

            <i class="bi bi-exclamation-circle-fill me-2"></i>
            {{ session('error') }}

        </div>

    @endif


    <!-- 🔷 STATUS SISTEM -->
    <div class="card shadow-sm border-0 rounded-0 mb-4">
        <div class="card-header bg-light border-0 p-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h5 class="fw-semibold mb-1">Status Sistem</h5>
                    <span class="text-muted small">Informasi tahun ajaran aktif saat ini</span>
                </div>
            </div>
        </div>

        <div class="card-body p-4">
            @if($tahunAktif)
                @php
                    $statusBadgeClass = match($tahunAktif->status) {
                        'aktif' => 'bg-success',
                        'draft' => 'bg-dark',
                        'selesai' => 'bg-secondary',
                        default => 'bg-light text-dark',
                    };

                    $statusLabel = match($tahunAktif->status) {
                        'aktif' => 'Aktif',
                        'draft' => 'Draft',
                        'selesai' => 'Selesai',
                        default => 'Tidak Aktif',
                    };

                    $statusSubtitle = match(true) {
                        $tahunAktif->status === 'aktif' && $tahunAktif->semester === 'Ganjil' => 'Semester ganjil sedang berjalan',
                        $tahunAktif->status === 'aktif' && $tahunAktif->semester === 'Genap' => 'Semester genap sedang berjalan',
                        $tahunAktif->status === 'draft' => 'Belum aktif untuk operasional',
                        $tahunAktif->status === 'selesai' => 'Tahun ajaran telah selesai',
                        default => 'Status tidak diketahui',
                    };
                @endphp

                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <h3 class="fw-semibold mb-0">{{ $tahunAktif->tahun }}</h3>
                        <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle">
                            {{ $tahunAktif->semester }}
                        </span>
                        <span class="badge {{ $statusBadgeClass }}">{{ $statusLabel }}</span>
                    </div>
                    <div class="small text-muted">{{ $statusSubtitle }}</div>
                </div>

                @if($tahunAktif->status === 'aktif' && $tahunAktif->semester === 'Ganjil')
                    <div class="mt-3">
                        <form method="POST"
                              action="{{ route('tu.tahun.lanjutSemester') }}"
                              onsubmit="return confirm('Lanjut ke semester genap?')">
                            @csrf
                            <button class="btn btn-dark">
                                <i class="bi bi-arrow-right-circle me-1"></i>
                                Lanjut ke Semester Genap
                            </button>
                        </form>
                    </div>
                @elseif($tahunAktif->status === 'aktif' && $tahunAktif->semester === 'Genap')
                    <div class="mt-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                        <div>
                            <div class="fw-semibold text-success">Operasional aktif</div>
                            <div class="small text-muted">Mode: Akhir Tahun</div>
                        </div>

                        <form method="POST"
                              action="{{ route('tu.tahun.tutup') }}"
                              onsubmit="return confirm('Tutup tahun ajaran ini?')">
                            @csrf
                            <button class="btn btn-danger">
                                <i class="bi bi-door-closed me-1"></i>
                                Tutup Tahun Ajaran
                            </button>
                        </form>
                    </div>
                @elseif($tahunAktif->status === 'draft')
                    <div class="mt-3 p-3 rounded-3 bg-light text-muted small">
                        Belum aktif untuk dipakai pada operasional sistem.
                    </div>
                @elseif($tahunAktif->status === 'selesai')
                    <div class="mt-3 p-3 rounded-3 bg-secondary-subtle text-secondary-emphasis small">
                        Arsip tahun ajaran, tidak dapat diubah pada mode operasional.
                    </div>
                @endif
            @else
                <div class="d-flex align-items-center gap-3 p-3 rounded-3 bg-danger-subtle">
                    <i class="bi bi-exclamation-triangle-fill text-danger fs-4"></i>
                    <div>
                        <div class="fw-semibold text-danger">Tidak ada tahun ajaran aktif</div>
                        <div class="small text-muted">Silakan aktifkan salah satu tahun ajaran yang tersedia.</div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- 🔷 AKTIVASI TAHUN -->
    @if(!$tahunAktif || $tahunAktif->status === 'selesai')

    <div class="card mb-4 border-0 rounded-0 shadow-sm">
        <div class="card-body">

            <div class="text-muted small mb-2">
                Aktifkan Tahun Ajaran
                <p class="small mb-0 fst-italic text-danger">
                    *Harap tambahkan tahun ajaran terlebih dahulu untuk mengaktifkannya.
                </p>
            </div>

            <form method="POST" action="" id="formAktifkan">
                @csrf

                <div class="d-flex gap-2">

                    <select name="tahun_id" class="form-select rounded border border-secondary-subtle" required>
                        @foreach($tahun as $row)
                            @if($row->status === 'draft')
                                <option value="{{ $row->id }}">
                                    {{ $row->tahun }} - {{ $row->semester }}
                                </option>
                            @endif
                        @endforeach
                    </select>

                    <button class="btn btn-success">
                        Aktifkan
                    </button>

                </div>
            </form>

        </div>
    </div>

    @endif

    <!-- 🔷 TABLE -->
    {{-- TABLE --}}
    <x-table.card
        title="Daftar Tahun Ajaran"
        subtitle="Seluruh Tahun Ajaran yang terdata pada sistem ini"
    >
        <x-slot:badge>
            <span class="badge bg-primary rounded-pill px-3 py-2">
                {{ $tahun->count() }} Dokumen Sekolah
            </span>
        </x-slot:badge>
 
        <x-slot:actions>
            <input
                type="text"
                id="searchInput"
                class="form-control rounded-3"
                placeholder="Cari Tahun..."
            >
        </x-slot:actions>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th width="100">Perubahan</th>
                        <th>Tahun</th>
                        <th width="150">Semester</th>
                        <th width="150">Status</th>
                        
                    </tr>
                </thead>
                <tbody id="tableTahun">
                    @forelse($tahun as $row)
                        <tr>
                            <td class="text-center">
                                <span class="badge bg-light rounded-pill text-dark border px-3 py-2 d-flex gap-2">
                                    <i class="bi bi-calendar"></i>
                                    {{ $row->updated_at->format('d M Y') }}
                                </span>
                            </td>
                            <td>
                                <span class="fw-bold">
                                    {{ $row->tahun }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-teal fw-bold rounded-pill px-3 py-2">
                                    {{ $row->semester }}
                                </span>
                            </td>
                            <td>
                                {!! match($row->status) {
                                    'aktif'   => '<span class="badge bg-success fw-bold rounded-pill px-3 py-2"><i class="bi bi-check-circle me-1"></i>Aktif</span>',
                                    'draft'   => '<span class="badge bg-secondary fw-bold rounded-pill px-3 py-2"><i class="bi bi-archive me-1"></i>Draft</span>',
                                    'selesai' => '<span class="badge bg-light border fw-bold text-dark rounded-pill px-3 py-2"><i class="bi bi-check-circle me-1"></i>Selesai</span>',
                                } !!}
                            </td>
                            
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
                                Belum ada data tahun ajaran
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4 d-flex justify-content-end">
                {{ $tahun->links() }}
            </div>
        </div>
    </x-table.card>

</div>

<script>
const form = document.getElementById('formAktifkan');

if (form) {
    form.addEventListener('submit', function () {
        const select = form.querySelector('select');
        const id = select.value;
        form.action = `/tu/tahun/${id}/aktifkan`;
    });
}
</script>
{{-- SEARCH --}}
<script>

    const searchInput =
        document.getElementById('searchInput');

    searchInput.addEventListener('keyup', function () {

        const keyword =
            this.value.toLowerCase();

        const rows =
            document.querySelectorAll(
                '#tableTahun tr'
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