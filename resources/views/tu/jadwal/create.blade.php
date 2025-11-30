@extends('layouts.tu')

@section('title', 'Tambah Jadwal')

@section('content')

<h1 class="mb-4">Tambah Jadwal</h1>

<div class="card">
    <div class="card-body">
@if ($errors->any())
    <div class="alert alert-danger">
        <strong>{{ $errors->first() }}</strong>
    </div>
@endif

        <form action="{{ route('tu.jadwal.store') }}" method="POST">
            @csrf

            <div class="row">

                <div class="col-md-4 mb-3">
                    <label>Kelas</label>
                    <select name="kelas_id" class="form-control" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label>Mata Pelajaran</label>
                    <input type="text" name="mapel" class="form-control" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label>Guru Pengajar</label>
                    <select name="guru_id" class="form-control" required>
                        <option value="">-- Pilih Guru --</option>
                        @foreach($guru as $g)
                            <option value="{{ $g->id }}">{{ $g->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label>Hari</label>
                    <select name="hari" class="form-control" required>
                        <option value="">-- Pilih Hari --</option>
                        <option>Senin</option>
                        <option>Selasa</option>
                        <option>Rabu</option>
                        <option>Kamis</option>
                        <option>Jumat</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label>Jam Mulai</label>
                    <input type="time" name="jam_mulai" class="form-control" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label>Jam Selesai</label>
                    <input type="time" name="jam_selesai" class="form-control" required>
                </div>

            </div>

            <button class="btn btn-primary w-100 mt-3">Simpan</button>
        </form>

    </div>
</div>

@endsection
