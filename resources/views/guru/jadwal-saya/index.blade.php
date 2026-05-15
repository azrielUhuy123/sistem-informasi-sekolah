@extends('layouts.app')

@section('title', 'Jadwal Saya')

@section('content')

<div class="container-fluid py-3">

    {{-- HEADER --}}
    <div class="mb-4">

        <h4 class="fw-bold mb-0">
            Jadwal Saya
        </h4>

        <small class="text-muted">
            Scheduling Guru
        </small>

    </div>

    {{-- FILTER --}}
    <div class="card shadow-sm border-0 mb-3">

        <div class="card-body">

            <form method="GET" class="row g-3">

                {{-- KELAS --}}
                <div class="col-md-6">

                    <label class="form-label fw-semibold">
                        Pilih Kelas
                    </label>

                    <select
                        name="kelas_id"
                        class="form-select"
                        onchange="this.form.submit()"
                    >

                        <option value="">
                            -- Pilih Kelas --
                        </option>

                        @foreach(
                            $pengampu->unique('kelas_id')
                            as $item
                        )

                            @if($item->kelas)

                                <option
                                    value="{{ $item->kelas_id }}"
                                    {{ $selectedKelas == $item->kelas_id ? 'selected' : '' }}
                                >

                                    {{ $item->kelas->nama_kelas }}

                                    @if(isset($item->sumber) && $item->sumber === 'wali_kelas')
                                        (Wali Kelas)
                                    @endif

                                </option>

                            @endif

                        @endforeach

                    </select>

                </div>

                {{-- MAPEL --}}
                <div class="col-md-6">

                    <label class="form-label fw-semibold">
                        Pilih Mata Pelajaran
                    </label>

                    <select
                        name="mapel_id"
                        class="form-select"
                        onchange="this.form.submit()"
                    >

                        <option value="">
                            -- Pilih Mata Pelajaran --
                        </option>

                        @foreach(
                            $pengampu
                                ->where('kelas_id', $selectedKelas)
                                ->unique('mata_pelajaran_id')
                            as $item
                        )

                            @if($item->mapel)

                                @php

                                    $jp = $jpTracker[
                                        $item->mata_pelajaran_id
                                    ] ?? null;

                                    $badge = '🟢';

                                    if (
                                        $jp &&
                                        $jp['status'] === 'hampir'
                                    ) {

                                        $badge = '🟡';

                                    }

                                    if (
                                        $jp &&
                                        $jp['status'] === 'penuh'
                                    ) {

                                        $badge = '🔴';

                                    }

                                @endphp

                                <option
                                    value="{{ $item->mata_pelajaran_id }}"
                                    {{ $selectedMapel == $item->mata_pelajaran_id ? 'selected' : '' }}
                                >

                                    {{ $badge }}

                                    {{ $item->mapel->nama_mapel }}

                                    @if($jp)
                                        ({{ $jp['current'] }}/{{ $jp['target'] }})
                                    @endif

                                </option>

                            @endif

                        @endforeach

                    </select>

                </div>

            </form>

        </div>

    </div>

    {{-- INFO JP --}}
    @if($selectedKelas)

        <div class="alert alert-info shadow-sm border-0">

            <strong>Total Jam Pelajaran:</strong>

            {{ $totalJP }} / {{ $maxJP }}

        </div>

    @endif

    {{-- SUBMIT --}}
    @if($selectedKelas && $selectedMapel)

        <div class="mb-3">

            <form
                method="POST"
                action="{{ route('guru.jadwal-saya.submit') }}"
            >

                @csrf

                <input
                    type="hidden"
                    name="kelas_id"
                    value="{{ $selectedKelas }}"
                >

                <button
                    class="btn btn-primary"
                    onclick="return confirm(
                        'Submit seluruh draft jadwal?'
                    )"
                >

                    Submit Jadwal

                </button>

            </form>

        </div>

    @endif

    {{-- TABLE --}}
    <div class="card shadow-sm border-0">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered align-middle text-center mb-0">

                    <thead class="table-light">

                        <tr>

                            <th style="width:120px;">
                                Jam
                            </th>

                            @foreach ($haris as $hari)

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

                                        $item = $jadwals[
                                            $hari->id
                                        ][
                                            $jam->id
                                        ] ?? null;

                                    @endphp

                                    <td style="min-width:140px;">

                                        {{-- ADA JADWAL --}}
                                        @if($item)

                                            <div
                                                class="p-2 rounded text-white position-relative"
                                                style="
                                                    background:
                                                    {{ $item->mapel->warna ?? '#198754' }}
                                                "
                                            >

                                                {{-- HAPUS --}}
                                                @if(
                                                    $item->status === 'draft'
                                                    ||
                                                    $item->status === 'rejected'
                                                )

                                                    <form
                                                        method="POST"
                                                        action="{{ route(
                                                            'guru.jadwal-saya.hapus-slot',
                                                            $item->id
                                                        ) }}"
                                                        class="position-absolute top-0 end-0"
                                                    >

                                                        @csrf
                                                        @method('DELETE')

                                                        <button
                                                            class="btn btn-sm text-white border-0"
                                                            onclick="return confirm('Hapus slot ini?')"
                                                        >
                                                            ×
                                                        </button>

                                                    </form>

                                                @endif

                                                <div class="fw-bold">

                                                    {{ $item->mapel->nama_mapel }}

                                                </div>

                                                <small>

                                                    {{ strtoupper($item->status) }}

                                                </small>

                                            </div>

                                        {{-- BELUM PILIH --}}
                                        @elseif(
                                            !$selectedKelas
                                            ||
                                            !$selectedMapel
                                        )

                                            <div class="text-muted small">

                                                Pilih kelas & mapel

                                            </div>

                                        {{-- JP PENUH --}}
                                        @elseif(
                                            $selectedJP
                                            &&
                                            $selectedJP['status'] === 'penuh'
                                        )

                                            <div class="text-danger small fw-semibold">

                                                JP penuh

                                            </div>

                                        {{-- SLOT KOSONG --}}
                                        @else

                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'guru.jadwal-saya.pilih-slot'
                                                ) }}"
                                            >

                                                @csrf

                                                <input
                                                    type="hidden"
                                                    name="kelas_id"
                                                    value="{{ $selectedKelas }}"
                                                >

                                                <input
                                                    type="hidden"
                                                    name="mapel_id"
                                                    value="{{ $selectedMapel }}"
                                                >

                                                <input
                                                    type="hidden"
                                                    name="hari_id"
                                                    value="{{ $hari->id }}"
                                                >

                                                <input
                                                    type="hidden"
                                                    name="jam_id"
                                                    value="{{ $jam->id }}"
                                                >

                                                <button
                                                    class="btn btn-light border w-100"
                                                    type="submit"
                                                >

                                                    + Pilih

                                                </button>

                                            </form>

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

</div>

@endsection