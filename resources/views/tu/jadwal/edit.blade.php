@extends('layouts.tu')

@section('title', 'Edit Jadwal')

@section('content')
<form method="GET">
    <label>Mapel</label>
    <select name="mapel_id" onchange="this.form.submit()">
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
</form>
<div class="mt-2">
    @if($mapelId && isset($jpStatus[$mapelId]))
        @php $jp = $jpStatus[$mapelId]; @endphp

        <small>
            {{ $jp['nama'] }} 
            ({{ $jp['current'] }}/{{ $jp['target'] }})

            @if($jp['status'] === 'kurang')
                <span style="color: orange;">Kurang</span>
            @elseif($jp['status'] === 'lebih')
                <span style="color: red;">Lebih</span>
            @else
                <span style="color: green;">OK</span>
            @endif
        </small>
    @endif
</div>
<form method="POST" action="{{ url("/tu/jadwal/$kelasId/$hariId/$jamId") }}">
    @csrf

    <input type="hidden" name="mapel_id" value="{{ request('mapel_id') }}">

    <label>Guru</label>
    <select name="guru_id" required>
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
        <small>Pilih mapel terlebih dahulu</small>
    @endif

    <button type="submit">Simpan</button>
</form>
@endsection