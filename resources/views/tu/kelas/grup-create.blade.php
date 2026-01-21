@extends('layouts.tu')

@section('title', 'Tambah Grup Kelas')

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="fw-bold mb-3">Tambah Grup Kelas</h5>

        <form action="{{ route('tu.kelas.grup.store') }}" method="POST">
            @csrf

            <div class="mb-3">
    <label class="form-label fw-semibold">Tingkat</label>
    <select name="kelas_master_id" class="form-select" required>
        <option value="">-- Pilih Tingkat --</option>
        @foreach ($kelasMaster as $km)
            <option value="{{ $km->id }}">
                Kelas {{ $km->tingkat }}
            </option>
        @endforeach
    </select>
</div>




            <div class="mb-3">
                <label class="form-label">Tahun Ajaran</label>
                <select name="tahun_ajaran_id" class="form-select" required>
                    <option value="">-- Pilih Tahun --</option>
                    @foreach($tahunAjaran as $ta)
                        <option value="{{ $ta->id }}">
                            {{ $ta->tahun }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('tu.kelas.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
