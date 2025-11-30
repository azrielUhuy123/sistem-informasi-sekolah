<!DOCTYPE html>
<html>
<head>
    <title>Edit Kelas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="p-4">

<div class="container">
    <h1>Edit Kelas</h1>

    <form action="{{ route('tu.kelas.update', $kelas->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nama Kelas</label>
            <input type="text" name="nama_kelas" class="form-control" value="{{ $kelas->nama_kelas }}">
        </div>

        <div class="mb-3">
            <label>Tingkat</label>
            <input type="number" name="tingkat" class="form-control" value="{{ $kelas->tingkat }}">
        </div>

        <div class="mb-3">
            <label>Tahun Ajaran</label>
            <input type="text" name="tahun_ajaran" class="form-control" value="{{ $kelas->tahun_ajaran }}">
        </div>

        <button class="btn btn-primary">Update</button>
    </form>
</div>

</body>
</html>
