@extends('layouts.tu')

@section('title', 'Tambah Rombel')

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="fw-bold mb-3">
            Tambah Rombel Kelas {{ $kelasMaster->tingkat }}
            ({{ $tahunAjaran->tahun }})
        </h5>

        <form method="POST"
            action="{{ route('tu.kelas.rombel.store', [
                'kelas_master' => $kelasMaster->id,
                'tahun_ajaran' => $tahunAjaran->id
            ]) }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Kode Rombel</label>
                <input type="text"
                    name="kode"
                    class="form-control"
                    placeholder="A / B / C"
                    maxlength="1"
                    required>
            </div>

            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('tu.kelas.rombel.index', [
                'kelas_master' => $kelasMaster->id,
                'tahun_ajaran' => $tahunAjaran->id
            ]) }}" class="btn btn-secondary">
                Batal
            </a>
        </form>
    </div>
</div>
@endsection
