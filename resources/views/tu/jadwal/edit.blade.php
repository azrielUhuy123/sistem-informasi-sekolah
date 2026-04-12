@extends('layouts.tu')

@section('title', 'Edit Jadwal')

@section('content')

<div class="container-fluid py-3">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Edit Jadwal</h4>
            <small class="text-muted">Atur mata pelajaran dan guru pada slot jadwal</small>
        </div>

        {{-- 🔙 BUTTON KEMBALI (HEADER) --}}
        <a href="{{ route('tu.jadwal.index', ['kelas_id' => $kelasId]) }}"
           class="btn btn-secondary btn-sm">
            ← Kembali
        </a>
    </div>

    {{-- MAPEL CARD --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">

            <form method="GET" class="row g-3 align-items-end">

                <div class="col-md-6">
                    <label class="form-label">Mata Pelajaran</label>
                    <select name="mapel_id" onchange="this.form.submit()" class="form-control">
                        <option value="">-- Pilih Mapel --</option>

                        @foreach($mapels as $mapel)
                            <option value="{{ $mapel->kode_mapel }}"
                                {{ $mapelId == $mapel->kode_mapel ? 'selected' : '' }}
                                @if(isset($jpStatus[$mapel->kode_mapel]) && $jpStatus[$mapel->kode_mapel]['status'] === 'ok')
                                    disabled
                                @endif
                            >
                                {{ $mapel->nama_mapel }}
                                @if(isset($jpStatus[$mapel->kode_mapel]))
                                    ({{ $jpStatus[$mapel->kode_mapel]['current'] }}/{{ $jpStatus[$mapel->kode_mapel]['target'] }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>

            </form>

            {{-- INFO JP --}}
            @if($mapelId && isset($jpStatus[$mapelId]))
                @php $jp = $jpStatus[$mapelId]; @endphp

                <div class="mt-3">
                    <small>
                        <strong>{{ $jp['nama'] }}</strong>
                        ({{ $jp['current'] }}/{{ $jp['target'] }})

                        @if($jp['status'] === 'kurang')
                            <span class="text-warning">Kurang</span>
                        @elseif($jp['status'] === 'lebih')
                            <span class="text-danger">Lebih</span>
                        @else
                            <span class="text-success">OK</span>
                        @endif
                    </small>
                </div>
            @endif

        </div>
    </div>

    {{-- GURU CARD --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">

            <form method="POST" action="{{ url("/tu/jadwal/$kelasId/$hariId/$jamId") }}">

                @csrf

                <input type="hidden" name="mapel_id" value="{{ $mapelId }}">

                <div class="mb-3">
                    <label class="form-label">Pilih Guru</label>

                    <select name="guru_id" class="form-control" required>
                        <option value="">-- Pilih Guru --</option>

                        @foreach ($gurus as $g)
                            @php
                                $isBentrok = in_array($g->id, $occupiedGuruMap ?? []);
                                $total = $guruJam[$g->id] ?? 0;
                                $isWali = $waliKelasId == $g->id;

                                $label = $g->nama;

                                if ($isWali) {
                                    $label .= " (Wali Kelas)";
                                }

                                $label .= " ({$total} jam)";

                                if ($isBentrok) {
                                    $label .= " - Bentrok";
                                } elseif ($total > 24) {
                                    $label .= " - Overload";
                                }
                            @endphp

                            <option value="{{ $g->id }}"
                                {{ old('guru_id', $currentGuruId ?? null) == $g->id ? 'selected' : '' }}
                                @if($isBentrok) disabled @endif
                                style="{{ $isWali ? 'font-weight:bold; color:green;' : '' }}"
                            >
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>

                    @if(!request('mapel_id'))
                        <small class="text-muted">Pilih mapel terlebih dahulu</small>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary">
                    Simpan Jadwal
                </button>

            </form>

        </div>
    </div>

</div>

@endsection