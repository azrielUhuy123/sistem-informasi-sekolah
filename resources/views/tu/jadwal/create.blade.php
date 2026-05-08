@extends('layouts.app')

@section('title', 'Tambah Grup Jadwal')

@section('content')

<div class="container-fluid">

    <h4 class="mb-4">Tambah Grup Jadwal</h4>

    <div class="card">
        <div class="card-body">

            <form action="{{ route('tu.jadwal.store') }}" method="POST">
                @csrf

                {{-- KELAS --}}
                <div class="mb-3">
                    <label class="form-label">Kelas</label>
                    <select name="kelas_master_id" class="form-control" required>
    <option value="">-- Pilih Kelas --</option>
    @foreach($kelas as $k)
        <option value="{{ $k->id }}">
            Kelas {{ $k->tingkat }}
        </option>
    @endforeach
</select>
                </div>

                {{-- TAHUN AJARAN --}}
                <div class="mb-3">
                    <label class="form-label">Tahun Ajaran</label>
                    <select name="tahun_ajaran_id" class="form-control" required>
                        <option value="">-- Pilih Tahun --</option>
                        @foreach($tahun as $t)
                            <option value="{{ $t->id }}">
                                {{ $t->tahun }} - {{ $t->semester }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-primary">Simpan</button>

                    <a href="{{ route('tu.jadwal.index') }}"
                       class="btn btn-secondary">
                        Kembali
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>

@endsection