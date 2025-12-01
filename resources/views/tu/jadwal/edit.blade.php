@extends('layouts.tu')

@section('title', 'Edit Jadwal')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Edit Jadwal</h1>
    <a href="{{ route('tu.jadwal.index') }}" class="btn btn-secondary">Kembali</a>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-body">
@if ($errors->any())
    <div class="alert alert-danger">
        <strong>{{ $errors->first() }}</strong>
    </div>
@endif

        <form action="{{ route('tu.jadwal.update', $jadwal->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">

                <div class="col-md-4 mb-3">
                    <label>Kelas</label>
                    <select name="kelas_id" class="form-control" required>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}"
                                {{ $jadwal->kelas_id == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label>Mata Pelajaran</label>
                    <select name="mapel_id" class="form-control" required>
                        <option value="">-- Pilih Mapel --</option>
                        @foreach($mapel as $m)
                            <option value="{{ $m->id }}"
                                {{ $jadwal->mapel_id == $m->id ? 'selected' : '' }}>
                                {{ $m->nama_mapel }}
                            </option>
                        @endforeach
                    </select>
                </div>


                <div class="col-md-4 mb-3">
                    <label>Guru Pengajar</label>
                    <select name="guru_id" class="form-control" required>
                        @foreach($guru as $g)
                            <option value="{{ $g->id }}"
                                {{ $jadwal->guru_id == $g->id ? 'selected' : '' }}>
                                {{ $g->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label>Hari</label>
                    <select name="hari" class="form-control" required>
                        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $hari)
                            <option value="{{ $hari }}"
                                {{ $jadwal->hari == $hari ? 'selected' : '' }}>
                                {{ $hari }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label>Jam Mulai</label>
                    <input type="time" name="jam_mulai" class="form-control"
                           value="{{ $jadwal->jam_mulai }}" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label>Jam Selesai</label>
                    <input type="time" name="jam_selesai" class="form-control"
                           value="{{ $jadwal->jam_selesai }}" required>
                </div>

            </div>

            <button class="btn btn-primary w-100 mt-3">Update Jadwal</button>

        </form>

    </div>
</div>

@endsection
