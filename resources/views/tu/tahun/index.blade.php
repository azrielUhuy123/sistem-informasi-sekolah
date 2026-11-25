@extends('layouts.app')

@section('title', 'Tahun Ajaran')

@section('content')

<div class="container-fluid dt-siswa-page">
    <x-table.breadcrumb
        :items="[
            [
                'label' => 'Operator',                                
            ],
            [
                'label' => 'Kelola Tahun Ajaran',                
            ],            
        ]"
    />

    <x-table.page-header
        icon="bi-calendar3"
        eyebrow="Data Akademik / Tahun Ajaran"
        title="Manajemen Tahun Ajaran"
        description="Kelola siklus akademik sekolah."
    />

    <x-alert.flash key="success" type="success" />
    <x-alert.flash key="error" type="danger" />


    <!-- 🔷 STATUS SISTEM -->
    <div class="card shadow-sm border-0 rounded-0 mb-4">
        <div class="card-header bg-light border-0 p-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h5 class="fw-semibold mb-1">Tahun Ajaran</h5>                    
                </div>
                <x-button.add
                    :href="route('tu.tahun.create')"
                    label="Tambah Tahun Ajaran"
                />
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
                        <span class="badge-semester {{ strtolower($tahunAktif->semester) }}">
                            {{ $tahunAktif->semester }}
                        </span>                        
                    </div>
                    <div class="small text-muted">{{ $statusSubtitle }}</div>
                </div>

                @if($tahunAktif->status === 'aktif' && $tahunAktif->semester === 'Ganjil')
                    <div class="mt-3 d-flex flex-column flex-md-row justify-content-end align-items-md-center gap-3">
                        <form method="POST"
                              action="{{ route('tu.tahun.lanjutSemester') }}">                              
                            @csrf
                            <button class="btn btn-secondary btn-sm btn-confirm"
                                data-title="Lanjut ke Semester Genap"
                                data-message="Semester Ganjil akan ditutup dan Semester Genap pada tahun ajaran yang sama akan diaktifkan.

Penempatan siswa yang valid pada Semester Ganjil akan diteruskan ke Semester Genap dengan kelas dan rombel yang sama. Riwayat penempatan Semester Ganjil tetap tersimpan.

Jika terdapat penempatan siswa atau data kelas yang tidak valid, seluruh proses akan dibatalkan dan status semester tidak berubah.

Apakah Anda yakin ingin melanjutkan?"
                                data-confirm-text="Lanjutkan"
                                data-button="btn-danger">
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
                              action="{{ route('tu.tahun.tutup') }}">
                            @csrf
                            <button class="btn btn-secondary btn-sm btn-confirm"
                                    data-title="Penutupan Tahun Ajaran dan Kenaikan Kelas"
                                    data-message="Semester Genap akan ditutup dan Semester Ganjil tahun ajaran berikutnya akan diaktifkan.

Siswa yang memenuhi ketentuan akan diproses ke tingkat berikutnya dengan Angkatan dan rombel yang sesuai. Siswa kelas VI yang telah dinyatakan lulus akan ditutup sebagai LULUS dan tidak dibuatkan penempatan pada tahun ajaran berikutnya.

Jika terdapat penempatan siswa atau kelas tujuan yang tidak valid, seluruh proses akan dibatalkan dan status tahun ajaran tidak berubah.

Apakah Anda yakin ingin melanjutkan?"
                                    data-confirm-text="Ya, Tutup Tahun & Naikkan Kelas"
                                    data-button="btn-danger">
                                <i class="bi bi-door-closed me-1"></i>
                                Penutupan Tahun Ajaran dan Kenaikan Kelas
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
        :count="$tahun->total()"
        count-label="data"
    >
 
        <x-slot:toolbar>
            <x-table.local-toolbar
                search-id="searchInput"
                search-label="Cari tahun ajaran"
                search-placeholder="Cari tahun..."
            />
        </x-slot:toolbar>
        <div class="table-responsive dt-siswa-table-wrap">
            <table class="table table-hover align-middle dt-table">
                <thead>
                    <tr>
                        <x-table.heading width="100px">Perubahan</x-table.heading>
                        <x-table.heading>Tahun</x-table.heading>
                        <x-table.heading width="150px">Semester</x-table.heading>
                        <x-table.heading width="150px">Status</x-table.heading>
                        
                    </tr>
                </thead>
                <tbody id="tableTahun">
                    @forelse($tahun as $row)
                        <tr>
                            <td>
                                <span class="d-flex align-items-center gap-2 small text-muted">                                    
                                    {{ $row->updated_at->format('d M Y') }}
                                </span>
                            </td>
                            <td>
                                <span class="fw-semibold">
                                    {{ $row->tahun }}
                                </span>
                            </td>
                            <td>
                                <span class="badge-semester {{ strtolower($row->semester) }}">
                                    {{ $row->semester }}
                                </span>
                            </td>
                            <td>
                                <span class="badge-status {{ $row->status }}">
                                    @if($row->status === 'aktif')
                                        <i class="bi bi-circle-fill me-2"></i>
                                    @elseif($row->status === 'draft')
                                        <i class="bi bi-pencil-square me-2"></i>
                                    @elseif($row->status === 'selesai')
                                        <i class="bi bi-check-circle me-2"></i>
                                    @endif

                                    {{ ucfirst($row->status) }}
                                </span>
                            </td>
                            
                        </tr>
                    @empty
                        <x-table.empty
                            :colspan="4"
                            title="Belum ada data tahun ajaran"
                            description="Data tahun ajaran belum tersedia."
                        />
                    @endforelse
                </tbody>
            </table>  
        </div>
        <x-table.pagination :paginator="$tahun" />
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