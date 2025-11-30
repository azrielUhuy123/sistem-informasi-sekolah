@extends('layouts.tu')

@section('title', 'Tambah Guru')

@section('content')

<h1 class="mb-4">Tambah Guru</h1>

<div class="card">
    <div class="card-body">

        <form action="{{ route('tu.guru.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label>Nama Guru</label>
                <input type="text" name="nama" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>NIP</label>
                <input type="text" name="nip" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Jenis Kelamin</label>
                <select name="jenis_kelamin" class="form-control" required>
                    <option value="">-- Pilih --</option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Mata Pelajaran</label>
                <select name="mapel" class="form-control" required>
                    <option value="">-- Pilih Mata Pelajaran --</option>

                    @php
                        $mapelList = [
                            'Matematika',
                            'Bahasa Indonesia',
                            'Bahasa Inggris',
                            'IPA',
                            'IPS',
                            'PPKn',
                            'Penjaskes',
                            'Seni Budaya',
                            'TIK',
                            'Agama',
                        ];
                    @endphp

                    @foreach($mapelList as $m)
                        <option value="{{ $m }}">{{ $m }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Email (opsional)</label>
                <input type="email" name="email" class="form-control">
            </div>

            <div class="mb-3">
                <label>Telepon (opsional)</label>
                <input type="text" name="telepon" class="form-control">
            </div>

            <div class="mb-3">
                <label>Alamat</label>
                <textarea name="alamat" class="form-control" rows="2"></textarea>
            </div>

            <div class="mb-3">
            <label>Upload Foto</label>
            <input type="file" name="foto" class="form-control">
        </div>

            <button class="btn btn-primary">Simpan</button>

        </form>


    </div>
</div>

@endsection
