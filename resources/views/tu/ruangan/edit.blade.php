@extends('layouts.tu')

@section('title', 'Edit Ruangan')

@section('content')

<h1 class="mb-4">Edit Ruangan</h1>

<div class="card">
    <div class="card-body">

        {{-- ERROR VALIDATION --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('tu.ruangan.update', $ruangan->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- KODE --}}
            <div class="mb-3">
                <label>Kode Ruangan</label>
                <input type="text" name="kode_ruangan" class="form-control"
                       value="{{ $ruangan->kode_ruangan }}" required>
            </div>

            {{-- NAMA --}}
            <div class="mb-3">
                <label>Nama Ruangan</label>
                <input type="text" name="nama_ruangan" class="form-control"
                       value="{{ $ruangan->nama_ruangan }}" required>
            </div>

            {{-- KAPASITAS --}}
            <div class="mb-3">
                <label>Kapasitas</label>
                <input type="number" name="kapasitas" class="form-control"
                       value="{{ $ruangan->kapasitas }}">
            </div>

            {{-- JENIS --}}
            <div class="mb-3">
                <label>Jenis Ruangan</label>
                <select name="jenis_ruangan" class="form-control">
                    <option value="">-- Pilih Jenis --</option>
                    <option value="Kelas" {{ $ruangan->jenis_ruangan == 'Kelas' ? 'selected':'' }}>Kelas</option>
                    <option value="Lab" {{ $ruangan->jenis_ruangan == 'Lab' ? 'selected':'' }}>Lab</option>
                    <option value="Kantor" {{ $ruangan->jenis_ruangan == 'Kantor' ? 'selected':'' }}>Kantor</option>
                </select>
            </div>

            <button class="btn btn-primary">Update</button>

        </form>

    </div>
</div>

@endsection