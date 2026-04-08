@extends('layouts.tu')

@section('title', 'Manajemen Jadwal')

@section('content')

<div style="padding:20px">

    <h2>Jadwal Pelajaran</h2>

    {{-- FILTER KELAS --}}
    <form method="GET" style="margin-bottom:20px;">
        <label>Pilih Kelas:</label>
        <select name="kelas_id" onchange="this.form.submit()" style="padding:5px;">
            <option value="">-- Pilih Kelas --</option>
            @foreach ($kelasList as $k)
                <option value="{{ $k->id }}" {{ $kelasId == $k->id ? 'selected' : '' }}>
                    {{ $k->nama_kelas }}
                </option>
            @endforeach
        </select>
    </form>
    <a href="{{ route('tu.jadwal.guru') }}">Jadwal Guru</a>
    @if ($kelasId)


        <div style="margin-bottom:10px;">
            <strong>Kelas:</strong>
            {{ $kelasList->firstWhere('id', $kelasId)->nama_kelas ?? '-' }}
        </div>

        <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse; width:100%; text-align:center;">
            
            <thead style="background:#f2f2f2;">
                <tr>
                    <th style="width:80px;">Jam</th>
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

                            $isBentrok = false;
                            $jp = null;
                            $bg = '#f2f2f2'; // default kosong

                            if ($item) {
                                $listGuru = $occupiedGuru[$hari->id][$jam->id] ?? [];

                                if (count(array_filter($listGuru, fn($g) => $g == $item->guru_id)) > 1) {
                                    $isBentrok = true;
                                }

                                // ambil kode_mapel
                                $kode = $item->mapel->kode_mapel ?? null;

                                // ambil JP status
                                $jp = $jpStatus[$kode] ?? null;

                                // PRIORITAS WARNA
                                if ($isBentrok) {
                                    $bg = '#f8d7da'; // merah
                                } elseif ($jp) {
                                    if ($jp['status'] === 'lebih') {
                                        $bg = '#f8d7da'; // merah
                                    } elseif ($jp['current'] >= $jp['target']) {
                                        $bg = '#f5c6cb'; // merah muda (full)
                                    } elseif ($jp['current'] >= ($jp['target'] - 1)) {
                                        $bg = '#fff3cd'; // kuning (hampir)
                                    } else {
                                        $bg = '#d4edda'; // hijau
                                    }
                                } else {
                                    $bg = '#d4edda';
                                }
                            }
                        @endphp

                        <td style="background: {{ $bg }};">
                            <a href="{{ url("/tu/jadwal/$kelasId/$hari->id/$jam->id/edit") }}"
                               style="text-decoration:none; color:black; display:block; padding:10px;">

                                @if ($item)
                                    <div style="font-weight:bold;">
                                        {{ $item->mapel?->nama_mapel ?? '-' }}
                                    </div>

                                    <small>
                                        {{ $item->guru?->nama ?? '-' }}
                                    </small>

                                    @if ($jp)
                                        <div style="font-size:11px; color:#555;">
                                            ({{ $jp['current'] }}/{{ $jp['target'] }})
                                        </div>
                                    @endif

                                    @if ($isBentrok)
                                        <div style="color:red; font-size:12px;">
                                            ⚠ Bentrok
                                        </div>
                                    @endif

                                @else
                                    <span style="color:#aaa;">(kosong)</span>
                                @endif

                            </a>
                        </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>

        </table>

    @endif

</div>
@endsection