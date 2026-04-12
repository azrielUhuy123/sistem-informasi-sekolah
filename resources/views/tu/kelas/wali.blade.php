@extends('layouts.tu')

@section('content')

<div class="container-fluid py-3">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">
                Set Wali Kelas: {{ $kelas->nama_kelas }}
            </h4>
            <small class="text-muted">Tentukan wali kelas untuk rombel ini</small>
        </div>
    </div>

    {{-- CARD FORM --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">

            <form method="POST" action="{{ route('tu.kelas.wali.store', $kelas->id) }}">
                @csrf

                <input type="hidden" name="kelas_grup_id" value="{{ $kelas->kelas_grup_id }}">

                {{-- ERROR --}}
                @error('guru_id')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                @enderror

                {{-- SELECT GURU --}}
                <div class="mb-3">
                    <label class="form-label">Pilih Guru</label>

                    <select name="guru_id" class="form-control">
                        <option value="">-- Pilih Guru --</option>

                        @foreach ($gurus as $g)
                            @php
                                $isWali = in_array($g->id, $waliGuruIds ?? []);
                                $isCurrent = optional($kelas->waliKelas)->guru_id == $g->id;
                            @endphp

                            <option value="{{ $g->id }}"
                                {{ $isCurrent ? 'selected' : '' }}
                                {{ $isWali && !$isCurrent ? 'disabled' : '' }}>

                                {{ $g->nama }}
                                {{ $isCurrent ? ' (Wali saat ini)' : ($isWali ? ' (Sudah menjadi wali kelas)' : '') }}

                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- ACTION --}}
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        Simpan
                    </button>

                    <a href="{{ route('tu.kelas.rombel.index', $kelas->kelas_grup_id) }}"
                       class="btn btn-outline-secondary">
                        Kembali
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>

@endsection