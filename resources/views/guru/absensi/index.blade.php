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

<div class="container-fluid py-4">

    {{-- HERO --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

        <div class="card-body p-4 p-lg-5 text-white"
            style="background:linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);">

            <div class="row align-items-center">

                <div class="col-lg-8">

                    <h2 class="fw-bold mb-2">

                        Absensi Siswa

                    </h2>

                    <p class="mb-0 opacity-75">

                        Kelola kehadiran siswa berdasarkan jadwal pembelajaran hari ini.

                    </p>

                </div>

                <div class="col-lg-4 mt-4 mt-lg-0">

                    <div class="row g-3">

                        <div class="col-6">

                            <div class="bg-white rounded-4 text-center p-3">

                                <h4 class="fw-bold text-primary mb-1">

                                    {{ $jadwals->count() }}

                                </h4>

                                <small class="text-muted">

                                    Jadwal

                                </small>

                            </div>

                        </div>

                        <div class="col-6">

                            <div class="bg-white rounded-4 text-center p-3">

                                <h4 class="fw-bold text-success mb-1">

                                    {{ $totalSiswa }}

                                </h4>

                                <small class="text-muted">

                                    Siswa

                                </small>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- ALERT --}}

    @if(session('success'))

        <div class="alert alert-success border-0 shadow-sm rounded-4">

            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

        </div>

    @endif

    @if(session('error'))

        <div class="alert alert-danger border-0 shadow-sm rounded-4">

            <i class="bi bi-exclamation-triangle-fill me-2"></i>

            {{ session('error') }}

        </div>

    @endif

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
                            class="form-select rounded-3"
                            onchange="this.form.submit()">

                            @forelse($jadwals as $j)

                                <option
                                    value="{{ $j->id }}"
                                    {{ $jadwalId==$j->id?'selected':'' }}>

                                    {{ $j->kelas->nama_kelas }}
                                    •
                                    {{ $j->mapel->nama_mapel }}
                                    •
                                    Jam {{ $j->jamPelajaran->jam_ke }}

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
                            class="form-control rounded-3"
                            value="{{ request('tanggal') ?? date('Y-m-d') }}"
                            disabled>

                    </div>

                    <div class="col-lg-2">

                        <div class="summary-card">

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

                <div class="info-card">

                    <small>Kelas</small>

                    <h6>{{ $jadwal->kelas->nama_kelas }}</h6>

                </div>

            </div>

            <div class="col-md-3">

                <div class="info-card">

                    <small>Mata Pelajaran</small>

                    <h6>{{ $jadwal->mapel->nama_mapel }}</h6>

                </div>

            </div>

            <div class="col-md-3">

                <div class="info-card">

                    <small>Hari</small>

                    <h6>{{ $jadwal->hari->nama }}</h6>

                </div>

            </div>

            <div class="col-md-3">

                <div class="info-card">

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

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-header bg-white border-0 py-4">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                        <div>

                            <h5 class="fw-bold mb-1">

                                Daftar Kehadiran Siswa

                            </h5>

                            <small class="text-muted">

                                {{ $penempatan->count() }} Siswa

                            </small>

                        </div>

                        <div class="d-flex gap-2">

                            <select
                                class="form-select rounded-pill"
                                id="bulkAction"
                                style="width:180px">

                                <option value="">

                                    Aksi Massal

                                </option>

                                <option value="hadir">Hadir</option>

                                <option value="izin">Izin</option>

                                <option value="sakit">Sakit</option>

                                <option value="alpha">Alpha</option>

                            </select>

                            <button class="btn btn-primary rounded-pill px-4">

                                <i class="bi bi-check-circle-fill me-2"></i>

                                Simpan

                            </button>

                        </div>

                    </div>

                </div>

                <div class="card-body p-0">

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

                                        Hadir

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

                <span class="badge bg-light text-dark rounded-pill px-3 py-2">

                    {{ $i+1 }}

                </span>

            </td>

            <td>

                <div class="d-flex align-items-center">

                    <div class="student-avatar me-3">

                        {{ strtoupper(substr($p->siswa->nama,0,1)) }}

                    </div>

                    <div>

                        <div class="fw-bold">

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

            <td colspan="6">

                <div class="text-center py-5">

                    <i class="bi bi-person-x display-3 text-secondary"></i>

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

<style>

.card{

    transition:.25s;

}

.card:hover{

    box-shadow:0 18px 45px rgba(37,99,235,.08)!important;

}

.summary-card{

    background:#f8fafc;

    border-radius:18px;

    padding:18px;

    text-align:center;

}

.summary-card h3{

    font-weight:700;

    margin-bottom:2px;

}

.info-card{

    background:#fff;

    border-radius:18px;

    padding:20px;

    box-shadow:0 .125rem .5rem rgba(0,0,0,.05);

    height:100%;

}

.info-card small{

    color:#6c757d;

    display:block;

    margin-bottom:6px;

}

.info-card h6{

    margin:0;

    font-weight:700;

}

.student-avatar{

    width:46px;

    height:46px;

    border-radius:50%;

    background:linear-gradient(135deg,#2563eb,#0f4c81);

    color:#fff;

    display:flex;

    align-items:center;

    justify-content:center;

    font-weight:700;

    flex-shrink:0;

}

.table thead th{

    background:#f8fafc;

    border:0;

    color:#64748b;

    text-transform:uppercase;

    font-size:.82rem;

    letter-spacing:.5px;

    padding:18px;

}

.table tbody td{

    padding:18px;

    vertical-align:middle;

    border-color:#eef2f7;

}

.table-hover tbody tr:hover{

    background:#f8fbff;

}

.form-select:focus{

    border-color:#2563eb;

    box-shadow:0 0 0 .2rem rgba(37,99,235,.15);

}

.badge{

    font-weight:600;

}

.btn{

    font-weight:600;

    transition:.2s;

}

.btn:hover{

    transform:translateY(-2px);

}

</style>

@endsection