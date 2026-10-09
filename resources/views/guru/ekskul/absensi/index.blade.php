@extends('layouts.app')

@section('title', 'Absensi Ekskul')

@section('content')

@php
    $jadwalHari = $jadwal->sortBy('jam_mulai')->first()?->hari?->nama ?? '-';
    $jadwalMulai = $jadwal->sortBy('jam_mulai')->first()?->jam_mulai;
    $jadwalSelesai = $jadwal->sortBy('jam_mulai')->first()?->jam_selesai;

    $totalAnggota = $anggota->count();
    $summaryCounts = ['hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alpha' => 0];

    foreach ($anggota as $a) {
        $absen = \App\Models\AbsensiEkskul::where('ekskul_id', $ekskul->id)
            ->where('siswa_id', $a->siswa_id)
            ->where('tanggal', $tanggal)
            ->first();

        $status = $absen->status ?? 'hadir';
        $summaryCounts[$status] = ($summaryCounts[$status] ?? 0) + 1;
    }

    $hadirCount = $summaryCounts['hadir'] ?? 0;
    $izinSakitCount = ($summaryCounts['izin'] ?? 0) + ($summaryCounts['sakit'] ?? 0);
    $alphaCount = $summaryCounts['alpha'] ?? 0;
@endphp

<div class="container-fluid">

    {{-- HERO --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div
            class="card-body p-4 p-lg-5 text-white"
            style="background:linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);"
        >
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2">
                        Absensi Ekstrakurikuler
                    </h3>
                    <p class="mb-0 opacity-75">
                        Kelola kehadiran anggota ekstrakurikuler pada setiap pertemuan secara cepat menggunakan sistem absensi digital.
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
    <x-alert.flash key="success" type="success" />
    <x-alert.flash key="error" type="danger" />

    {{-- FILTER --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="fw-bold mb-0">
                Informasi Pertemuan
            </h5>
        </div>

        <div class="card-body">
            <form method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-8">
                        <label class="form-label fw-semibold">
                            Ekskul
                        </label>
                        <input
                            type="text"
                            class="form-control"
                            value="{{ $ekskul->nama }}"
                            readonly
                        >
                    </div>

                    <div class="col-lg-4">
                        <label class="form-label fw-semibold">
                            Tanggal
                        </label>
                        <input
                            type="date"                            
                            class="form-control"
                            value="{{ request('tanggal') ?? date('Y-m-d') }}"
                            disabled
                        >
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card bg-white shadow-sm border-0 rounded-4 p-3 h-100">
                <small>Nama Ekskul</small>
                <h6>{{ $ekskul->nama }}</h6>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-white shadow-sm border-0 rounded-4 p-3 h-100">
                <small>Hari</small>
                <h6>{{ $jadwalHari }}</h6>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-white shadow-sm border-0 rounded-4 p-3 h-100">
                <small>Jam</small>
                <h6>{{ $jadwalMulai ? substr($jadwalMulai,0,5) : '-' }} - {{ $jadwalSelesai ? substr($jadwalSelesai,0,5) : '-' }}</h6>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-white shadow-sm border-0 rounded-4 p-3 h-100">
                <small>Total Anggota</small>
                <h6>
                    {{ $totalAnggota }}
                </h6>
            </div>
        </div>
    </div>

    @if ($jadwalAda)
        <form
            action="{{ route('guru.ekskul.absensi.store') }}"
            method="POST"
        >
            @csrf
            <input type="hidden" name="tanggal" value="{{ $tanggal }}">

            <x-table.card
                title="Daftar Kehadiran Anggota"
                subtitle="Total anggota: {{ $totalAnggota }}"
            >
                <x-slot:badge>
                    <span class="badge bg-primary rounded-pill px-2 py-1">
                        {{ $totalAnggota }}
                        <span class="text-badge">Anggota</span>
                    </span>
                </x-slot:badge>

                <x-slot:actions>
                    <div class="d-flex gap-2 align-items-end">
                        <select
                            class="form-control"
                            id="bulkAction"
                            style="width:180px"
                        >
                            <option value="">Aksi Massal</option>
                            <option value="hadir">Hadir</option>
                            <option value="izin">Izin</option>
                            <option value="sakit">Sakit</option>
                            <option value="alpha">Alpha</option>
                        </select>

                        <button class="btn btn-success rounded-3 px-3" type="submit">
                            <span class="desc-add">Simpan</span><i class="bi bi-save px-1"></i>
                        </button>
                    </div>
                </x-slot:actions>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th width="55">
                                    <input
                                        type="checkbox"
                                        class="form-check-input"
                                        id="tableCheckAll"
                                    >
                                </th>
                                <th width="70">
                                    No
                                </th>
                                <th>
                                    Nama
                                </th>
                                <th width="120">
                                    Waktu
                                </th>
                                <th width="220">
                                    Update
                                </th>
                                <th width="180">
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($anggota as $i => $a)
                                @php
                                    $absen = \App\Models\AbsensiEkskul::where('ekskul_id', $ekskul->id)
                                        ->where('siswa_id', $a->siswa_id)
                                        ->where('tanggal', $tanggal)
                                        ->first();

                                    $status = $absen->status ?? 'hadir';
                                    $namaSiswa = optional($a->siswa)->nama ?? 'Data Siswa Hilang';
                                    $nisSiswa = optional($a->siswa)->nis ?? '-';
                                    $inisial = strtoupper(substr(trim($namaSiswa), 0, 1));
                                @endphp
                                <tr>
                                    <td>
                                        <input
                                            type="checkbox"
                                            class="form-check-input student-checkbox"
                                        >
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark rounded-circle">
                                            {{ $i + 1 }}
                                        </span>
                                    </td>
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
                                                    {{ $namaSiswa }}
                                                </div>
                                                <small class="text-muted">
                                                    NIS {{ $nisSiswa }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-muted">
                                            {{
                                                optional($absen?->created_at)->format('H:i')
                                                ?? '--'
                                            }}
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{
                                                optional($absen?->updated_at)->format('d M Y H:i')
                                                ?? '--'
                                            }}
                                        </small>
                                    </td>
                                    <td>
                                        <input type="hidden" name="absensi[{{ $a->siswa_id }}][status]" value="{{ $status }}" class="statusInput">
                                        <span class="badge statusBadge bg-{{ match($status) { 'hadir' => 'success', 'izin' => 'warning', 'sakit' => 'info', default => 'danger' } }}">
                                            {{ ucfirst($status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="bi bi-person-x display-4 text-secondary"></i>
                                            <h5 class="fw-bold mt-3">
                                                Belum Ada Data Siswa
                                            </h5>
                                            <p class="text-muted mb-0">
                                                Tidak ada siswa pada jadwal yang dipilih.
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-table.card>
        </form>

        <script>
            /*
            |--------------------------------------------------------------------------
            | CHECKBOX ALL
            |--------------------------------------------------------------------------
            */

            document
                .getElementById('tableCheckAll')
                .addEventListener('change', function () {
                    document
                        .querySelectorAll('.student-checkbox')
                        .forEach(cb => {
                            cb.checked = this.checked;
                        });
                });

            /*
            |--------------------------------------------------------------------------
            | BULK ACTION AUTO
            |--------------------------------------------------------------------------
            */

            document
                .getElementById('bulkAction')
                .addEventListener('change', function () {
                    let action = this.value;

                    if (!action) return;

                    let rows = document.querySelectorAll('.table tbody tr');

                    rows.forEach(row => {
                        let checked = row.querySelector('.student-checkbox');

                        if (checked && checked.checked) {
                            let badge = row.querySelector('.statusBadge');
                            let input = row.querySelector('.statusInput');

                            /*
                            |--------------------------------------------------------------------------
                            | UPDATE INPUT
                            |--------------------------------------------------------------------------
                            */

                            input.value = action;

                            /*
                            |--------------------------------------------------------------------------
                            | RESET BADGE
                            |--------------------------------------------------------------------------
                            */

                            badge.className = 'badge statusBadge';

                            /*
                            |--------------------------------------------------------------------------
                            | COLOR
                            |--------------------------------------------------------------------------
                            */

                            if (action === 'hadir') {
                                badge.classList.add('bg-success');
                            } else if (action === 'izin') {
                                badge.classList.add('bg-warning');
                            } else if (action === 'sakit') {
                                badge.classList.add('bg-info');
                            } else {
                                badge.classList.add('bg-danger');
                            }

                            /*
                            |--------------------------------------------------------------------------
                            | TEXT
                            |--------------------------------------------------------------------------
                            */

                            badge.innerText =
                                action.charAt(0).toUpperCase() + action.slice(1);
                        }
                    });
                });
        </script>
    @else
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body text-center py-5">
                <h5 class="fw-bold mb-2">Tidak Ada Jadwal Ekskul</h5>
                <p class="text-muted mb-0">Tidak ada jadwal ekskul pada tanggal yang dipilih.</p>
            </div>
        </div>
    @endif
</div>

@endsection