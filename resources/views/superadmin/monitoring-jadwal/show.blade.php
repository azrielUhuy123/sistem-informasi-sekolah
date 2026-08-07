@extends('layouts.app')

@section('title', 'Monitoring Jadwal Kelas ' . $kelas->nama_kelas_label)

@section('content')

<div class="container-fluid">

    {{-- ================= HEADER ================= --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

        <div class="card-body p-4 p-lg-5 text-white"
             style="background:linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);">

            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center">

                <div>

                    <h2 class="fw-bold mb-2">

                        Monitoring Jadwal

                    </h2>

                    <p class="mb-0 opacity-75">

                        Monitoring, validasi, persetujuan, dan publikasi jadwal pelajaran kelas
                        <strong>{{ $kelas->nama_kelas_label }}</strong>.

                    </p>

                </div>

                <div class="mt-3 mt-lg-0">

                    <div class="bg-white rounded-4 px-4 py-3 text-center">

                        <h2 class="fw-bold text-primary mb-0">

                            {{ $jadwals->count() }}

                        </h2>

                        <small class="text-muted">

                            Total Jadwal

                        </small>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- INFO RINGKAS --}}
    @php
        $submitted = $jadwals->where('status', 'submitted')->count();
        $approved = $jadwals->where('status', 'approved')->count();
        $rejected = $jadwals->where('status', 'rejected')->count();
    @endphp

    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body text-center">

                    <i class="bi bi-hourglass-split text-warning display-6"></i>

                    <h3 class="fw-bold mt-2 mb-0">

                        {{ $submitted }}

                    </h3>

                    <small class="text-muted">

                        Submitted

                    </small>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body text-center">

                    <i class="bi bi-check-circle-fill text-success display-6"></i>

                    <h3 class="fw-bold mt-2 mb-0">

                        {{ $approved }}

                    </h3>

                    <small class="text-muted">

                        Approved

                    </small>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body text-center">

                    <i class="bi bi-x-circle-fill text-danger display-6"></i>

                    <h3 class="fw-bold mt-2 mb-0">

                        {{ $rejected }}

                    </h3>

                    <small class="text-muted">

                        Rejected

                    </small>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body text-center">

                    <i class="bi bi-calendar-check-fill text-primary display-6"></i>

                    <h3 class="fw-bold mt-2 mb-0">

                        {{ $jadwals->count() }}

                    </h3>

                    <small class="text-muted">

                        Total Jadwal

                    </small>

                </div>

            </div>

        </div>

    </div>

    {{-- ================= TOOLBAR ================= --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-body">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                {{-- LEFT --}}
                <div class="d-flex flex-wrap gap-2">

                    <a href="{{ route('superadmin.monitoring-jadwal.index') }}"
                    class="btn btn-light border rounded-pill px-4">

                        <i class="bi bi-arrow-left me-2"></i>

                        Kembali

                    </a>

                    <a
                        href="{{ route(
                            'superadmin.monitoring-jadwal.show',
                            [
                                'kelas'=>$kelas->id,
                                'view'=>'grid'
                            ]
                        ) }}"
                        class="btn rounded-pill px-4
                        {{ $view=='grid'
                            ? 'btn-primary'
                            : 'btn-outline-primary'
                        }}">

                        <i class="bi bi-grid-3x3-gap-fill me-2"></i>

                        Grid View

                    </a>

                    <a
                        href="{{ route(
                            'superadmin.monitoring-jadwal.show',
                            [
                                'kelas'=>$kelas->id,
                                'view'=>'table'
                            ]
                        ) }}"
                        class="btn rounded-pill px-4
                        {{ $view=='table'
                            ? 'btn-primary'
                            : 'btn-outline-primary'
                        }}">

                        <i class="bi bi-table me-2"></i>

                        Table View

                    </a>

                </div>

                {{-- RIGHT --}}
                <div class="d-flex flex-wrap gap-2">

                    {{-- APPROVE --}}
                    <form method="POST"
                        action="{{ route('superadmin.monitoring-jadwal.approve',$kelas->id) }}">

                        @csrf

                        <button
                            class="btn btn-success rounded-pill px-4"
                            onclick="return confirm('Approve seluruh jadwal kelas ini?')">

                            <i class="bi bi-check-circle-fill me-2"></i>

                            Approve

                        </button>

                    </form>

                    {{-- REJECT --}}
                    <button
                        class="btn btn-danger rounded-pill px-4"
                        data-bs-toggle="modal"
                        data-bs-target="#rejectModal">

                        <i class="bi bi-x-circle-fill me-2"></i>

                        Reject

                    </button>

                    {{-- PUBLISH --}}
                    <form
                        method="POST"
                        action="{{ route('superadmin.monitoring-jadwal.publish',$kelas->id) }}">

                        @csrf
                        @method('PATCH')

                        <button
                            class="btn btn-primary rounded-pill px-4">

                            <i class="bi bi-send-check-fill me-2"></i>

                            Publish

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

    {{-- CARD --}}
    @if($view == 'grid')
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

        <div class="card-body">

            {{-- TABLE --}}
            <div class="table-responsive">

                <table class="table table-bordered align-middle text-center modern-grid-table mb-0">

                    <thead>

                        <tr class="table-header">

                            <th width="160">
                                Jam
                            </th>

                            @foreach($haris as $hari)

                                <th>

                                    {{ $hari->nama }}

                                </th>

                            @endforeach

                        </tr>

                    </thead>

                    <tbody>

                        @foreach ($jams as $jam)

                            <tr>

                                {{-- JAM --}}
                                <td class="bg-light fw-semibold">

                                    <strong>
                                        Jam {{ $jam->jam_ke }}
                                    </strong>

                                    <br>

                                    <small>
                                        {{ substr($jam->mulai, 0, 5) }}
                                        -
                                        {{ substr($jam->selesai, 0, 5) }}
                                    </small>

                                </td>

                                {{-- SLOT --}}
                                @foreach ($haris as $hari)

                                    @php
                                        $item = $formattedJadwal[$hari->id][$jam->id] ?? null;

                                        $bgColor = '#198754'; // aman
                                        if ($item && ($conflicts[$item->id] ?? false)) {
                                            $bgColor = '#dc3545'; // bentrok
                                        }
                                    @endphp

                                    <td style="min-width:140px;">

                                        @if($item)

                                            <div class="jadwal-card text-white"
                                                style="background:{{ $bgColor }};">

                                                <div class="fw-bold fs-6 mb-1">
                                                    {{ $item->mapel->nama_mapel ?? '-' }}
                                                </div>

                                                <div class="small opacity-90">

                                                    <i class="bi bi-person-fill me-1"></i>

                                                    {{ $item->guru->pegawai->nama ?? '-' }}

                                                </div>

                                                
                                                <br>

                                                @if($conflicts[$item->id] ?? false)

                                                    <div class="mt-2">

                                                        <span class="badge bg-light text-danger">

                                                            <i class="bi bi-exclamation-triangle-fill me-1"></i>

                                                            Bentrok

                                                        </span>

                                                    </div>

                                                    @else

                                                    <div class="mt-2">

                                                        <span class="badge bg-light text-success">

                                                            <i class="bi bi-check-circle-fill me-1"></i>

                                                            Aman

                                                        </span>

                                                    </div>

                                                    @endif

                                            </div>

                                        @else

                                            <div class="text-center py-4 text-secondary">

                                                <i class="bi bi-dash-circle display-6"></i>

                                                <div class="small">

                                                    Tidak Ada Jadwal

                                                </div>

                                            </div>

                                        @endif

                                    </td>

                                @endforeach

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>
    @endif
    
    {{-- ================= TABLE VIEW ================= --}}
    @if($view == 'table')

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

        <div class="card-header bg-white border-0 pt-4 px-4">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="fw-bold mb-1">

                        <i class="bi bi-table text-primary me-2"></i>

                        Daftar Jadwal Pelajaran

                    </h5>

                    <small class="text-muted">

                        Monitoring seluruh jadwal kelas
                        <strong>{{ $kelas->nama_kelas_label }}</strong>

                    </small>

                </div>

                <span class="badge bg-primary rounded-pill px-3 py-2">

                    {{ $jadwals->count() }} Jadwal

                </span>

            </div>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>

                        <tr>

                            <th width="120">

                                Hari

                            </th>

                            <th width="170">

                                Jam

                            </th>

                            <th>

                                Guru

                            </th>

                            <th>

                                Mata Pelajaran

                            </th>

                            <th width="140">

                                Status

                            </th>

                            <th width="140">

                                Monitoring

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($jadwals as $item)

                        <tr>

                            {{-- Hari --}}
                            <td>

                                <span class="badge bg-light text-dark border px-3 py-2">

                                    {{ $item->hari->nama ?? '-' }}

                                </span>

                            </td>

                            {{-- Jam --}}
                            <td>

                                <div class="fw-semibold">

                                    Jam {{ $item->jamPelajaran->jam_ke }}

                                </div>

                                <small class="text-muted">

                                    {{ substr($item->jamPelajaran->mulai,0,5) }}
                                    -
                                    {{ substr($item->jamPelajaran->selesai,0,5) }}

                                </small>

                            </td>

                            {{-- Guru --}}
                            <td>

                                <div class="d-flex align-items-center">

                                    <div class="avatar-circle me-3">

                                        {{ strtoupper(substr($item->guru->pegawai->nama ?? 'A',0,1)) }}

                                    </div>

                                    <div>

                                        <div class="fw-semibold">

                                            {{ $item->guru->pegawai->nama ?? '-' }}

                                        </div>

                                    </div>

                                </div>

                            </td>

                            {{-- Mapel --}}
                            <td>

                                <span class="badge bg-primary-subtle text-primary border px-3 py-2">

                                    {{ $item->mapel->nama_mapel ?? '-' }}

                                </span>

                            </td>

                            {{-- Status --}}
                            <td>

                                @if($item->status=='submitted')

                                    <span class="badge rounded-pill bg-warning px-3 py-2">

                                        <i class="bi bi-hourglass-split me-1"></i>

                                        Submitted

                                    </span>

                                @elseif($item->status=='approved')

                                    <span class="badge rounded-pill bg-success px-3 py-2">

                                        <i class="bi bi-check-circle-fill me-1"></i>

                                        Approved

                                    </span>

                                @elseif($item->status=='rejected')

                                    <span class="badge rounded-pill bg-danger px-3 py-2">

                                        <i class="bi bi-x-circle-fill me-1"></i>

                                        Rejected

                                    </span>

                                @elseif($item->status=='published')

                                    <span class="badge rounded-pill bg-primary px-3 py-2">

                                        <i class="bi bi-send-check-fill me-1"></i>

                                        Published

                                    </span>

                                @else

                                    <span class="badge rounded-pill bg-secondary px-3 py-2">

                                        Draft

                                    </span>

                                @endif

                            </td>

                            {{-- Monitoring --}}
                            <td>

                                @if($conflicts[$item->id] ?? false)

                                    <span class="badge rounded-pill bg-danger px-3 py-2">

                                        <i class="bi bi-exclamation-triangle-fill me-1"></i>

                                        Bentrok

                                    </span>

                                @else

                                    <span class="badge rounded-pill bg-success px-3 py-2">

                                        <i class="bi bi-check-circle-fill me-1"></i>

                                        Aman

                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6">

                                <div class="empty-state">

                                    <i class="bi bi-calendar-x display-4 text-secondary"></i>

                                    <h5 class="mt-3">

                                        Belum Ada Jadwal

                                    </h5>

                                    <p class="text-muted mb-0">

                                        Jadwal pelajaran untuk kelas ini belum tersedia.

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

    @endif

</div>
<div
    class="modal fade"
    id="rejectModal"
    tabindex="-1"
>

    <div class="modal-dialog">

        <div class="modal-content border-0 shadow">

            <form
                method="POST"
                action="{{ route(
                    'superadmin.monitoring-jadwal.reject',
                    $kelas->id
                ) }}"
            >

                @csrf

                <div class="modal-header">

                    <h5 class="modal-title fw-bold">
                        Reject Jadwal
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>

                </div>

                <div class="modal-body">

                    {{-- ALASAN --}}
                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Alasan Reject
                        </label>

                        <select
                            name="alasan"
                            class="form-select"
                        >

                            <option value="">
                                -- Pilih Alasan --
                            </option>

                            <option value="Bentrok Jadwal">
                                Bentrok Jadwal
                            </option>

                            <option value="Jam Terlalu Padat">
                                Jam Terlalu Padat
                            </option>

                            <option value="Slot Tidak Sesuai">
                                Slot Tidak Sesuai
                            </option>

                            <option value="Distribusi Tidak Merata">
                                Distribusi Tidak Merata
                            </option>

                            <option value="Perlu Revisi Manual">
                                Perlu Revisi Manual
                            </option>

                        </select>

                    </div>

                    {{-- HARI --}}
                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Hari Bermasalah
                        </label>

                        <select
                            name="hari"
                            class="form-select"
                        >

                            <option value="">
                                -- Pilih Hari --
                            </option>

                            @foreach($haris as $hari)

                                <option value="{{ $hari->nama ?? $hari->nama_hari }}">

                                    {{ $hari->nama ?? $hari->nama_hari }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- JAM --}}
                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Jam Bermasalah
                        </label>

                        <select
                            name="jam"
                            class="form-select"
                        >

                            <option value="">
                                -- Pilih Jam --
                            </option>

                            @foreach($jams as $jam)

                                <option
                                    value="
                                        Jam {{ $jam->jam_ke }}
                                        ({{ substr($jam->mulai,0,5) }}
                                        - {{ substr($jam->selesai,0,5) }})
                                    "
                                >

                                    Jam {{ $jam->jam_ke }}

                                    —
                                    {{ substr($jam->mulai,0,5) }}

                                    -
                                    {{ substr($jam->selesai,0,5) }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- CATATAN --}}
                    <div>

                        <label class="form-label fw-semibold">
                            Catatan Tambahan
                        </label>

                        <textarea
                            name="catatan_tambahan"
                            class="form-control"
                            rows="3"
                            placeholder="Opsional..."
                        ></textarea>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-light border rounded-pill px-4"
                        data-bs-dismiss="modal">

                        Batal

                    </button>

                    <button
                        type="submit"
                        class="btn btn-danger rounded-pill px-4">

                        <i class="bi bi-x-circle-fill me-2"></i>

                        Reject Jadwal

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<style>

.avatar-circle{

    width:50px;
    height:50px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;

    background:linear-gradient(135deg,#2563eb,#0f4c81);

    color:white;
    font-weight:700;

    box-shadow:0 6px 18px rgba(37,99,235,.25);

}

.card{

    transition:.25s;

}

.card:hover{

    box-shadow:0 15px 40px rgba(15,76,129,.08)!important;

}

.table thead th{

    border:0;
    background:#f8fafc;

    color:#64748b;

    text-transform:uppercase;

    letter-spacing:.5px;

    font-size:.82rem;

    font-weight:700;

    padding:16px;

}

.table tbody td{

    padding:18px 16px;

    vertical-align:middle;

}

.table-hover tbody tr{

    transition:.2s;

}

.table-hover tbody tr:hover{

    background:#f8fbff;

}

.modern-grid-table th{

    background:#0f4c81;

    color:#fff;

    font-weight:600;

}

.modern-grid-table td{

    vertical-align:middle;

}

.jadwal-card{

    min-height:105px;

    border-radius:14px;

    padding:14px;

    transition:.25s;

    box-shadow:0 8px 20px rgba(0,0,0,.08);

}

.jadwal-card:hover{

    transform:translateY(-3px);

    box-shadow:0 15px 30px rgba(0,0,0,.15);

}

.badge{

    font-weight:600;

}

.btn{

    transition:.25s;

}

.btn:hover{

    transform:translateY(-2px);

}

.empty-state{

    text-align:center;

    padding:70px 20px;

}

.modal-content{

    border-radius:18px;

}

.modal-header{

    border-bottom:1px solid #eef2f7;

}

.modal-footer{

    border-top:1px solid #eef2f7;

}

.form-control,
.form-select{

    border-radius:12px;

}

</style>
@endsection