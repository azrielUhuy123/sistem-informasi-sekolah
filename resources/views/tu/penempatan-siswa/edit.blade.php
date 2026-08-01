@extends('layouts.app')

@section('title', 'Pindah Kelas')

@section('content')

<div class="container-fluid">

    {{-- STATUS --}}
    @if($tahunAktif->status !== 'aktif')
        <div class="alert alert-warning">
            Mutasi siswa hanya dapat dilakukan pada tahun ajaran aktif.
        </div>
    @endif

    {{-- HEADER --}}
    <div class="mb-4">
        <h4 class="mb-1">Pindah Kelas Siswa</h4>

        <div class="text-muted">
            Memindahkan siswa ke rombel lain pada tahun ajaran aktif.
        </div>

        <div class="text-muted mt-1">
            Tahun Ajaran :
            <strong>{{ $tahunAktif->tahun }}</strong>

            {!! match($tahunAktif->status) {
                'aktif'    => '<span class="badge bg-success ms-2">AKTIF</span>',
                'transisi' => '<span class="badge bg-warning text-dark ms-2">TRANSISI</span>',
                'selesai'  => '<span class="badge bg-secondary ms-2">SELESAI</span>',
            } !!}
        </div>
    </div>

    {{-- ERROR --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">

        <div class="card-body">

            <form
                action="{{ route('tu.penempatan-siswa.update', $penempatan) }}"
                method="POST"
                {{ $tahunAktif->status !== 'aktif' ? 'onsubmit=return false;' : '' }}
            >

                @csrf
                @method('PUT')

                {{-- Nama --}}
                <div class="mb-3">

                    <label class="form-label">
                        Nama Siswa
                    </label>

                    <input
                        type="text"
                        class="form-control rounded-3"
                        value="{{ $penempatan->siswa->nama }}"
                        readonly
                    >

                </div>

                {{-- Kelas sekarang --}}
                <div class="mb-3">

                    <label class="form-label">
                        Kelas Saat Ini
                    </label>

                    <input
                        type="text"
                        class="form-control rounded-3"
                        value="{{ $penempatan->kelas->nama_kelas }}"
                        readonly
                    >

                </div>

                {{-- Kelas tujuan --}}
                <div class="mb-4">

                    <label class="form-label">
                        Pindah ke Kelas
                    </label>

                    <select
                        name="kelas_id"
                        class="form-select rounded-3"
                        required
                        {{ $tahunAktif->status !== 'aktif' ? 'disabled' : '' }}
                    >

                        <option value="">
                            -- Pilih Kelas --
                        </option>

                        @foreach($kelas as $k)

                            @continue($k->id == $penempatan->kelas_id)

                            <option
                                value="{{ $k->id }}"
                                @selected(old('kelas_id') == $k->id)
                            >
                                {{ $k->nama_kelas }}
                            </option>

                        @endforeach

                    </select>

                    <small class="text-muted">
                        Pilih rombel tujuan untuk memindahkan siswa.
                    </small>

                </div>

                {{-- ACTION --}}
                <div class="d-flex justify-content-between">

                    <a
                        href="{{ route('tu.siswa.index') }}"
                        class="btn btn-outline-secondary"
                    >
                        <i class="bi bi-arrow-left me-1"></i>
                        Kembali
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary px-4 btn-confirm"
                        data-title="Perbarui Kelas Siswa"
                        data-message="Penempatan kelas Siswa akan diperbarui."
                        data-button="btn-warning"
                        {{ $tahunAktif->status !== 'aktif' ? 'disabled' : '' }}
                    >
                        <i class="bi bi-save me-1"></i> Simpan Data
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection