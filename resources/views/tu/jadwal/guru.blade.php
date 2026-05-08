@extends('layouts.app')

@section('title', 'Jadwal Guru')

@section('content')

<div class="container-fluid py-3">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Jadwal Guru</h4>
            <small class="text-muted">Monitoring beban mengajar guru per kelas</small>
        </div>

        <a href="{{ route('tu.jadwal.index') }}" class="btn btn-secondary btn-sm">
            ← Kembali
        </a>
    </div>

    {{-- FILTER --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">

            <form method="GET" class="row align-items-end g-3">

                {{-- GURU --}}
                <div class="col-md-4">
                    <label class="form-label">Pilih Guru</label>
                    <select name="guru_id" class="form-control">
                        <option value="">-- Pilih Guru --</option>
                        @foreach ($gurus as $g)
                            <option value="{{ $g->id }}"
                                {{ $guruId == $g->id ? 'selected' : '' }}>
                                {{ $g->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- KELAS --}}
                <div class="col-md-4">
                    <label class="form-label">Pilih Kelas</label>
                    <select name="kelas_id" class="form-control">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach ($kelasList as $k)
                            <option value="{{ $k->id }}"
                                {{ $kelasId == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- BUTTON --}}
                <div class="col-md-2">
                    <button class="btn btn-primary w-100">
                        Tampilkan
                    </button>
                </div>

            </form>

        </div>
    </div>

    {{-- HASIL --}}
    @if ($guruId && $kelasId)

        @php
            $guru = $gurus->firstWhere('id', $guruId);
            $kelas = $kelasList->firstWhere('id', $kelasId);
        @endphp

        {{-- INFO --}}
        <div class="mb-3">
            <strong>Guru:</strong> {{ $guru?->nama ?? '-' }} <br>
            <strong>Kelas:</strong> {{ $kelas?->nama_kelas ?? '-' }}
        </div>

        {{-- TOTAL JAM --}}
        <div class="mb-4">
            <strong>Total Jam Mengajar:</strong> 
            <span class="fw-bold
                {{ $totalJam > 30 ? 'text-danger' : ($totalJam > 24 ? 'text-warning' : 'text-success') }}">
                {{ $totalJam }} jam
            </span>

            @if ($totalJam > 30)
                <small class="text-danger">(Berat)</small>
            @elseif ($totalJam > 24)
                <small class="text-warning">(Mulai Overload)</small>
            @endif
        </div>

        {{-- TABLE --}}
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">

                <div class="table-responsive">
                    <table class="table table-bordered mb-0 text-center align-middle">

                        <thead class="table-light">
                            <tr>
                                <th style="width:120px;">Jam</th>
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

                                        $bg = '#f8f9fa';

                                        if ($item) {
                                            $kode = $item->mapel->kode_mapel ?? null;
                                            $jp = $jpStatus[$kode] ?? null;

                                            if ($jp) {
                                                if ($jp['status'] === 'lebih') {
                                                    $bg = '#f8d7da'; // merah
                                                } elseif ($jp['status'] === 'full') {
                                                    $bg = '#fff3cd'; // kuning
                                                } else {
                                                    $bg = '#d4edda'; // hijau
                                                }
                                            } else {
                                                $bg = '#d4edda';
                                            }
                                        }
                                    @endphp

                                    <td style="background: {{ $bg }};">

                                        @if ($item)

                                            {{-- STATUS JP --}}
                                            @if(isset($jpStatus[$item->mapel->kode_mapel]))
                                                @php $jp = $jpStatus[$item->mapel->kode_mapel]; @endphp
                                                <div class="text-secondary" style="font-size:11px;">
                                                    ({{ $jp['current'] }}/{{ $jp['target'] }})
                                                </div>
                                            @endif

                                            {{-- MAPEL --}}
                                            <div class="fw-bold">
                                                {{ $item->mapel?->nama_mapel }}
                                            </div>

                                            {{-- KELAS --}}
                                            <small class="text-muted">
                                                {{ $item->kelas?->nama_kelas }}
                                            </small>

                                        @else
                                            <span class="text-muted">(kosong)</span>
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

</div>

@endsection