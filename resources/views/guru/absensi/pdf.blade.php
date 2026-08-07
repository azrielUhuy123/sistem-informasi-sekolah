<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">

    <title>
        Rekap Absensi
    </title>

    <style>

        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }

        th {
            background: #f0f0f0;
        }

        .title {
            text-align: center;
            margin-bottom: 20px;
        }

    </style>

</head>

<body>

    <div class="title">

        <h2>

            Rekap Absensi Siswa

        </h2>

        <p>

            Kelas {{ $kelas->nama_kelas_label }}

        </p>

        <p>

            Wali Kelas:
            {{ $waliKelas->guru->pegawai->nama }}

        </p>

        <p>

            Mode:
            {{ ucfirst($mode) }}

        </p>

    </div>

    <table>

        <thead>

            <tr>

                <th>No</th>
                <th>Nama Siswa</th>
                <th>Hadir</th>
                <th>Izin</th>
                <th>Sakit</th>
                <th>Alpha</th>

            </tr>

        </thead>

        <tbody>

            @foreach($rekap as $i => $r)

                <tr>

                    <td>

                        {{ $i + 1 }}

                    </td>

                    <td style="text-align:left;">

                        {{ $r['siswa']->nama }}

                    </td>

                    <td>

                        {{ $r['hadir'] }}

                    </td>

                    <td>

                        {{ $r['izin'] }}

                    </td>

                    <td>

                        {{ $r['sakit'] }}

                    </td>

                    <td>

                        {{ $r['alpha'] }}

                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>

    <br><br>

    <table
        style="border:none;"
    >

        <tr>

            <td
                style="
                    border:none;
                    text-align:center;
                "
            >

                Wali Kelas
                <br><br><br><br>

                {{ $waliKelas->guru->pegawai->nama }}

            </td>

            <td
                style="
                    border:none;
                    text-align:center;
                "
            >

                Kepala Sekolah
                <br><br><br><br>

                ____________________

            </td>

        </tr>

    </table>

</body>
</html>