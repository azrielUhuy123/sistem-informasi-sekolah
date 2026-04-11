@extends('layouts.tu')

@section('content')

<h4>Set Wali Kelas: {{ $kelas->nama_kelas }}</h4>

<form method="POST" action="{{ route('tu.kelas.wali.store', $kelas->id) }}">
    @csrf

    <input type="hidden" name="kelas_grup_id" value="{{ $kelas->kelas_grup_id }}">

    @error('guru_id')
        <div class="alert alert-danger">
            {{ $message }}
        </div>
    @enderror

    <select name="guru_id" class="form-control mb-3">
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
                {{ $isCurrent ? ' (Wali saat ini)' : ($isWali ? ' (Sudah wali kelas)' : '') }}

            </option>
        @endforeach
    </select>

    <button type="submit" class="btn btn-primary">Simpan</button>
</form>

@endsection