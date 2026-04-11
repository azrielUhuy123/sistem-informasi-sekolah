@extends('layouts.tu')

@section('title', 'Manajemen Jadwal')

@section('content')

<div class="container-fluid py-3">

    {{-- HEADER --}}
    <div class="mb-4">
        <h4 class="fw-bold mb-0">Manajemen Jadwal</h4>
        <small class="text-muted">Kelola jadwal pelajaran dan monitoring JP</small>
    </div>

    {{-- CARD FILTER --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">

            <form method="GET" class="row align-items-end g-3">
                
                <div class="col-md-4">
                    <label class="form-label">Pilih Kelas</label>
                    <select name="kelas_id" onchange="this.form.submit()" class="form-control">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach ($kelasList as $k)
                            <option value="{{ $k->id }}" {{ $kelasId == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <a href="{{ route('tu.jadwal.guru') }}" class="btn btn-outline-primary">
                        Lihat Jadwal Guru
                    </a>
                </div>

            </form>

        </div>
    </div>

    @if ($kelasId)

        {{-- INFO KELAS --}}
        <div class="mb-3">
            <strong>Kelas:</strong>
            {{ $kelasList->firstWhere('id', $kelasId)->nama_kelas ?? '-' }}
        </div>

        {{-- TABLE CARD --}}
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-bordered mb-0 text-center align-middle">

                        <thead class="table-light">
                            <tr>
                                <th style="width:100px;">Jam</th>
                                @foreach ($haris as $hari)
                                    <th>{{ $hari->nama }}</th>
                                @endforeach
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($jams as $jam)
                            <tr>
                                <td class="bg-light">
                                    <strong>{{ $jam->jam_ke }}</strong><br>
                                    <small>{{ $jam->mulai }} - {{ $jam->selesai }}</small>
                                </td>

                                @foreach ($haris as $hari)
                                    @php
                                        $item = $jadwals[$hari->id][$jam->id] ?? null;

                                        $isBentrok = false;
                                        $jp = null;
                                        $bg = '#f8f9fa';

                                        if ($item) {
                                            $listGuru = $occupiedGuru[$hari->id][$jam->id] ?? [];

                                            if (count(array_filter($listGuru, fn($g) => $g == $item->guru_id)) > 1) {
                                                $isBentrok = true;
                                            }

                                            $kode = $item->mapel->kode_mapel ?? null;
                                            $jp = $jpStatus[$kode] ?? null;

                                            if ($isBentrok) {
                                                $bg = '#f8d7da';
                                            } elseif ($jp) {
                                                if ($jp['status'] === 'lebih') {
                                                    $bg = '#f8d7da';
                                                } elseif ($jp['current'] >= $jp['target']) {
                                                    $bg = '#f5c6cb';
                                                } elseif ($jp['current'] >= ($jp['target'] - 1)) {
                                                    $bg = '#fff3cd';
                                                } else {
                                                    $bg = '#d4edda';
                                                }
                                            } else {
                                                $bg = '#d4edda';
                                            }
                                        }
                                    @endphp

                                    <td style="background: {{ $bg }}; cursor:pointer;">

                                        <a href="{{ url("/tu/jadwal/$kelasId/$hari->id/$jam->id/edit") }}"
                                           class="d-block text-decoration-none text-dark p-2">

                                            @if ($item)
                                                <div class="fw-bold">
                                                    {{ $item->mapel?->nama_mapel ?? '-' }}
                                                </div>

                                                <small class="text-muted d-block">
                                                    {{ $item->guru?->nama ?? '-' }}
                                                </small>

                                                @if ($jp)
                                                    <div style="font-size:11px;" class="text-secondary">
                                                        ({{ $jp['current'] }}/{{ $jp['target'] }})
                                                    </div>
                                                @endif

                                                @if ($isBentrok)
                                                    <div class="text-danger small">
                                                        ⚠ Bentrok
                                                    </div>
                                                @endif
                                            @else
                                                <span class="text-muted">(kosong)</span>
                                            @endif

                                        </a>

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

</div>

@endsection