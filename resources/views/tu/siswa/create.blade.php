@extends('layouts.tu')

@section('content')

<h1 class="mb-4">Tambah Siswa</h1>

<div class="card">
    <div class="card-body">

        <form action="{{ route('tu.siswa.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Nama Siswa</label>
                <input type="text" name="nama" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>NIS</label>
                <input type="text" name="nis" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>NISN (opsional)</label>
                <input type="text" name="nisn" class="form-control">
            </div>

            <div class="mb-3">
                <label>Kelas</label>
                <select name="kelas_id" class="form-control" required>
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($kelas as $k)
                        <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Jenis Kelamin</label>
                <select name="jenis_kelamin" class="form-control" required>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" class="form-control">
            </div>

            <div class="mb-3">
                <label>Alamat</label>
                <textarea name="alamat" class="form-control" rows="2"></textarea>
            </div>

            <div class="mb-3">
                <label>Nama Wali</label>
                <input type="text" name="nama_wali" class="form-control">
            </div>

            <div class="mb-3">
                <label>Telepon Wali</label>
                <input type="text" name="telepon_wali" class="form-control">
            </div>

            <button class="btn btn-primary">Simpan</button>
        </form>

    </div>
</div>

@endsection
