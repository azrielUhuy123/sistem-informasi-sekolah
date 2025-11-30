@extends('layouts.tu')

@section('content')

<h1 class="mb-4">Edit Siswa</h1>

<div class="card">
    <div class="card-body">

        <form action="{{ route('tu.siswa.update', $siswa->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Nama Siswa</label>
                <input type="text" name="nama" class="form-control" value="{{ $siswa->nama }}" required>
            </div>

            <div class="mb-3">
                <label>NIS</label>
                <input type="text" name="nis" class="form-control" value="{{ $siswa->nis }}" required>
            </div>

            <div class="mb-3">
                <label>NISN (opsional)</label>
                <input type="text" name="nisn" class="form-control" value="{{ $siswa->nisn }}">
            </div>

            <div class="mb-3">
                <label>Kelas</label>
                <select name="kelas_id" class="form-control" required>
                    @foreach($kelas as $k)
                        <option value="{{ $k->id }}" 
                                {{ $siswa->kelas_id == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Jenis Kelamin</label>
                <select name="jenis_kelamin" class="form-control" required>
                    <option value="L" {{ $siswa->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ $siswa->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" 
                       class="form-control" 
                       value="{{ $siswa->tanggal_lahir }}">
            </div>

            <div class="mb-3">
                <label>Alamat</label>
                <textarea name="alamat" class="form-control" rows="2">{{ $siswa->alamat }}</textarea>
            </div>

            <div class="mb-3">
                <label>Nama Wali</label>
                <input type="text" name="nama_wali" class="form-control" value="{{ $siswa->nama_wali }}">
            </div>

            <div class="mb-3">
                <label>Telepon Wali</label>
                <input type="text" name="telepon_wali" class="form-control" value="{{ $siswa->telepon_wali }}">
            </div>

            <button class="btn btn-primary">Update</button>
        </form>

    </div>
</div>

@endsection
