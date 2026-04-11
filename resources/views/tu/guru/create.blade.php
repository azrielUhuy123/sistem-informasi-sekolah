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
                <input type="text" name="nip" class="form-control">
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
                <label class="form-label">Mata Pelajaran</label>

                @foreach ($mapel as $m)
                    <div class="form-check">
                        <input 
                            class="form-check-input"
                            type="checkbox"
                            name="mapel_ids[]"
                            value="{{ $m->id }}"

                            {{-- untuk edit --}}
                            @if(isset($guru) && $guru->mapels->contains($m->id)) checked @endif
                        >

                        <label class="form-check-label">
                            {{ $m->nama_mapel }} ({{ $m->fase }})
                        </label>
                    </div>
                @endforeach
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control">
            </div>

            <div class="mb-3">
                <label>Telepon</label>
                <input type="text" name="telepon" class="form-control">
            </div>

            <div class="mb-3">
                <label>Alamat</label>
                <textarea name="alamat" class="form-control" rows="2"></textarea>
            </div>

            <div class="mb-3">
                <label>Foto</label>
                <input type="file" name="foto" class="form-control">
            </div>

            <div class="d-flex gap-2 mt-4">
                
                <button class="btn btn-primary">Simpan</button>
                <a href="{{ route('tu.guru.index') }}" class="btn btn-secondary">Kembali</a>

            </div>

        </form>

    </div>
</div>

@endsection
