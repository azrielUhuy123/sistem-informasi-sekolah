@extends('layouts.tu')

@section('title', 'Tambah Wali Kelas')

@section('content')

<h1 class="mb-4">Tambah Wali Kelas</h1>

@if($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="card">
    <div class="card-body">

        <form action="{{ route('tu.walikelas.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Guru / Wali Kelas</label>
                <select name="guru_id" class="form-control" required>
                    <option value="">-- Pilih Guru --</option>
                    @foreach($guru as $g)
                    <option value="{{ $g->id }}">{{ $g->nama }} ({{ $g->nip }})</option>
                    @endforeach
                </select>
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
                <label>Tahun Ajaran</label>
                <select name="tahunajaran_id" class="form-control" required>
                    <option value="">-- Pilih --</option>
                    @foreach($tahun as $t)
                    <option value="{{ $t->id }}">
                        {{ $t->tahun }} ({{ $t->semester }})
                    </option>
                    @endforeach
                </select>
            </div>


            <button class="btn btn-primary">Simpan</button>

        </form>

    </div>
</div>

@endsection
