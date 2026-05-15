<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">

    <style>

        body {
            font-family: sans-serif;
            font-size: 11px;
        }

        h2 {
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
            vertical-align: middle;
        }

        th {
            background: #f2f2f2;
        }

    </style>

</head>
<body>

@foreach($kelas as $k)

    <h2>
        Jadwal Kelas {{ $k->nama_kelas }}
    </h2>

    <table>

        <thead>

            <tr>

                <th width="120">
                    Jam
                </th>

                @foreach($hariList as $hari)

                    <th>
                        {{ $hari->nama }}
                    </th>

                @endforeach

            </tr>

        </thead>

        <tbody>

            @foreach($jamList as $jamPelajaran)

                <tr>

                    <td>

                        {{ substr($jamPelajaran->mulai,0,5) }}
                        -
                        {{ substr($jamPelajaran->selesai,0,5) }}

                    </td>

                    @foreach($hariList as $hari)

                        @php

                            $jadwal = $k->pengajuanJadwal
                                ->where('hari_id', $hari->id)
                                ->where('jam_pelajaran_id', $jamPelajaran->id)
                                ->first();

                        @endphp

                        <td>

                            @if($jadwal)

                                <strong>
                                    {{ $jadwal->mapel->nama_mapel }}
                                </strong>

                                <br>

                                {{ $jadwal->guru->nama }}

                            @else

                                -

                            @endif

                        </td>

                    @endforeach

                </tr>

            @endforeach

        </tbody>

    </table>

@endforeach

</body>
</html>