<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Rekap Pendaftar PPDB</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        h1 { font-size: 16px; margin-bottom: 12px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #555; padding: 5px; text-align: left; }
        th { background: #e8eef5; }
    </style>
</head>
<body>
    <h1>Rekap Pendaftar PPDB</h1>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No. Pendaftaran</th>
                <th>Nama Lengkap</th>
                <th>NIK</th>
                <th>Jenis Kelamin</th>
                <th>Tanggal Pendaftaran</th>
                <th>Tahun Ajaran</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $index => $pendaftar)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $pendaftar->no_pendaftaran }}</td>
                    <td>{{ $pendaftar->nama }}</td>
                    <td>{{ $pendaftar->nik }}</td>
                    <td>{{ $pendaftar->jenis_kelamin }}</td>
                    <td>{{ $pendaftar->tanggal_pendaftaran }}</td>
                    <td>{{ $pendaftar->tahunAjaran?->tahun }}</td>
                    <td>{{ $pendaftar->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">Tidak ada data pendaftar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
