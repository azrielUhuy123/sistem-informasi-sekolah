<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Jadwal Kelas {{ $kelas->nama_kelas }}</title>
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
            text-align: center;
            margin-bottom: -10px;
        }
        p {
            text-align: center;
        }
    </style>
</head>
<body>

<h2>Jadwal Pelajaran</h2>
<p>Kelas: <strong>{{ $kelas->nama_kelas }}</strong></p>

<table>
    <thead>
        <tr>
            <th>Hari</th>
            <th>Mata Pelajaran</th>
            <th>Guru</th>
            <th>Jam Mulai</th>
            <th>Jam Selesai</th>
        </tr>
    </thead>
    <tbody>
        @foreach($jadwal as $j)
        <tr>
            <td>{{ $j->hari }}</td>
            <td>{{ $j->mapel }}</td>
            <td>{{ $j->guru->nama }}</td>
            <td>{{ $j->jam_mulai }}</td>
            <td>{{ $j->jam_selesai }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
