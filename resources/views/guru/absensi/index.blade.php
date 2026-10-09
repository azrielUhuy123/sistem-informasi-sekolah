@extends('layouts.app')

@section('title', 'Absensi')

@section('content')

@php

    $totalSiswa = $penempatan->count();

    $hadir = $penempatan->filter(function($p) use ($jadwal){

        return optional(
            \App\Models\AbsensiSiswa::where('jadwal_id',$jadwal?->id)
                ->where('siswa_id',$p->siswa->id)
                ->where('tanggal',date('Y-m-d'))
                ->first()
        )->status == 'hadir';

    })->count();

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
                        Absensi Kelas
                    </h3>

                    <p class="mb-0 opacity-75">
                        Kelola absensi harian siswa kelas
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

    <x-alert.flash key="success" type="success"/>
    <x-alert.flash key="error" type="danger"/>

    {{-- FILTER --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-header bg-white border-0 py-3">

            <h5 class="fw-bold mb-0">

                Jadwal Mengajar Hari Ini

            </h5>

        </div>

        <div class="card-body">
            <form method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-7">
                        <label class="form-label fw-semibold">
                            Jadwal
                        </label>

                        <select
                            name="jadwal_id"
                            class="form-control"
                            onchange="this.form.submit()">
                            @forelse($jadwals as $j)
                                <option
                                    value="{{ $j->id }}"
                                    {{ $jadwalId==$j->id?'selected':'' }}>
                                    {{ $j->kelas->nama_kelas_label }}
                                    •
                                    {{ $j->mapel->nama_mapel }}
                                    •
                                    Jam ke-{{ $j->jamPelajaran->jam_ke }}
                                </option>
                            @empty
                                <option>
                                    Tidak ada jadwal hari ini
                                </option>
                            @endforelse
                        </select>
                    </div>

                    <div class="col-lg-3">
                        <label class="form-label fw-semibold">
                            Tanggal
                        </label>
                        <input
                            type="date"
                            class="form-control"
                            value="{{ request('tanggal') ?? date('Y-m-d') }}"
                            disabled>
                    </div>
                    <div class="col-lg-2">
                        <div class="text-center text-label">
                            <h3 class="mb-1">
                                {{ $jadwals->count() }}
                            </h3>
                            <small>
                                Jadwal
                            </small>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($jadwal)

        <div class="row g-3 mb-4">

            <div class="col-md-3">
                <div class="card bg-white shadow-sm border-0 rounded-4 p-3 h-100">
                    <small>Kelas</small>
                    <h6>{{ $jadwal->kelas->nama_kelas_label }}</h6>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card bg-white shadow-sm border-0 rounded-4 p-3 h-100">
                    <small>Mata Pelajaran</small>
                    <h6>{{ $jadwal->mapel->nama_mapel }}</h6>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card bg-white shadow-sm border-0 rounded-4 p-3 h-100">
                    <small>Hari</small>
                    <h6>{{ $jadwal->hari->nama }}</h6>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card bg-white shadow-sm border-0 rounded-4 p-3 h-100">
                    <small>Jam</small>
                    <h6>
                        {{ substr($jadwal->jamPelajaran->mulai,0,5) }}
                        -
                        {{ substr($jadwal->jamPelajaran->selesai,0,5) }}
                    </h6>
                </div>
            </div>
        </div>

        <form
            action="{{ route('guru.absensi.store') }}"
            method="POST">

            @csrf

            <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">

            <input type="hidden" name="kelas_id" value="{{ $jadwal->kelas_id }}">

            <input type="hidden" name="tanggal" value="{{ date('Y-m-d') }}">

            <x-table.card
                title="Daftar Kehadiran Siswa"
                subtitle="{{ $penempatan->count() }} Siswa"
            >

                <x-slot:badge>
                    <span class="badge bg-primary rounded-pill px-2 py-1">
                        {{ $totalSiswa }}
                        <span class="text-badge">Siswa</span>
                    </span>
                </x-slot:badge>

                <x-slot:actions>
                    <div class="d-flex gap-2 align-items-end">
                        <select
                            class="form-control"
                            id="bulkAction"
                            style="width:220px">
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
                                            id="tableCheckAll">

                                    </th>

                                    <th width="70">

                                        No

                                    </th>

                                    <th>

                                        Nama Siswa

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

    @forelse($penempatan as $i => $p)

        @php

            $absen = \App\Models\AbsensiSiswa::where('jadwal_id',$jadwal->id)
                ->where('siswa_id',$p->siswa->id)
                ->where('tanggal',date('Y-m-d'))
                ->first();

            $status = $absen->status ?? 'hadir';

            $badge = match($status){

                'hadir' => 'success',

                'izin' => 'warning',

                'sakit' => 'info',

                default => 'danger'

            };

        @endphp

        <tr>

            <td>

                <input
                    type="checkbox"
                    class="form-check-input student-checkbox">

            </td>

            <td>

                <span class="badge bg-light text-dark rounded-circle">

                    {{ $i+1 }}

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

                            {{ $p->siswa->nama }}

                        </div>

                        <small class="text-muted">

                            NIS {{ $p->siswa->nis }}

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

                <input
                    type="hidden"
                    name="absensi[{{ $p->siswa->id }}][status]"
                    value="{{ $status }}"
                    class="statusInput">

                <span
                    class="badge rounded-pill px-3 py-2 statusBadge bg-{{ $badge }}">

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