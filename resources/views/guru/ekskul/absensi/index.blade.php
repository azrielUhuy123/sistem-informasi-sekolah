@extends('layouts.app')

@section('title', 'Absensi Ekskul')

@section('content')

<div class="container-fluid py-3">

    {{-- HEADER --}}
    <div class="mb-4">

        {{-- HEADER --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2">Manajemen Absensi Ekskul</h3>
                    <p class="mb-0 opacity-75">Kelola kehadiran anggota ekstrakurikuler</p>
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

    {{-- FILTER --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="GET">

                <div class="row g-3 align-items-end">

                    {{-- EKSKUL --}}
                    <div class="col-lg-8">

                        <label class="form-label">

                            Ekskul

                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $ekskul->nama }}"
                            readonly
                        >

                    </div>

                    {{-- TANGGAL --}}
                    <div class="col-lg-4">

                        <label class="form-label">

                            Tanggal Pertemuan

                        </label>

                        <input
                            type="date"
                            name="tanggal"
                            class="form-control"
                            value="{{ $tanggal }}"
                            onchange="this.form.submit()"
                        >

                    </div>

                </div>

            </form>

        </div>

    </div>

    {{-- DETAIL EKSKUL --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="row">

                <div class="col-md-3">

                    <small class="text-muted d-block">

                        Nama Ekskul

                    </small>

                    <div class="fw-semibold">

                        {{ $ekskul->nama }}

                    </div>

                </div>

                <div class="col-md-3">

                    <small class="text-muted d-block">

                        Hari

                    </small>

                    <div class="fw-semibold">

                        {{ $ekskul->hari }}

                    </div>

                </div>

                <div class="col-md-3">

                    <small class="text-muted d-block">

                        Jam

                    </small>

                    <div class="fw-semibold">

                        {{ substr($ekskul->jam_mulai,0,5) }}
                        -
                        {{ substr($ekskul->jam_selesai,0,5) }}

                    </div>

                </div>

                <div class="col-md-3">

                    <small class="text-muted d-block">

                        Total Anggota

                    </small>

                    <div class="fw-semibold">

                        {{ $anggota->count() }}

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- FORM --}}
    <form
        action="{{ route('guru.ekskul.absensi.store') }}"
        method="POST"
    >

        @csrf

        <input
            type="hidden"
            name="tanggal"
            value="{{ $tanggal }}"
        >

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

                    <div>

                        <h5 class="fw-bold mb-1">

                            Daftar Kehadiran Anggota

                        </h5>

                        <small class="text-muted">

                            Total anggota:
                            {{ $anggota->count() }}

                        </small>

                    </div>

                    <div class="d-flex gap-2">

                        <select
                            class="form-select"
                            id="bulkAction"
                            style="width:180px"
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

                        <button class="btn btn-dark">

                            Simpan Absensi

                        </button>

                    </div>

                </div>

                <div class="table-responsive">

                    <table class="table align-middle">

                        <thead class="table-light">

                            <tr>

                                <th width="50">

                                    <input
                                        type="checkbox"
                                        id="tableCheckAll"
                                        class="form-check-input"
                                    >

                                </th>

                                <th width="70">

                                    No

                                </th>

                                <th>

                                    Nama Siswa

                                </th>

                                <th width="120">

                                    NIS

                                </th>

                                <th width="140">

                                    Jam Input

                                </th>

                                <th width="200">

                                    Terakhir Update

                                </th>

                                <th width="180">

                                    Status

                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($anggota as $i => $a)

                                @php

                                    $absen =
                                        \App\Models\AbsensiEkskul::where(
                                            'ekskul_id',
                                            $ekskul->id
                                        )
                                        ->where(
                                            'siswa_id',
                                            $a->siswa_id
                                        )
                                        ->where(
                                            'tanggal',
                                            $tanggal
                                        )
                                        ->first();

                                    $status =
                                        $absen->status
                                        ??
                                        'hadir';

                                @endphp

                                <tr>

                                    <td>

                                        <input
                                            type="checkbox"
                                            class="form-check-input student-checkbox"
                                        >

                                    </td>

                                    <td>

                                        {{ $i + 1 }}

                                    </td>

                                    <td>

                                        <div class="fw-semibold">

                                            {{ $a->siswa->nama }}

                                        </div>

                                    </td>

                                    <td>

                                        {{ $a->siswa->nis }}

                                    </td>

                                    <td>

                                        {{
                                            optional(
                                                $absen?->created_at
                                            )->format('H:i')
                                            ??
                                            '--'
                                        }}

                                    </td>

                                    <td>

                                        {{
                                            optional(
                                                $absen?->updated_at
                                            )->format('d M Y H:i')
                                            ??
                                            '--'
                                        }}

                                    </td>

                                    <td>

                                        <input
                                            type="hidden"
                                            name="absensi[{{ $a->siswa_id }}][status]"
                                            value="{{ $status }}"
                                            class="statusInput"
                                        >

                                        <span
                                            class="
                                                badge
                                                statusBadge
                                                bg-{{
                                                    match($status){
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
                                        colspan="7"
                                        class="text-center text-muted py-4"
                                    >

                                        Belum ada anggota ekskul

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </form>

</div>

<script>

document
.getElementById('tableCheckAll')
.addEventListener('change', function() {

    document
    .querySelectorAll('.student-checkbox')
    .forEach(cb => {

        cb.checked = this.checked;

    });

});

document
.getElementById('bulkAction')
.addEventListener('change', function() {

    let action = this.value;

    if(!action) return;

    document
    .querySelectorAll('tbody tr')
    .forEach(row => {

        let check =
            row.querySelector('.student-checkbox');

        if(check && check.checked){

            let input =
                row.querySelector('.statusInput');

            let badge =
                row.querySelector('.statusBadge');

            input.value = action;

            badge.className =
                'badge statusBadge';

            if(action === 'hadir')
                badge.classList.add('bg-success');

            else if(action === 'izin')
                badge.classList.add('bg-warning');

            else if(action === 'sakit')
                badge.classList.add('bg-info');

            else
                badge.classList.add('bg-danger');

            badge.innerText =
                action.charAt(0).toUpperCase()
                +
                action.slice(1);
        }

    });

});

</script>

@endsection