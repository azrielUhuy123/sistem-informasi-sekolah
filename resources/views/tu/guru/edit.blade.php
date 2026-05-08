@extends('layouts.app')

@section('title', 'Edit Guru')

@section('content')

<h1 class="mb-4">Edit Guru</h1>

<div class="card">
    <div class="card-body">

        <form action="{{ route('tu.guru.update', $guru->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- NIP --}}
            <div class="mb-3">
                <label>NIP</label>
                <input type="text" name="nip" 
                       class="form-control" 
                       value="{{ $guru->nip }}" required>
            </div>

            {{-- Nama --}}
            <div class="mb-3">
                <label>Nama Guru</label>
                <input type="text" name="nama" 
                       class="form-control" 
                       value="{{ $guru->nama }}" required>
            </div>

            {{-- Jenis Kelamin --}}
            <div class="mb-3">
                <label>Jenis Kelamin</label>
                <select name="jenis_kelamin" class="form-control" required>
                    <option value="L" {{ $guru->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ $guru->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>

            {{-- Telepon --}}
            <div class="mb-3">
                <label>Telepon</label>
                <input type="text" name="telepon" 
                       class="form-control" 
                       value="{{ $guru->telepon }}">
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" 
                       class="form-control" 
                       value="{{ $guru->email }}">
            </div>

            {{-- Alamat --}}
            <div class="mb-3">
                <label>Alamat</label>
                <textarea name="alamat" class="form-control" rows="2">{{ $guru->alamat }}</textarea>
            </div>

            {{-- Foto --}}
            <div class="mb-3">
                <label>Foto Guru (opsional)</label>
                <input type="file" name="foto" class="form-control">

                @if($guru->foto)
                    <div class="mt-2">
                        <img src="{{ asset('foto-guru/'.$guru->foto) }}" 
                             width="90" height="90" 
                             class="rounded" style="object-fit: cover;">
                    </div>
                @endif
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

                            {{ $guru->mapels->contains($m->id) ? 'checked' : '' }}
                        >

                        <label class="form-check-label">
                            {{ $m->nama_mapel }} ({{ $m->fase }})
                        </label>
                    </div>
                @endforeach
            </div>

            <div class="d-flex gap-2 mt-4">
                <button class="btn btn-primary">Update</button>
                <a href="{{ route('tu.guru.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>

    </div>
</div>

@endsection
