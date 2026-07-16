@extends('layouts.app')

@section('title', 'Absensi')

@section('content')

<div class="container-fluid py-3">

    {{-- HEADER --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2">Manajemen Absensi</h3>
                    <p class="mb-0 opacity-75">Kelola absensi siswa dalam setiap jadwal pelajaran</p>
                </div>
                <div class="badge bg-white text-primary px-3 py-2 fs-6">
                    <i class="bi bi-shield-check me-2"></i> Sistem Terhubung
                </div>
            </div>
        </div>
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

    {{-- FILTER JADWAL --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="GET">

                <div class="row g-3 align-items-end">

                    {{-- JADWAL --}}
                    <div class="col-lg-7">

                        <label class="form-label">

                            Jadwal Hari Ini

                        </label>

                        <select
                            name="jadwal_id"
                            class="form-select"
                            onchange="this.form.submit()"
                        >

                            @forelse($jadwals as $j)

                                <option
                                    value="{{ $j->id }}"
                                    {{
                                        $jadwalId == $j->id
                                        ? 'selected'
                                        : ''
                                    }}
                                >

                                    {{ $j->kelas->nama_kelas }}
                                    -
                                    {{ $j->mapel->nama_mapel }}
                                    -
                                    Jam {{ $j->jamPelajaran->jam_ke }}
                                    (
                                    {{ substr($j->jamPelajaran->mulai, 0, 5) }}
                                    -
                                    {{ substr($j->jamPelajaran->selesai, 0, 5) }}
                                    )

                                </option>

                            @empty

                                <option>

                                    Tidak ada jadwal hari ini

                                </option>

                            @endforelse

                        </select>

                    </div>

                    {{-- TANGGAL --}}
                    <div class="col-lg-3">

                        <label class="form-label">

                            Tanggal

                        </label>

                        <input
                            type="date"
                            class="form-control"
                            value="{{ request('tanggal') ?? date('Y-m-d') }}"
                            disabled
                        >

                    </div>

                    {{-- INFO --}}
                    <div class="col-lg-2">

                        <div class="small text-muted">

                            Total Jadwal

                        </div>

                        <div class="fw-bold fs-5">

                            {{ $jadwals->count() }}

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>

    {{-- DETAIL JADWAL --}}
    @if($jadwal)

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-body">

                <div class="row">

                    <div class="col-md-3">

                        <small class="text-muted d-block">

                            Kelas

                        </small>

                        <div class="fw-semibold">

                            {{ $jadwal->kelas->nama_kelas }}

                        </div>

                    </div>

                    <div class="col-md-3">

                        <small class="text-muted d-block">

                            Mata Pelajaran

                        </small>

                        <div class="fw-semibold">

                            {{ $jadwal->mapel->nama_mapel }}

                        </div>

                    </div>

                    <div class="col-md-3">

                        <small class="text-muted d-block">

                            Hari

                        </small>

                        <div class="fw-semibold">

                            {{ $jadwal->hari->nama }}

                        </div>

                    </div>

                    <div class="col-md-3">

                        <small class="text-muted d-block">

                            Jam Pelajaran

                        </small>

                        <div class="fw-semibold">

                            Jam {{ $jadwal->jamPelajaran->jam_ke }}
                            (
                            {{ substr($jadwal->jamPelajaran->mulai, 0, 5) }}
                            -
                            {{ substr($jadwal->jamPelajaran->selesai, 0, 5) }}
                            )

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- FORM ABSENSI --}}
        <form
            action="{{ route('guru.absensi.store') }}"
            method="POST"
        >

            @csrf

            <input
                type="hidden"
                name="jadwal_id"
                value="{{ $jadwal->id }}"
            >

            <input
                type="hidden"
                name="kelas_id"
                value="{{ $jadwal->kelas_id }}"
            >

            <input
                type="hidden"
                name="tanggal"
                value="{{ date('Y-m-d') }}"
            >

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    {{-- HEADER --}}
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

                        <div>

                            <h5 class="fw-bold mb-1">

                                Daftar Kehadiran Siswa

                            </h5>

                            <small class="text-muted">

                                Total siswa:
                                {{ $penempatan->count() }}

                            </small>

                        </div>

                        {{-- ACTION --}}
                        <div class="d-flex align-items-center gap-2">

                            <select
                                class="form-select"
                                id="bulkAction"
                                style="width: 180px"
                            >

                                <option value="">

                                    Aksi Massal

                                </option>

                                <option value="hadir">

                                    Hadir

                                </option>

                                <option value="izin">

                                    Izin

                                </option>

                                <option value="sakit">

                                    Sakit

                                </option>

                                <option value="alpha">

                                    Alpha

                                </option>

                            </select>

                            <button class="btn btn-primary px-4">

                                Simpan Absensi

                            </button>

                        </div>

                    </div>

                    {{-- TABLE --}}
                    <div class="table-responsive">

                        <table class="table align-middle">

                            <thead class="table-light">

                                <tr>

                                    {{-- CHECKBOX ALL --}}
                                    <th width="50">

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

                                        Nama Siswa

                                    </th>

                                    <th width="120">

                                        Jam Hadir

                                    </th>

                                    <th width="220">

                                        Update Kehadiran

                                    </th>

                                    <th width="180">

                                        Status Kehadiran

                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse($penempatan as $i => $p)

                                    @php

                                        $absen = \App\Models\AbsensiSiswa::where(
                                                'jadwal_id',
                                                $jadwal->id
                                            )
                                            ->where(
                                                'siswa_id',
                                                $p->siswa->id
                                            )
                                            ->where(
                                                'tanggal',
                                                date('Y-m-d')
                                            )
                                            ->first();

                                        $status =
                                            $absen->status ?? 'hadir';

                                    @endphp

                                    <tr>

                                        {{-- CHECKBOX --}}
                                        <td>

                                            <input
                                                type="checkbox"
                                                class="form-check-input student-checkbox"
                                            >

                                        </td>

                                        {{-- NO --}}
                                        <td>

                                            {{ $i + 1 }}

                                        </td>

                                        {{-- NAMA --}}
                                        <td>

                                            <div class="fw-semibold">

                                                {{ $p->siswa->nama }}

                                            </div>

                                            <small class="text-muted">

                                                NIS:
                                                {{ $p->siswa->nis }}

                                            </small>

                                        </td>

                                        {{-- JAM HADIR --}}
                                        <td>

                                            <small class="text-muted">

                                                {{
                                                    optional(
                                                        $absen?->created_at
                                                    )->format('H:i')
                                                    ?? '--'
                                                }}

                                            </small>

                                        </td>

                                        {{-- UPDATE --}}
                                        <td>

                                            <small class="text-muted">

                                                {{
                                                    optional(
                                                        $absen?->updated_at
                                                    )->format('d M Y H:i')
                                                    ?? '--'
                                                }}

                                            </small>

                                        </td>

                                        {{-- STATUS --}}
                                        <td>

                                            <input
                                                type="hidden"
                                                name="absensi[{{ $p->siswa->id }}][status]"
                                                value="{{ $status }}"
                                                class="statusInput"
                                            >

                                            <span
                                                class="
                                                    badge
                                                    statusBadge
                                                    bg-{{
                                                        match($status) {
                                                            'hadir' => 'success',
                                                            'izin' => 'warning',
                                                            'sakit' => 'info',
                                                            default => 'danger'
                                                        }
                                                    }}
                                                "
                                            >

                                                {{ ucfirst($status) }}

                                            </span>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="6"
                                            class="text-center text-muted py-4"
                                        >

                                            Tidak ada siswa pada kelas ini

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

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

                    let rows =
                        document.querySelectorAll('.table tbody tr');

                    rows.forEach(row => {

                        let checked =
                            row.querySelector('.student-checkbox');

                        if (
                            checked &&
                            checked.checked
                        ) {

                            let badge =
                                row.querySelector('.statusBadge');

                            let input =
                                row.querySelector('.statusInput');

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

                            badge.className =
                                'badge statusBadge';

                            /*
                            |--------------------------------------------------------------------------
                            | COLOR
                            |--------------------------------------------------------------------------
                            */

                            if (action === 'hadir') {

                                badge.classList.add('bg-success');

                            }

                            else if (action === 'izin') {

                                badge.classList.add('bg-warning');

                            }

                            else if (action === 'sakit') {

                                badge.classList.add('bg-info');

                            }

                            else {

                                badge.classList.add('bg-danger');

                            }

                            /*
                            |--------------------------------------------------------------------------
                            | TEXT
                            |--------------------------------------------------------------------------
                            */

                            badge.innerText =
                                action.charAt(0).toUpperCase()
                                + action.slice(1);

                        }

                    });

                });

        </script>





    @endif

</div>

@endsection