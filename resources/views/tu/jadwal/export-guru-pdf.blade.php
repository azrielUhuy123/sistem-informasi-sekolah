<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Jadwal Guru {{ $guru->pegawai->nama }}</title>
    <style>
        body { font-family: sans-serif; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            font-size: 12px;
            text-align: center;
        }
        th {
            background: #ddd;
        }
        h2 {
            text-align:center;
            margin-bottom: 5px;
        }
        p {
            text-align:center;
            margin: 0;
        }
    </style>
</head>
<body>

<h2>Jadwal Mengajar Guru</h2>
<p><strong>{{ $guru->pegawai->nama }}</strong></p>

<table>
    <thead>
        <tr>
            <th>Hari</th>
            <th>Jam Mulai</th>
            <th>Jam Selesai</th>
            <th>Mata Pelajaran</th>
            <th>Kelas</th>
        </tr>
    </thead>
    <tbody>
        @foreach($jadwal as $j)
        <tr>
            <td>{{ $j->hari }}</td>
            <td>{{ $j->jam_mulai }}</td>
            <td>{{ $j->jam_selesai }}</td>
            <td>{{ $j->mapel }}</td>
            <td>{{ $j->kelas->nama_kelas }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
