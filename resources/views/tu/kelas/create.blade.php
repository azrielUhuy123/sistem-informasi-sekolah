<!DOCTYPE html>
<html>
<head>
    <title>Tambah Kelas</title>
</head>
<body>

    <h1>Tambah Kelas</h1>

    <form action="{{ route('tu.kelas.store') }}" method="POST">
        @csrf

        <label>Nama Kelas</label><br>
        <input type="text" name="nama_kelas"><br><br>

        <label>Tingkat</label><br>
        <input type="number" name="tingkat"><br><br>

        <label>Tahun Ajaran</label><br>
        <input type="text" name="tahun_ajaran" placeholder="2024/2025"><br><br>

        <button type="submit">Simpan</button>
    </form>

</body>
</html>
