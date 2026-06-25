@extends('layouts.app')

@section('title', 'Monitoring Jadwal Kelas ' . $kelas->nama_kelas)

@section('content')

<div class="container-fluid py-3">

    {{-- HEADER --}}
    <div class="mb-4">

        <h4 class="fw-bold mb-0">
            Monitoring Jadwal
        </h4>

        <small class="text-muted">
            Kelas {{ $kelas->nama_kelas }}
        </small>

    </div>

    {{-- INFO RINGKAS --}}
    @php
        $submitted = $jadwals->where('status', 'submitted')->count();
        $approved = $jadwals->where('status', 'approved')->count();
        $rejected = $jadwals->where('status', 'rejected')->count();
    @endphp

    <div class="alert alert-info shadow-sm border-0 mb-4">
        <strong>Total Jadwal:</strong> {{ $jadwals->count() }}
        <span class="mx-2">|</span>
        <strong>Submitted:</strong> {{ $submitted }}
        <span class="mx-2">|</span>
        <strong>Approved:</strong> {{ $approved }}
        <span class="mx-2">|</span>
        <strong>Rejected:</strong> {{ $rejected }}
    </div>
    <div class="d-flex gap-2 mb-4">

        <a
            href="{{ route(
                'superadmin.monitoring-jadwal.show',
                [
                    'kelas' => $kelas->id,
                    'view' => 'grid'
                ]
            ) }}"
            class="
                btn
                {{ $view == 'grid'
                    ? 'btn-primary'
                    : 'btn-outline-primary'
                }}
            "
        >

            Grid View

        </a>

        <a
            href="{{ route(
                'superadmin.monitoring-jadwal.show',
                [
                    'kelas' => $kelas->id,
                    'view' => 'table'
                ]
            ) }}"
            class="
                btn
                {{ $view == 'table'
                    ? 'btn-primary'
                    : 'btn-outline-primary'
                }}
            "
        >

            Table View

        </a>

    </div>

    <div class="d-flex gap-2 mb-4">

        {{-- APPROVE --}}
        <form
            method="POST"
            action="{{ route(
                'superadmin.monitoring-jadwal.approve',
                $kelas->id
            ) }}"
        >

            @csrf

            <button
                class="btn btn-success"
                onclick="return confirm(
                    'Approve seluruh jadwal kelas ini?'
                )"
            >

                Approve Jadwal

            </button>

        </form>

        {{-- REJECT --}}
        <button
            class="btn btn-danger"
            data-bs-toggle="modal"
            data-bs-target="#rejectModal"
        >

            Reject Jadwal

        </button>

        {{-- PUBLISH --}}
        <form
            method="POST"
            action="{{ route(
                'superadmin.monitoring-jadwal.publish',
                $kelas->id
            ) }}"
        >

            @csrf

            <button
                type="submit"
                class="btn btn-primary"
            >

                <i class="bi bi-send-check"></i>

                Publish Jadwal

            </button>

        </form>
    </div>
    {{-- CARD --}}
    @if($view == 'grid')
    <div class="card shadow-sm border-0">

        <div class="card-body">

            {{-- TABLE --}}
            <div class="table-responsive">

                <table class="table table-bordered align-middle text-center mb-0">

                    <thead class="table-light">

                        <tr>

                            <th style="width:120px;">
                                Jam
                            </th>

                            @foreach ($haris as $hari)

                                <th>
                                    {{ $hari->nama_hari ?? $hari->nama }}
                                </th>

                            @endforeach

                        </tr>

                    </thead>

                    <tbody>

                        @foreach ($jams as $jam)

                            <tr>

                                {{-- JAM --}}
                                <td class="bg-light">

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

                                            <div
                                                class="p-2 rounded text-white position-relative"
                                                style="background: {{ $bgColor }}; min-height: 92px;"
                                            >

                                                <div class="fw-bold">
                                                    {{ $item->mapel->nama_mapel ?? '-' }}
                                                </div>

                                                <small>
                                                    {{ $item->guru->pegawai->nama ?? '-' }}
                                                </small>

                                                
                                                <br>

                                                @if($conflicts[$item->id] ?? false)
                                                    <small class="fw-bold">
                                                        BENTROK
                                                    </small>
                                                @else
                                                    <small>
                                                        AMAN
                                                    </small>
                                                @endif

                                            </div>

                                        @else

                                            <div class="text-muted small py-4">
                                                Kosong
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
    
    {{-- TABLE --}}

    @if($view == 'table')

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <div class="table-responsive">

                <table
                    class="table table-striped align-middle"
                >

                    <thead>

                        <tr>

                            <th>Hari</th>
                            <th>Jam</th>
                            <th>Guru</th>
                            <th>Mapel</th>
                            <th>Status</th>
                            <th>Monitoring</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($jadwals as $item)

                            <tr>

                                <td>
                                    {{ $item->hari->nama ?? '-' }}
                                </td>

                                <td>

                                    Jam
                                    {{ $item->jamPelajaran->jam_ke }}

                                    <br>

                                    <small class="text-muted">

                                        {{ substr(
                                            $item->jamPelajaran->mulai,
                                            0,
                                            5
                                        ) }}

                                        -

                                        {{ substr(
                                            $item->jamPelajaran->selesai,
                                            0,
                                            5
                                        ) }}

                                    </small>

                                </td>

                                <td>
                                    {{ $item->guru->pegawai->nama ?? '-' }}
                                </td>

                                <td>
                                    {{ $item->mapel->nama_mapel ?? '-' }}
                                </td>

                                <td>

                                    @if($item->status == 'submitted')

                                        <span class="badge bg-warning">
                                            Submitted
                                        </span>

                                    @elseif($item->status == 'approved')

                                        <span class="badge bg-success">
                                            Approved
                                        </span>

                                    @elseif($item->status == 'rejected')

                                        <span class="badge bg-danger">
                                            Rejected
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    @if($conflicts[$item->id] ?? false)

                                        <span class="badge bg-danger">
                                            Bentrok
                                        </span>

                                    @else

                                        <span class="badge bg-success">
                                            Aman
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

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
                        class="btn btn-light border"
                        data-bs-dismiss="modal"
                    >
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="btn btn-danger"
                    >
                        Reject Jadwal
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
@endsection