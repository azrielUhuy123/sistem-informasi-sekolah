@extends('layouts.app')

@section('title', 'Tambah Rombel')

@section('content')
<div class="card shadow-sm">
    <div class="card-body">

        <h5 class="fw-bold">
    Tambah Rombel Kelas {{ $kelasGrup->tingkat }}
</h5>
<p class="text-muted">
    Angkatan {{ $kelasGrup->angkatan->tahun }}
</p>

<form method="POST"
      action="{{ route('tu.kelas.rombel.store', $kelasGrup->id) }}">
    @csrf

    <div class="mb-3">
        <label class="form-label">Kode Kelas</label>
        <input type="text"
               name="kode"
               class="form-control"
               placeholder="A / B / C"
               maxlength="1"
               required>
    </div>
            <button class="btn btn-primary">
                Simpan
            </button>

            <a href="{{ route('tu.kelas.rombel.index', $kelasGrup->id) }}"
               class="btn btn-secondary">
                Batal
            </a>
        </form>

    </div>
</div>
@endsection
