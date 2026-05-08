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
            Kelas {{ $kelas->nama_kelas }}
        </small>

    </div>

    {{-- INFO TOTAL JP --}}
    <div class="alert alert-info shadow-sm border-0">

        <strong>Total Jam Pelajaran:</strong>

        {{ $totalJP }} / {{ $maxJP }}

    </div>

    {{-- CARD --}}
    <div class="card shadow-sm border-0">

        <div class="card-body">

            {{-- PILIH MAPEL --}}
            <form method="GET" class="mb-4">

                <label class="form-label fw-semibold">
                    Pilih Mata Pelajaran
                </label>

                <select
                    name="mapel_id"
                    onchange="this.form.submit()"
                    class="form-select"
                >

                    <option value="">
                        -- Pilih Mata Pelajaran --
                    </option>

                    @foreach($mapels as $mapel)

                        @php

                            $jp = $jpTracker[$mapel->id];

                            $badge = '🟢';

                            if ($jp['status'] === 'hampir') {

                                $badge = '🟡';

                            }

                            if ($jp['status'] === 'penuh') {

                                $badge = '🔴';

                            }

                        @endphp

                        <option
                            value="{{ $mapel->id }}"
                            {{ $selectedMapel == $mapel->id ? 'selected' : '' }}
                        >

                            {{ $badge }}

                            {{ $mapel->nama_mapel }}

                            ({{ $jp['current'] }}/{{ $jp['target'] }})

                        </option>

                    @endforeach

                </select>

            </form>

            {{-- SELECTED JP --}}
            @php

                $selectedJP = null;

                if ($selectedMapel) {

                    $selectedJP = $jpTracker[$selectedMapel] ?? null;

                }

            @endphp

            <div class="mb-3">

                <form
                    method="POST"
                    action="{{ route('guru.jadwal-saya.submit') }}"
                >

                    @csrf

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

                                        $item = $jadwals[$hari->id][$jam->id] ?? null;

                                    @endphp

                                    <td style="min-width:140px;">

                                        {{-- SUDAH ADA --}}
                                        @if($item)

                                            <div
                                                class="p-2 rounded text-white position-relative"
                                                style="background: {{ $item->mapel->warna ?? '#198754' }}"
                                            >

                                                {{-- HAPUS --}}
                                                @if(
                                                    $item->status === 'draft' ||
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

                                        {{-- BELUM PILIH MAPEL --}}
                                        @elseif(!$selectedMapel)

                                            <div class="text-muted small">

                                                Pilih mapel dulu

                                            </div>

                                        {{-- JP PENUH --}}
                                        @elseif(
                                            $selectedJP &&
                                            $selectedJP['status'] === 'penuh'
                                        )

                                            <div class="text-danger small fw-semibold">

                                                Jam Pelajaran penuh

                                            </div>

                                        {{-- SLOT KOSONG --}}
                                        @else

                                            <form
                                                method="POST"
                                                action="{{ route('guru.jadwal-saya.pilih-slot') }}"
                                            >

                                                @csrf

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