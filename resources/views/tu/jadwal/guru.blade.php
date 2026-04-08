@extends('layouts.tu')

@section('title', 'Jadwal Guru')

@section('content')
<form method="GET">
    <label>Pilih Guru</label>
    <select name="guru_id" onchange="this.form.submit()">
        <option value="">-- Pilih Guru --</option>
        @foreach ($gurus as $g)
            <option value="{{ $g->id }}"
                {{ $guruId == $g->id ? 'selected' : '' }}>
                {{ $g->nama }}
            </option>
        @endforeach
    </select>
</form>

@if ($guruId)
    @php
        $guru = $gurus->firstWhere('id', $guruId);
    @endphp

    <div style="margin:10px 0;">
        <strong>Guru:</strong> {{ $guru?->nama ?? '-' }}
    </div>
@endif

@if ($guruId)
    <div style="margin:10px 0;">
        <strong>Total Jam Mengajar:</strong> 
        <span style="
            color: 
                {{ $totalJam > 30 ? 'red' : ($totalJam > 24 ? 'orange' : 'green') }};
            font-weight: bold;
        ">
            {{ $totalJam }} jam
        </span>

        @if ($totalJam > 30)
            <small style="color:red;">(Berat)</small>
        @elseif ($totalJam > 24)
            <small style="color:orange;">(Mulai Overload)</small>
        @endif
    </div>
@endif

@if ($guruId)

<table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse; width:100%; text-align:center;">

    <thead style="background:#f2f2f2;">
        <tr>
            <th>Jam</th>
            @foreach ($haris as $hari)
                <th>{{ $hari->nama }}</th>
            @endforeach
        </tr>
    </thead>

    <tbody>
        @foreach ($jams as $jam)
        <tr>
            <td>
                <strong>{{ $jam->jam_ke }}</strong><br>
                <small>{{ $jam->mulai }} - {{ $jam->selesai }}</small>
            </td>

            @foreach ($haris as $hari)
                @php
                    $item = $jadwals[$hari->id][$jam->id] ?? null;
                @endphp

                @php
                    $bg = '#f2f2f2';

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
                        @if(isset($jpStatus[$item->mapel->kode_mapel]))
                            @php $jp = $jpStatus[$item->mapel->kode_mapel]; @endphp

                            <div style="font-size:11px; color:#555;">
                                ({{ $jp['current'] }}/{{ $jp['target'] }})
                            </div>
                        @endif
                        <div style="font-weight:bold;">
                            {{ $item->mapel?->nama_mapel }}
                        </div>

                        <small>
                            {{ $item->kelas?->nama_kelas }}
                        </small>
                    @else
                        <span style="color:#aaa;">(kosong)</span>
                    @endif
                </td>

            @endforeach
        </tr>
        @endforeach
    </tbody>

</table>

@endif
@endsection